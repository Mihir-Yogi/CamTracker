<?php
namespace App\Http\Controllers;

use App\Models\Nvr;
use Illuminate\Http\Request;
use App\Models\Depot;
use App\Models\Location;
use App\Models\Combo;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use App\Models\Sublocation;


class NvrController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve filter parameters from the request
        $depotId = $request->input('depot_id');
        $locationId = $request->input('location_id');
    
        // Initialize the query for NVRs
        $query = Nvr::with('sublocation'); 
    
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
    
        // Execute query and get filtered NVRs
        $nvrs = $query->paginate(4);
    
        // Retrieve all depots for filter dropdown
        $depots = Depot::all();
    
        // Pass the NVRs, depots, and selected filters to the view
        return view('admin.NVRs.index', compact('nvrs', 'depots', 'depotId', 'locationId'));
    }
    

    public function create()
    {
        $depots = Depot::all(); // Fetch all depots
        return view('admin.NVRs.nvr_add', compact('depots'));
    }

    public function store(Request $request)
{
    $request->validate([
        'model' => 'required|string|max:255',
        'serial_number' => 'required|string|unique:nvrs,serial_number|max:255',
        'status' => 'required|in:working,failed',
        'purchase_date' => 'required|date',
        'installation_date' => 'required|date',
        'warranty_expiration' => 'required|date',
        'depot_id' => 'required|exists:depots,id',
        'location_id' => 'required|exists:locations,id',
        'camera_capacity' => 'required|integer|min:0',
    ]);

    // Create the NVR record
    $nvr = Nvr::create($request->except(['camera_capacity', 'current_cctv_count']));

    // Associate or create the Combo record and save camera capacity
    $combo = new Combo([
        'camera_capacity' => $request->camera_capacity,
        'current_cctv_count' => 0,
    ]);
    $nvr->combo()->save($combo);

    return redirect()->back()->with('success', 'NVR added successfully!');
}

    public function show(Nvr $nvr)
    {
        $sublocations = Sublocation::all();
        return view('admin.NVRs.show', compact('nvr','sublocations'));
    }

    public function edit(Nvr $nvr)
    {
        $sublocations = Sublocation::all();
        return view('admin.NVRs.nvr_edit', compact('nvr','sublocations'));
    }

    public function update(Request $request, Nvr $nvr)
    {
        // Validate the request data
        $request->validate([
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:nvrs,serial_number,' . $nvr->id . '|max:255',
            'failure_reason' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'installation_date' => 'nullable|date',
            'warranty_expiration' => 'nullable|date',
            'sublocation_id' => 'required|string|max:255'
        ]);
    
        // Only allow updating fields that are editable by the user
        $nvr->update([
            'model' => $request->input('model'),
            'serial_number' => $request->input('serial_number'),
            'failure_reason' => $request->input('failure_reason'),
            'purchase_date' => $request->input('purchase_date'),
            'installation_date' => $request->input('installation_date'),
            'warranty_expiration' => $request->input('warranty_expiration'),
            'sublocation_id' => $request->input('sublocation_id')
        ]);
    
        // Redirect to the NVR index page with a success message
        return redirect()->route('admin.nvrs.index')->with('status', 'NVR updated successfully!');
    }


    public function destroy(Nvr $nvr)
    {
        $nvr->delete();
        return redirect()->route('admin.nvrs.index');
    }

    public function getLocationsByDepot($depotId)
    {
        $locations = Location::where('depot_id', $depotId)->get();

        if ($locations->isEmpty()) {
            return response()->json(['message' => 'No locations found'], 404);
        }

        return response()->json($locations);
    }


    /**
    * Show the replace form for the specified NVR.
    *
    * @param Nvr $nvr
    * @return \Illuminate\View\View
    */
    public function showReplaceForm(Nvr $nvr)
    {
        $sublocations = Sublocation::all();
        // Pass the selected NVR with its depot and location to the view
        return view('admin.NVRs.nvr_replace', compact('nvr','sublocations'));
    }

    /**
    * Handle the replacement of an NVR.
    *
    * @param Request $request
    * @param Nvr $nvr
    * @return \Illuminate\Http\RedirectResponse
    */
    public function replace(Request $request, Nvr $nvr)
    {
        // Validate the replacement request
        $request->validate([
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:nvrs,serial_number', // Ensure it's unique
            'failure_reason' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'installation_date' => 'required|date',
            'warranty_expiration' => 'required|date',
            'replace_image' => 'required|image|max:2048',
            'sublocation_id' => 'required|string|max:255'
        ]);

         // Check if the request has a file
        if ($request->hasFile('replace_image')) {
            $image = $request->file('replace_image');

            // Generate a unique file name for the image
            $fileName = 'replace_' . time() . '.' . $image->getClientOriginalExtension();

            // Define the path where the image will be stored
            $destinationPath = public_path('uploads/nvrReplace_images');

            // Move the uploaded file to the specified directory
            $image->move($destinationPath, $fileName);

            // Set the path for the saved image in the `replace_image` attribute
            $nvr->image_replace = 'uploads/nvrReplace_images/' . $fileName;
        }
        

        $nvr->status = 'NOT WORKING';
        $nvr->failure_reason = $request->failure_reason;
        $nvr->save();

        // Create a new NVR record with the replacement details
        $newNvr= Nvr::create([
            'model' => $request->input('model'),
            'serial_number' => $request->input('serial_number'),
            'status' => 'working',
            'purchase_date' => $request->input('purchase_date'),
            'installation_date' => $request->input('installation_date'),
            'warranty_expiration' => $request->input('warranty_expiration'),
            'depot_id' => $nvr->depot_id,    // Use the same depot as the old NVR
            'location_id' => $nvr->location_id,  // Use the same location as the old NVR
            'sublocation_id' => $nvr->sublocation_id,
        ]);

        $nvr->combo()->update(['nvr_id' => $newNvr->id]);

        return redirect()->route('admin.nvrs.index')->with( 'success', 'NVR replaced successfully!');
    }

}
