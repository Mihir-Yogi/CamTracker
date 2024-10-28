<?php

namespace App\Http\Controllers;

use App\Models\Cctv;
use App\Models\Combo;
use Illuminate\Http\Request;

class CctvController extends Controller
{
    public function index()
    {
        $cctvs = Cctv::with('combo')->get();
        return view('admin.CCTV.index', compact('cctvs'));
    }

    public function create()
    {
        $combos = Combo::with('depot', 'location')->get();
        return view('admin.CCTV.cctv_add', compact('combos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'model' => 'required|string|max:255',
            'combo_id' => 'required|exists:combos,id',
            'serial_number' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'installation_date' => 'required|date',
            'warranty_expiration' => 'required|date',
        ]);

         // Find the combo and check its camera capacity
        $combo = Combo::findOrFail($request->combo_id);

        if ($combo->current_cctv_count >= $combo->camera_capacity) {
            return redirect()->back()->withErrors(['combo_id' => 'This combo has reached its camera capacity.']);
        }

        Cctv::create($request->all());

        $combo->increment('current_cctv_count');

        return redirect()->route('admin.cctvs.index')->with('success', 'CCTV camera added successfully.');
    }

    public function show(Cctv $cctv)
    {
        return view('admin.CCTV.show', compact('cctv'));
    }

    public function edit(Cctv $cctv)
    {
        $combos = Combo::all();
        return view('admin.CCTV.cctv_edit', compact('cctv', 'combos'));
    }

    public function update(Request $request, Cctv $cctv)
    {
        $request->validate([
            'model' => 'required|string|max:255',
            'combo_id' => 'required|exists:combos,id',
            'purchase_date' => 'nullable|date',
            'installation_date' => 'nullable|date',
            'warranty_expiration' => 'nullable|date',
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
            'serial_number' => 'required|string|unique:cctvs,serial_number', // Ensure it's unique
            'failure_reason' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'installed_date' => 'required|date',
            'expiry_date' => 'required|date',
            'replace_image' => 'required|image|max:2048',
        ]);
    
        // Check if the request has a file
        if ($request->hasFile('replace_image')) {
            $image = $request->file('replace_image');
    
            // Generate a unique file name for the image
            $fileName = 'replace_' . time() . '.' . $image->getClientOriginalExtension();
    
            // Define the path where the image will be stored
            $destinationPath = public_path('uploads/cctvReplace_images');
    
            // Move the uploaded file to the specified directory
            $image->move($destinationPath, $fileName);
    
            // Set the path for the saved image in the `replace_image` attribute
            $cctv->image_replace = 'uploads/cctvReplace_images/' . $fileName;
        }
    
        // Update the status and failure reason for the old CCTV
        $cctv->status = 'failed';
        $cctv->failure_reason = $request->failure_reason;
        $cctv->save();
    
        // Create a new CCTV record with the replacement details
        $newCctv = Cctv::create([
            'model' => $request->input('model'),
            'serial_number' => $request->input('serial_number'),
            'status' => 'working',
            'purchase_date' => $request->input('purchase_date'),
            'installation_date' => $request->input('installed_date'), // Match with your column name
            'warranty_expiration' => $request->input('expiry_date'), // Match with your column name
            'combo_id' => $cctv->combo_id, // Use the same combo_id as the old CCTV
        ]); 
    
        return redirect()->route('admin.cctvs.index')->with('success', 'CCTV camera replaced successfully!');
    }
    

}
