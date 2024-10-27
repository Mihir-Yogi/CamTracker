<?php
namespace App\Http\Controllers;

use App\Models\Nvr;
use Illuminate\Http\Request;
use App\Models\Depot;
use App\Models\Location;
use App\Models\Combo;

class NvrController extends Controller
{
    public function index()
    {
        $nvrs = Nvr::all();
        return view('admin.NVRs.index', compact('nvrs'));
    }

    public function create()
    {
        $depots = Depot::all(); // Fetch all depots
        return view('admin.NVRs.nvr_add', compact('depots'));
    }

    public function replace(Request $request, Nvr $nvr)
    {
        // Validate the replacement request
        $request->validate([
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:nvrs,serial_number', // Ensure it's unique
            'failure_reason' => 'required|string|max:255',
            'purchase_date' => 'nullable|date',
            'installation_date' => 'nullable|date',
            'warranty_expiration' => 'nullable|date',
        ]);
    
        // First, mark the existing NVR as failed (or any other logic you need)
        $nvr->status = 'failed';
        $nvr->failure_reason = $request->failure_reason;
        $nvr->save();
    
        // Create the new NVR
        Nvr::create($request->all());
    
        return redirect()->route('admin.nvrs.index')->with('success', 'NVR replaced successfully!');
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

    return redirect()->route('admin.nvrs.index')->with('success', 'NVR added successfully!');
}

    public function show(Nvr $nvr)
    {
        return view('nvrs.show', compact('nvr'));
    }

    public function edit(Nvr $nvr)
    {
        return view('admin.NVRs.nvr_edit', compact('nvr'));
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
        ]);
    
        // Only allow updating fields that are editable by the user
        $nvr->update([
            'model' => $request->input('model'),
            'serial_number' => $request->input('serial_number'),
            'failure_reason' => $request->input('failure_reason'),
            'purchase_date' => $request->input('purchase_date'),
            'installation_date' => $request->input('installation_date'),
            'warranty_expiration' => $request->input('warranty_expiration'),
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
}
