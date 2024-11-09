<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Depot;
use App\Models\Location;
use App\Models\Combo;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use App\Models\Hdd;
use App\Models\Sublocation;


class HddController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve filter parameters from the request
        $depotId = $request->input('depot_id');
        $locationId = $request->input('location_id');

        
    
        // Initialize the query for HDDs
        $query = Hdd::with('sublocation'); 
    
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
    
        // Execute query and get filtered HDDs
        $hdds = $query->paginate(5);
    
        // Retrieve all depots for filter dropdown
        $depots = Depot::all();
    
        // Pass the HDDs, depots, and selected filters to the view
        return view('admin.HDDs.index', compact('hdds', 'depots', 'depotId', 'locationId'));
    }
    

    public function create()
    {
        $depots = Depot::all(); // Fetch all depots
        return view('admin.HDDs.hdd_add', compact('depots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:hdds,serial_number|max:255',
            'status' => 'required|in:working,failed',
            'purchase_date' => 'required|date',
            'installation_date' => 'required|date',
            'warranty_expiration' => 'required|date',
            'depot_id' => 'required|exists:depots,id',
            'location_id' => 'required|exists:locations,id',
            'capacity' => 'required|integer|min:0',
        ]);

        // Create the HDD record
        $hdd = Hdd::create($request->except(['capacity', 'current_cctv_count']));

        // Associate or create the Combo record and save camera capacity
        $combo = new Combo([
            'capacity' => $request->capacity,
            'current_cctv_count' => 0,
        ]);
        $hdd->combo()->save($combo);

        return redirect()->back()->with('success', 'HDD added successfully!');
    }

    public function show(Hdd $hdd)
    {
        return view('admin.HDDs.show', compact('hdd'));
    }

    public function edit(Hdd $hdd)
    {
        $sublocations = Sublocation::all();
        return view('admin.HDDs.hdd_edit', compact('hdd','sublocations'));
    }

    public function update(Request $request, Hdd $hdd)
    {
        // Validate the request data
        $request->validate([
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:hdds,serial_number,' . $hdd->id . '|max:255',
            'failure_reason' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'installation_date' => 'nullable|date',
            'warranty_expiration' => 'nullable|date',
            'capacity' => 'nullable|integer|min:0',
            'sublocation_id' => 'required|string|max:255'
        ]);

        // Only allow updating fields that are editable by the user
        $hdd->update([
            'model' => $request->input('model'),
            'serial_number' => $request->input('serial_number'),
            'failure_reason' => $request->input('failure_reason'),
            'purchase_date' => $request->input('purchase_date'),
            'installation_date' => $request->input('installation_date'),
            'warranty_expiration' => $request->input('warranty_expiration'),
            'capacity' => $request->input('capacity'),
            'sublocation_id' => $request->input('sublocation_id')            
        ]);

        return redirect()->route('admin.hdds.index')->with('status', 'HDD updated successfully!');
    }

    public function destroy(Hdd $hdd)
    {
        $hdd->delete();
        return redirect()->route('admin.hdds.index');
    }

    public function getLocationsByDepot($depotId)
    {
        $locations = Location::where('depot_id', $depotId)->get();

        if ($locations->isEmpty()) {
            return response()->json(['message' => 'No locations found'], 404);
        }

        return response()->json($locations);
    }

    public function showReplaceForm(Hdd $hdd)
    {
        $sublocations = Sublocation::all();
        // Pass the selected HDD with its depot and location to the view
        return view('admin.HDDs.hdd_replace', compact('hdd','sublocations'));
    }

    public function replace(Request $request, Hdd $hdd)
    {
        // Validate the replacement request
        $request->validate([
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:hdds,serial_number', // Ensure it's unique
            'failure_reason' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'installation_date' => 'required|date',
            'warranty_expiration' => 'required|date',
            'replace_image' => 'required|image|max:2048',
            'capacity' => 'required|integer|min:0',
            'sublocation_id' => 'required|string|max:255',
        ]);

        // Check if the request has a file
        if ($request->hasFile('replace_image')) {
            $image = $request->file('replace_image');

            // Generate a unique file name for the image
            $fileName = 'replace_' . time() . '.' . $image->getClientOriginalExtension();

            // Define the path where the image will be stored
            $destinationPath = public_path('uploads/hddReplace_images');

            // Move the uploaded file to the specified directory
            $image->move($destinationPath, $fileName);

            // Set the path for the saved image in the `replace_image` attribute
            $hdd->image_replace = 'uploads/hddReplace_images/' . $fileName;
        }

        $hdd->status = 'failed';
        $hdd->failure_reason = $request->failure_reason;
        $hdd->save();

        // Create a new HDD record with the replacement details
        $newHdd = Hdd::create([
            'model' => $request->input('model'),
            'serial_number' => $request->input('serial_number'),
            'status' => 'working',
            'purchase_date' => $request->input('purchase_date'),
            'installation_date' => $request->input('installation_date'),
            'warranty_expiration' => $request->input('warranty_expiration'),
            'depot_id' => $hdd->depot_id,    // Use the same depot as the old HDD
            'location_id' => $hdd->location_id,  // Use the same location as the old HDD
            'capacity' => $hdd->capacity,
            'sublocation_id' => $hdd->sublocation_id,
        ]);

        $hdd->combo()->update(['hdd_id' => $newHdd->id]);

        return redirect()->route('admin.hdds.index')->with('success', 'HDD replaced successfully!');
    }
}
