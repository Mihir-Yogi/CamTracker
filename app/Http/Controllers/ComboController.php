<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nvr;
use App\Models\Dvr;
use App\Models\Hdd;
use App\Models\Combo;
use App\Models\Depot;
use App\Models\Location;

class ComboController extends Controller
{

    public function index()
    {
        // Retrieve all Combo records with related NVR, DVR, HDD, Depot, and Location data
        $combos = Combo::with(['nvr', 'dvr', 'hdd', 'depot', 'location'])->paginate(10);

        // Pass the retrieved records to the view
        return view('admin.combo.index', compact('combos'));
    }
    public function create()
    {
        $depots = Depot::all(); 
        return view('admin.combo.combo_add', compact('depots'));
    }
    public function store(Request $request)
    {
        $request->validate([
            // NVR validations
            'nvr_model' => 'required|string|max:255',
            'nvr_serial_number' => 'required|string|unique:nvrs,serial_number|max:255',
            'nvr_purchase_date' => 'nullable|date',
            'nvr_installation_date' => 'nullable|date',
            'nvr_warranty_expiration' => 'nullable|date',

            // DVR validations
            'dvr_model' => 'required|string|max:255',
            'dvr_serial_number' => 'required|string|unique:dvrs,serial_number|max:255',
            'dvr_purchase_date' => 'nullable|date',
            'dvr_installation_date' => 'nullable|date',
            'dvr_warranty_expiration' => 'nullable|date',

            // HDD validations
            'hdd_model' => 'required|string|max:255',
            'hdd_serial_number' => 'required|string|unique:dvrs,serial_number|max:255',
            'hdd_capacity' => 'required|numeric',
            'hdd_purchase_date' => 'nullable|date',
            'hdd_installation_date' => 'nullable|date',
            'hdd_warranty_expiration' => 'nullable|date',

            // Combo validations
            'camera_capacity' => 'required|integer|min:1',
            'depot_id' => 'required|exists:depots,id',
            'location_id' => 'required|exists:locations,id',
        ]);

        // Create the NVR record
        $nvr = Nvr::create([
            'model' => $request->nvr_model,
            'serial_number' => $request->nvr_serial_number ,
            'purchase_date' => $request->nvr_purchase_date,
            'installation_date' => $request->nvr_installation_date,
            'warranty_expiration' => $request->nvr_warranty_expiration,
            'depot_id' => $request->depot_id,
            'location_id' => $request->location_id,
        ]);

            // Check if 'dvr_model' exists in the request before creating DVR record
    if (!$request->has('dvr_model')) {
        return back()->withErrors(['dvr_model' => 'DVR model is required but was not provided.']);
    }
        // Create the DVR record
        $dvr = Dvr::create([
            'model' => $request->dvr_model,
            'serial_number' => $request->dvr_serial_number ,
            'purchase_date' => $request->dvr_purchase_date,
            'installation_date' => $request->dvr_installation_date,
            'warranty_expiration' => $request->dvr_warranty_expiration,
            'depot_id' => $request->depot_id,
            'location_id' => $request->location_id,
        ]);

        // Create the HDD record
        $hdd = Hdd::create([
            'model' => $request->hdd_model,
            'serial_number' => $request->hdd_serial_number ,
            'capacity' => $request->hdd_capacity,
            'purchase_date' => $request->hdd_purchase_date,
            'installation_date' => $request->hdd_installation_date,
            'warranty_expiration' => $request->hdd_warranty_expiration,
            'depot_id' => $request->depot_id,
            'location_id' => $request->location_id,
        ]);

        // Create the Combo record, linking the NVR, DVR, and HDD records
        $combo = Combo::create([
            'location_id' => $request->location_id,
            'nvr_id' => $nvr->id,
            'dvr_id' => $dvr->id,
            'hdd_id' => $hdd->id,
            'camera_capacity' => $request->camera_capacity,
            'current_cctv_count' => $request->current_cctv_count ?? 0,
        ]);

        return redirect()->route('admin.combos.index')->with('success', 'Combo with NVR, DVR, and HDD created successfully!');
    }
}
