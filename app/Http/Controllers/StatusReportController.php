<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Location;
use App\Models\StatusReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Nvr;
use App\Models\Dvr;
use App\Models\Hdd;
use App\Models\Cctv;

class StatusReportController extends Controller
{
    public function index(Request $request)
{
    // Get all depots for the dropdown
    $depots = Depot::all();

    // Initialize variables for depot and location
    $depotId = $request->input('depot_id');
    $locationId = $request->input('location_id');

    // Fetch the status reports based on the selected depot and location
    $reports = StatusReport::when($depotId, function ($query, $depotId) {
            return $query->where('depot_id', $depotId);
        })
        ->when($locationId, function ($query, $locationId) {
            return $query->where('location_id', $locationId);
        })
        ->with(['depot', 'location', 'nvr', 'dvr', 'hdd', 'cctv']) // eager load relationships for display
        ->get();

    return view('admin.REPORTS.index', compact('reports', 'depots', 'depotId', 'locationId'));
}

    public function create()
    {
        $depots = Depot::all(); 
        return view('admin.REPORTS.report_add', compact('depots',));
    }

    public function store(Request $request)
{

    
    // Validate incoming request data
    $request->validate([
        'depot_id' => 'required|exists:depots,id',
        'location_id' => 'required|exists:locations,id',
        'remark_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

        'nvr_status.*' => 'nullable|in:ON,OFF', // Validate each nvr_status item    
        'nvr_reason.*' => 'nullable|string', // Validate each nvr_reason item
        'dvr_status.*' => 'nullable|in:ON,OFF', // Validate each dvr_status item
        'dvr_reason.*' => 'nullable|string', // Validate each dvr_reason item
        'hdd_status.*' => 'nullable|in:ON,OFF', // Validate each hdd_status item
        'hdd_reason.*' => 'nullable|string', // Validate each hdd_reason item
        'cctv_status.*' => 'nullable|in:ON,OFF', 
        'cctv_reason.*' => 'nullable|string',
    ]);

    Log::info('Uploaded Files:', $request->file());
    

        // Initialize counters for CCTV
        $cctvOnCount = 0;
        $cctvOffCount = 0;

        foreach ($request->input('cctv_status', []) as $status) {
            if ($status === 'ON') {
                $cctvOnCount++;
            } elseif ($status === 'OFF') {
                $cctvOffCount++;
            }
        }
        
    // Check if a report already exists for the same location on the current date
    // $today = now()->toDateString();
    // $existingReport = StatusReport::where('location_id', $request->location_id)
    //                                 ->whereDate('created_at', $today)
    //                                 ->first();

    // if ($existingReport) {
    //     return redirect()->back()->withErrors(['location_id' => 'A status report for this location has already been created today.']);
    // }

    $path = null;

    // Check for the uploaded image
    if ($request->hasFile('remark_image')) {
        $image = $request->file('remark_image');
        $fileName = 'remark_' . time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('uploads/remark_images');
        $image->move($destinationPath, $fileName);
        $path = 'uploads/remark_images/' . $fileName; // Set the path to the stored image
    }

    
    $nvrId = $request->input('nvr_id') ?: null; // Use null if nvr_id is not available
    $dvrId = $request->input('dvr_id') ?: null; // Use null if dvr_id is not available
    $hddId = $request->input('hdd_id') ?: null;

    
    // Create the new status report
    $StatusReport = StatusReport::create([
        'user_id' => Auth::id(),
        'depot_id' => $request->depot_id,
        'location_id' => $request->location_id,
        'nvr_id' =>  $nvrId, // Use null if $nvr is not available
        'dvr_id' =>  $dvrId, // Use null if $dvr is not available
        'hdd_id' => $hddId,
        'nvr_status' => $request->nvr_status, // Serialize to JSON
        'nvr_reason' => $request->nvr_reason, // Serialize to JSON
        'dvr_status' => $request->dvr_status, // Serialize to JSON
        'dvr_reason' => $request->dvr_reason, // Serialize to JSON
        'hdd_status' => $request->hdd_status, // Serialize to JSON
        'hdd_reason' => $request->hdd_reason, // Serialize to JSON
        'cctv_off_count' => $cctvOffCount,
        'cctv_on_count' => $cctvOnCount,
        'remark_image' => $path,
    ]);

    Log::info('Image Path:', ['path' => $path]);
    Log::info('Status Report Data:', $StatusReport->toArray());

    foreach ($request->input('cctv_status', []) as $cameraId => $status) {
        $offReason = $status === 'OFF' ? ($request->input("cctv_reason.{$cameraId}") ?? '') : null;
    
        DB::table('cctv_status')->updateOrInsert(
            ['cctv_id' => $cameraId, 'report_id' => $StatusReport->id],
            [
                'status' => $status,
                'off_reason' => $offReason,
                'depot_id' => $request->depot_id,
                'location_id' => $request->location_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
    return redirect()->route('status_reports.index')->with('success', 'Status report created successfully.');
}

// Helper methods to retrieve the NVR, DVR, and HDD if necessary
private function getNvrById($nvrId)
{
    return Nvr::find($nvrId); // Returns null if not found
}

private function getDvrById($dvrId)
{
    return Dvr::find($dvrId); // Returns null if not found
}

private function getHddById($hddId)
{
    return Hdd::find($hddId); // Returns null if not found
}

    public function getDevices(Request $request)
{
    $request->validate([
        'depot_id' => 'required|exists:depots,id',
        'location_id' => 'required|exists:locations,id',
    ]);

    // Get the working NVRs, DVRs, HDDs, and CCTVs based on depot and location
    $nvrs = Nvr::where('depot_id', $request->depot_id)
                ->where('location_id', $request->location_id)
                ->where('status', 'working') // Assuming 'working' means not failed
                ->get();

    $dvrs = Dvr::where('depot_id', $request->depot_id)
                ->where('location_id', $request->location_id)
                ->where('status', 'working')
                ->get();

    $hdds = Hdd::where('depot_id', $request->depot_id)
                ->where('location_id', $request->location_id)
                ->where('status', 'working')
                ->get();

    $cctvs = Cctv::where('depot_id', $request->depot_id)
                ->where('location_id', $request->location_id)
                ->where('status', 'working')
                ->get();

    return response()->json([
        'nvrs' => $nvrs,
        'dvrs' => $dvrs,
        'hdds' => $hdds,
        'cctvs' => $cctvs,
    ]);

}


public function edit($id)
{
    $report = StatusReport::with(['depot', 'location', 'nvr', 'dvr', 'hdd', 'cctv', 'cctvStatuses'])->findOrFail($id);
    $depots = Depot::all(); // To populate depot dropdown
    $locations = Location::where('depot_id', $report->depot_id)->get(); // Locations based on selected depot

    return view('admin.REPORTS.edit_report', compact('report', 'depots', 'locations'));
}
    
public function update(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'depot_id' => 'required|exists:depots,id',
        'location_id' => 'required|exists:locations,id',
        'nvr_status' => 'nullable|in:ON,OFF',
        'dvr_status' => 'nullable|in:ON,OFF',
        'hdd_status' => 'required|in:ON,OFF',
        'remark_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Find the status report
    $report = StatusReport::findOrFail($id);

    // Update NVR status and reason
    $report->nvr_status = $request->input('nvr_status') ?? null;
    $report->nvr_reason = $request->input('off_reason.nvr') ?? null;

    // Update DVR status and reason
    $report->dvr_status = $request->input('dvr_status') ?? null;
    $report->dvr_reason = $request->input('off_reason.dvr') ?? null;

    // Update HDD status and reason
    $report->hdd_status = $request->input('hdd_status');
    $report->hdd_reason = $request->input('off_reason.hdd') ?? null;

    // Handle replacement image upload
    if ($request->hasFile('remark_image')) {
        // Delete the old image if it exists
        if ($report->remark_image) {
            Storage::delete($report->remark_image);
        }
        // Store the new image
        $image = $request->file('remark_image');
        $fileName = 'remark_' . time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('uploads/remark_images');
        $image->move($destinationPath, $fileName);
        $report->remark_image = 'uploads/remark_images/' . $fileName; // Save the path
    }


    // Save the status report
    $report->save();

    // Update CCTV statuses
    foreach ($request->input('cctv_status', []) as $cameraId => $status) {
        $offReason = $status === 'OFF' ? ($request->input("cctv_reason.{$cameraId}") ?? '') : null;

        DB::table('cctv_status')->updateOrInsert(
            ['cctv_id' => $cameraId, 'report_id' => $report->id],
            [
                'status' => $status,
                'off_reason' => $offReason,
                'depot_id' => $request->depot_id,
                'location_id' => $request->location_id,
                'updated_at' => now(),
            ]
        );
    }

    return redirect()->route('status_reports.index')->with('success', 'Status report updated successfully.');
}


}
