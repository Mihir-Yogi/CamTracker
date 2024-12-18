<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nvr;
use App\Models\Dvr;
use App\Models\Hdd;
use App\Models\Combo;
use App\Models\Depot;
use App\Models\Location;
use App\Models\Sublocation;

class ComboController extends Controller
{

    public function index(Request $request)
    {
        // Retrieve depot and location filter inputs from the request
        $depotId = $request->input('depot_id');
        $locationId = $request->input('location_id');
    
        // Initialize the query with Combo and its relationships
        $query = Combo::with(['nvr', 'dvr', 'hdd', 'depot', 'location']);
    
        // Apply filtering if depot_id is selected
        if ($depotId) {
            $query->whereHas('depot', function ($q) use ($depotId) {
                $q->where('id', $depotId);
            });
        }
    
        // Apply filtering if location_id is selected
        if ($locationId) {
            $query->whereHas('location', function ($q) use ($locationId) {
                $q->where('id', $locationId);
            });
        }
    
        // Execute query with pagination
        $combos = $query->paginate(5);
    
        // Retrieve all depots for the filter dropdown
        $depots = Depot::all();
    
        // Pass the retrieved records and depots to the view, along with filter inputs
        return view('admin.combo.index', compact('combos', 'depots', 'depotId', 'locationId'));
    }
    public function create()
    {
        $depots = Depot::all(); 
        $sublocations = Sublocation::all();
        return view('admin.combo.combo_add', compact('depots','sublocations'));
    }

    public function show($id)
    {
        $combo = Combo::with(['depot', 'location'])->findOrFail($id);
        return view('admin.combo.show', compact('combo'));
    }
    public function store(Request $request)
    {
        // Validate at least one of NVR or DVR fields, and only require the relevant fields based on presence
        $request->validate([
            // NVR validations
            'nvr_model' => 'nullable|required_without:dvr_model|string|max:255',
            'nvr_serial_number' => 'nullable|required_without:dvr_serial_number|string|unique:nvrs,serial_number|max:255',
            'nvr_purchase_date' => 'nullable|date',
            'nvr_installation_date' => 'nullable|date',
            'nvr_warranty_expiration' => 'nullable|date',
    
            // DVR validations
            'dvr_model' => 'nullable|required_without:nvr_model|string|max:255',
            'dvr_serial_number' => 'nullable|required_without:nvr_serial_number|string|unique:dvrs,serial_number|max:255',
            'dvr_purchase_date' => 'nullable|date',
            'dvr_installation_date' => 'nullable|date',
            'dvr_warranty_expiration' => 'nullable|date',

            // HDD validations
            'hdd_model' => 'required|string|max:255',
            'hdd_serial_number' => 'required|string|unique:hdds,serial_number|max:255',
            'hdd_capacity' => 'required|numeric',
            'hdd_purchase_date' => 'nullable|date',
            'hdd_installation_date' => 'nullable|date',
            'hdd_warranty_expiration' => 'nullable|date',
            'sublocation_id' => 'nullable|string|max:255|required_without:dvrsublocation_id',
            'dvrsublocation_id' => 'nullable|string|max:255|required_without:sublocation_id',
    
            // Combo validations
            'camera_capacity' => 'required|integer|min:1',
            'depot_id' => 'required|exists:depots,id',
            'location_id' => 'required|exists:locations,id',
        ]);
    
        // Create NVR record if NVR fields are provided
        $nvr = null;
        if ($request->filled(['nvr_model', 'nvr_serial_number'])) {
            $nvr = Nvr::create([
                'model' => $request->nvr_model,
                'serial_number' => $request->nvr_serial_number,
                'purchase_date' => $request->nvr_purchase_date,
                'installation_date' => $request->nvr_installation_date,
                'warranty_expiration' => $request->nvr_warranty_expiration,
                'depot_id' => $request->depot_id,
                'location_id' => $request->location_id,
                'sublocation_id' => $request->sublocation_id
            ]);
        }
    
        // Create DVR record if DVR fields are provided
        $dvr = null;
        if ($request->filled(['dvr_model', 'dvr_serial_number'])) {
            $dvr = Dvr::create([
                'model' => $request->dvr_model,
                'serial_number' => $request->dvr_serial_number,
                'purchase_date' => $request->dvr_purchase_date,
                'installation_date' => $request->dvr_installation_date,
                'warranty_expiration' => $request->dvr_warranty_expiration,
                'depot_id' => $request->depot_id,
                'location_id' => $request->location_id,
                'sublocation_id' => $request->dvrsublocation_id
            ]);
        }
    
        // Ensure either NVR or DVR was created before continuing
        if (!$nvr && !$dvr) {
            return back()->withErrors(['device_type' => 'Either NVR or DVR details must be provided.']);
        }
    
        // Create the HDD record
        $hdd = Hdd::create([
            'model' => $request->hdd_model,
            'serial_number' => $request->hdd_serial_number,
            'capacity' => $request->hdd_capacity,
            'purchase_date' => $request->hdd_purchase_date,
            'installation_date' => $request->hdd_installation_date,
            'warranty_expiration' => $request->hdd_warranty_expiration,
            'depot_id' => $request->depot_id,
            'location_id' => $request->location_id,
            'sublocation_id' => $request->sublocation_id ?? $request->dvrsublocation_id
        ]);
    
        // Create the Combo record, linking the NVR, DVR, and HDD records
        $combo = Combo::create([
            'location_id' => $request->location_id,
            'depot_id' => $request->depot_id,
            'nvr_id' => $nvr ? $nvr->id : null,
            'dvr_id' => $dvr ? $dvr->id : null,
            'hdd_id' => $hdd->id,
            'camera_capacity' => $request->camera_capacity,
            'current_cctv_count' => $request->current_cctv_count ?? 0,
        ]);
    
        return redirect()->back()->with('success', 'Combo with NVR, DVR, and HDD created successfully!');
    }
    
    
    public function edit($id)
{
    $combo = Combo::with(['nvr', 'dvr', 'hdd'])->findOrFail($id);
    $depots = Depot::all();
    $locations = Location::all();
    $sublocations = Sublocation::all();

    return view('admin.combo.combo_edit', compact('combo', 'depots', 'locations', 'sublocations'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'nvr_model' => 'nullable|string|max:255',
        'nvr_serial_number' => 'nullable|string|max:255',
        'nvr_purchase_date' => 'nullable|date',
        'nvr_installation_date' => 'nullable|date',
        'nvr_warranty_expiration' => 'nullable|date',

        'dvr_model' => 'nullable|string|max:255',
        'dvr_serial_number' => 'nullable|string|max:255',
        'dvr_purchase_date' => 'nullable|date',
        'dvr_installation_date' => 'nullable|date',
        'dvr_warranty_expiration' => 'nullable|date',

        'hdd_model' => 'required|string|max:255',
        'hdd_serial_number' => 'required|string|max:255',
        'hdd_capacity' => 'required|numeric',
        'hdd_purchase_date' => 'nullable|date',
        'hdd_installation_date' => 'nullable|date',
        'hdd_warranty_expiration' => 'nullable|date',

        'camera_capacity' => 'required|integer|min:1',
        'depot_id' => 'required|exists:depots,id',
        'location_id' => 'required|exists:locations,id',
    ]);

    $combo = Combo::findOrFail($id);

    // Initialize NVR and DVR IDs as null
    $nvrId = null;
    $dvrId = null;

    // Handle NVR or DVR switching
    if ($request->nvr_model && !$request->dvr_model) {
        // If NVR details provided and DVR is empty, ensure DVR is deleted and update/create NVR
        $combo->dvr()->delete();
        $nvr = $combo->nvr()->updateOrCreate([], [
            'model' => $request->nvr_model,
            'serial_number' => $request->nvr_serial_number,
            'purchase_date' => $request->nvr_purchase_date,
            'installation_date' => $request->nvr_installation_date,
            'warranty_expiration' => $request->nvr_warranty_expiration,
            'location_id' => $request->location_id,
            'depot_id' => $request->depot_id,
        ]);
        $nvrId = $nvr->id; // Store NVR ID
    } elseif ($request->dvr_model && !$request->nvr_model) {
        // If DVR details provided and NVR is empty, ensure NVR is deleted and update/create DVR
        $combo->nvr()->delete();
        $dvr = $combo->dvr()->updateOrCreate([], [
            'model' => $request->dvr_model,
            'serial_number' => $request->dvr_serial_number,
            'purchase_date' => $request->dvr_purchase_date,
            'installation_date' => $request->dvr_installation_date,
            'warranty_expiration' => $request->dvr_warranty_expiration,
            'location_id' => $request->location_id,
            'depot_id' => $request->depot_id,
        ]);
        $dvrId = $dvr->id; // Store DVR ID
    } else {
        // If both NVR and DVR are provided, update both
        if ($request->nvr_model) {
            $nvr = $combo->nvr()->updateOrCreate([], [
                'model' => $request->nvr_model,
                'serial_number' => $request->nvr_serial_number,
                'purchase_date' => $request->nvr_purchase_date,
                'installation_date' => $request->nvr_installation_date,
                'warranty_expiration' => $request->nvr_warranty_expiration,
                'location_id' => $request->location_id,
                'depot_id' => $request->depot_id,
        
            ]);
            $nvrId = $nvr->id; // Store NVR ID
        }

        if ($request->dvr_model) {
            $dvr = $combo->dvr()->updateOrCreate([], [
                'model' => $request->dvr_model,
                'serial_number' => $request->dvr_serial_number,
                'purchase_date' => $request->dvr_purchase_date,
                'installation_date' => $request->dvr_installation_date,
                'warranty_expiration' => $request->dvr_warranty_expiration,
                'location_id' => $request->location_id,
                'depot_id' => $request->depot_id,
            ]);
            $dvrId = $dvr->id; // Store DVR ID
        }
    }

    // Update HDD details
    $combo->hdd->update([
        'model' => $request->hdd_model,
        'serial_number' => $request->hdd_serial_number,
        'capacity' => $request->hdd_capacity,
        'purchase_date' => $request->hdd_purchase_date,
        'installation_date' => $request->hdd_installation_date,
        'warranty_expiration' => $request->hdd_warranty_expiration,
        'location_id' => $request->location_id,
        'depot_id' => $request->depot_id,
    ]);

    // Update Combo record with NVR and DVR IDs
    $combo->update([
        'nvr_id' => $nvrId,
        'dvr_id' => $dvrId,
        'hdd_id' => $combo->hdd->id,
        'depot_id' => $request->depot_id,
        'location_id' => $request->location_id,
        'camera_capacity' => $request->camera_capacity,
    ]);

    return redirect()->route('admin.combos.index')->with('success', 'Combo updated successfully!');
}


    public function destroy($id)
    {
        // Find the combo record with related NVR, DVR, and HDD
        $combo = Combo::with(['nvr', 'dvr', 'hdd'])->findOrFail($id);
    
        // Check and delete related NVR if it exists
        if ($combo->nvr) {
            $combo->nvr->delete();
        }
    
        // Check and delete related DVR if it exists
        if ($combo->dvr) {
            $combo->dvr->delete();
        }
    
        // Check and delete related HDD if it exists
        if ($combo->hdd) {
            $combo->hdd->delete();
        }
    
        // Finally, delete the Combo record itself
        $combo->delete();
    
        return redirect()->route('admin.combos.index')->with('success', 'Combo and its related records deleted successfully!');
    }
}
