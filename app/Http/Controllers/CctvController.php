<?php

namespace App\Http\Controllers;

use App\Models\Cctv;
use App\Models\Combo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Depot;
use App\Models\Sublocation;



class CctvController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve filter parameters from the request
        $depotId = $request->input('depot_id');
        $locationId = $request->input('location_id');
    
        // Initialize the query with related combo, location, and depot data
        $query = Cctv::with(['combo.location.depot']);
    
        // Apply depot filter if depot_id is provided
        if ($depotId) {
            $query->whereHas('combo.location.depot', function ($q) use ($depotId) {
                $q->where('id', $depotId);
            });
        }
    
        // Apply location filter if location_id is provided
        if ($locationId) {
            $query->whereHas('combo.location', function ($q) use ($locationId) {
                $q->where('id', $locationId);
            });
        }
    
        // Execute the query and get the filtered CCTV records
        $cctvs = $query->paginate(5);
    
        // Retrieve all depots for the filter dropdown
        $depots = Depot::all();
    
        // Pass the filtered CCTVs, depots, and selected filter parameters to the view
        return view('admin.CCTV.index', compact('cctvs', 'depots', 'depotId', 'locationId'));
    }
    

    public function create()
    {
        $combos = Combo::with('depot', 'location')->get();
        $sublocations = Sublocation::all();
        return view('admin.CCTV.cctv_add', compact('combos','sublocations'));
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'model' => 'required|string|max:255',
            'combo_id' => 'required|exists:combos,id',
            'sublocation' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'megapixel' => 'required|numeric',
            'purchase_date' => 'required|date',
            'installation_date' => 'required|date',
            'warranty_expiration' => 'required|date',
        ]);
    
        // Find the combo and check its camera capacity
        $combo = Combo::findOrFail($request->combo_id);
    
        if ($combo->current_cctv_count >= $combo->camera_capacity) {
            return redirect()->back()->withErrors(['combo_id' => 'This depot location has reached its camera capacity.']);
        }
    
        // Prepare the data for the CCTV record
        $cctvData = $request->all();
    
        // Retrieve depot_id and location_id from the combo
        $cctvData['depot_id'] = $combo->location->depot_id; // Assuming location is a relationship on combo
        $cctvData['location_id'] = $combo->location_id;     // Directly accessing the location_id
    
        // Create the CCTV record
        Cctv::create($cctvData);
    
        // Increment the current CCTV count for the combo
        $combo->increment('current_cctv_count');
    
        // Redirect back with success message
        return redirect()->back()->with('success', 'CCTV camera added successfully.');
    }

    public function show(Cctv $cctv)
    {
        return view('admin.CCTV.show', compact('cctv'));
    }

    public function edit(Cctv $cctv)
    {
        $combos = Combo::all();
        $sublocations = Sublocation::all();
        return view('admin.CCTV.cctv_edit', compact('cctv', 'combos','sublocations'));
    }

    public function update(Request $request, Cctv $cctv)
    {
        $request->validate([
            'model' => 'required|string|max:255',
            'combo_id' => 'required|exists:combos,id',
            'sublocation' => 'required|string|max:255',
            'purchase_date' => 'nullable|date',
            'installation_date' => 'nullable|date',
            'warranty_expiration' => 'nullable|date',
            'megapixel' => 'required|numeric',
        ]);

        $cctv->update($request->all());
        return redirect()->route('admin.cctvs.index');
    }

    public function destroy(Cctv $cctv)
    {
        $cctv->delete();
        return redirect()->route('admin.cctvs.index');
    }

    public function showReplaceForm(Cctv $cctv)
    {
        // Pass the selected CCTV with its depot and location to the view
        return view('admin.CCTV.cctv_replace', compact('cctv'));
    }

    public function replace(Request $request, Cctv $cctv)
{

    // Validate the replacement request
    $request->validate([
        'model' => 'required|string|max:255',
        'serial_number' => 'required|string|unique:cctvs,serial_number',
        'sublocation' => 'required|string|max:255',
        'failure_reason' => 'required|string|max:255',
        'purchase_date' => 'required|date',
        'installed_date' => 'required|date',
        'expiry_date' => 'required|date',
        'replace_image' => 'required|image|max:2048',
        'megapixel' => 'required|numeric',
    ]);

    // Handle the replacement image
    if ($request->hasFile('replace_image')) {
        $image = $request->file('replace_image');
        $fileName = 'replace_' . time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('uploads/cctvReplace_images');
        $image->move($destinationPath, $fileName);
        $cctv->image_replace = 'uploads/cctvReplace_images/' . $fileName;
    }

    // Update the old CCTV status and failure reason
    $cctv->status = 'failed';
    $cctv->failure_reason = $request->failure_reason;
    $cctv->replaced_by = Auth::user()->id;
    $cctv->save();

    // Create a new CCTV record with the replacement details
    $newCctv = Cctv::create([
        'model' => $request->input('model'),
        'serial_number' => $request->input('serial_number'),
        'sublocation' => 'required|string|max:255',
        'status' => 'working',
        'purchase_date' => $request->input('purchase_date'),
        'installation_date' => $request->input('installed_date'),
        'warranty_expiration' => $request->input('expiry_date'),
        'combo_id' => $cctv->combo_id,
        'megapixel' => $request->input('megapixel'),
    ]);

    return redirect()->route('admin.cctvs.index')->with('success', 'CCTV camera replaced successfully!');
}
    

}
