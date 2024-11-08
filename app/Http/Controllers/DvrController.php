<?php

namespace App\Http\Controllers;

use App\Models\Dvr;
use Illuminate\Http\Request;
use App\Models\Depot;
use App\Models\Location;
use App\Models\Combo;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use App\Models\Sublocation;

class DvrController extends Controller
{
    public function index(Request $request)
{
    // Retrieve filter parameters from the request
    $depotId = $request->input('depot_id');
    $locationId = $request->input('location_id');

    // Initialize the query for DVRs
    $query = Dvr::with('sublocation'); 

    // Apply depot filter if depot_id is provided
    if ($depotId) {
        $query->whereHas('location.depot', function ($q) use ($depotId) {
            $q->where('id', $depotId);
        });
    }

    // Apply location filter if location_id is provided
    if ($locationId) {
        $query->whereHas('location', function ($q) use ($locationId) {
            $q->where('id', $locationId);
        });
    }

    // Execute query and get all filtered DVRs
    $dvrs = $query->paginate(5);

    // Retrieve all depots for the filter dropdown
    $depots = Depot::all();

    // Pass the DVRs, depots, and selected filters to the view
    return view('admin.DVRs.index', compact('dvrs', 'depots', 'depotId', 'locationId'));
}

    public function create()
    {
        $depots = Depot::all(); // Fetch all depots
        return view('admin.DVRs.dvr_add', compact('depots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:dvrs,serial_number|max:255',
            'status' => 'required|in:working,failed',
            'purchase_date' => 'required|date',
            'installation_date' => 'required|date',
            'warranty_expiration' => 'required|date',
            'depot_id' => 'required|exists:depots,id',
            'location_id' => 'required|exists:locations,id',
            'camera_capacity' => 'required|integer|min:0',
        ]);

        // Create the DVR record
        $dvr = Dvr::create($request->except(['camera_capacity', 'current_cctv_count']));

        // Associate or create the Combo record and save camera capacity
        $combo = new Combo([
            'camera_capacity' => $request->camera_capacity,
            'current_cctv_count' => 0,
        ]);
        $dvr->combo()->save($combo);

        return redirect()->back()->with('success', 'DVR added successfully!');
    }

    public function show(Dvr $dvr)
    {
        return view('admin.DVRs.show', compact('dvr'));
    }

    public function edit(Dvr $dvr)
    {
        
        $sublocations = Sublocation::all();
        return view('admin.DVRs.dvr_edit', compact('dvr','sublocations'));
    }

    public function update(Request $request, Dvr $dvr)
    {
        // Validate the request data
        $request->validate([
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:dvrs,serial_number,' . $dvr->id . '|max:255',
            'failure_reason' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'installation_date' => 'nullable|date',
            'warranty_expiration' => 'nullable|date',
            'sublocation_id' => 'required|string|max:255'
        ]);

        // Only allow updating fields that are editable by the user
        $dvr->update([
            'model' => $request->input('model'),
            'serial_number' => $request->input('serial_number'),
            'failure_reason' => $request->input('failure_reason'),
            'purchase_date' => $request->input('purchase_date'),
            'installation_date' => $request->input('installation_date'),
            'warranty_expiration' => $request->input('warranty_expiration'),
            'sublocation_id' => $request->input('sublocation_id')
        ]);

        return redirect()->route('admin.dvrs.index')->with('status', 'DVR updated successfully!');
    }

    public function destroy(Dvr $dvr)
    {
        $dvr->delete();
        return redirect()->route('admin.dvrs.index');
    }

    public function getLocationsByDepot($depotId)
    {
        $locations = Location::where('depot_id', $depotId)->get();

        if ($locations->isEmpty()) {
            return response()->json(['message' => 'No locations found'], 404);
        }

        return response()->json($locations);
    }

    public function showReplaceForm(Dvr $dvr)
    {
        $sublocations = Sublocation::all();
        return view('admin.DVRs.dvr_replace', compact('dvr','sublocations'));
    }

    public function replace(Request $request, Dvr $dvr)
    {
        // Validate the replacement request
        $request->validate([
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:dvrs,serial_number', // Ensure it's unique
            'failure_reason' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'installation_date' => 'required|date',
            'warranty_expiration' => 'required|date',
            'replace_image' => 'required|image|max:2048',
            'sublocation_id' => 'required|string|max:255',
        ]);

        // Check if the request has a file
        if ($request->hasFile('replace_image')) {
            $image = $request->file('replace_image');

            // Generate a unique file name for the image
            $fileName = 'replace_' . time() . '.' . $image->getClientOriginalExtension();

            // Define the path where the image will be stored
            $destinationPath = public_path('uploads/dvrReplace_images');

            // Move the uploaded file to the specified directory
            $image->move($destinationPath, $fileName);

            // Set the path for the saved image in the `replace_image` attribute
            $dvr->image_replace = 'uploads/dvrReplace_images/' . $fileName;
        }

        $dvr->status = 'failed';
        $dvr->failure_reason = $request->failure_reason;
        $dvr->save();

        // Create a new DVR record with the replacement details
        $newDvr = Dvr::create([
            'model' => $request->input('model'),
            'serial_number' => $request->input('serial_number'),
            'status' => 'working',
            'purchase_date' => $request->input('purchase_date'),
            'installation_date' => $request->input('installation_date'),
            'warranty_expiration' => $request->input('warranty_expiration'),
            'depot_id' => $dvr->depot_id,    // Use the same depot as the old DVR
            'location_id' => $dvr->location_id,
            'sublocation_id' => $dvr->sublocation_id,
        ]);

        $dvr->combo()->update(['dvr_id' => $newDvr->id]);

        return redirect()->route('admin.dvrs.index')->with('success', 'DVR replaced successfully!');
    }
}
