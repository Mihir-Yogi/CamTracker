<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Location;
use App\Models\StatusReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('admin.REPORTS.report_add', compact('depots'));
    }

    public function store(Request $request)
{
    // Validate incoming request data
    $request->validate([
        'depot_id' => 'required|exists:depots,id',
        'location_id' => 'required|exists:locations,id',
        'nvr_id' => 'required|exists:nvrs,id',
        'dvr_id' => 'required|exists:dvrs,id',
        'hdd_id' => 'required|exists:hdds,id',
        'cctv_id' => 'required|exists:cctvs,id',
        'status' => 'required|in:ON,OFF',
        'off_reason' => 'nullable|string',
        'comments' => 'nullable|string',
    ]);

    // Check if a report already exists for the same location on the current date
    $today = now()->toDateString();
    $existingReport = StatusReport::where('location_id', $request->location_id)
                                   ->whereDate('created_at', $today)
                                   ->first();

    if ($existingReport) {
        return redirect()->back()->withErrors(['location_id' => 'A status report for this location has already been created today.']);
    }

    // Create the new status report
    $report = StatusReport::create([
        'user_id' => Auth::id(),
        'depot_id' => $request->depot_id,
        'location_id' => $request->location_id,
        'nvr_id' => $request->nvr_id,
        'dvr_id' => $request->dvr_id,
        'hdd_id' => $request->hdd_id,
        'cctv_id' => $request->cctv_id,
        'status' => $request->status,
        'off_reason' => $request->off_reason,
        'comments' => $request->comments,
    ]);

    return redirect()->route('status_reports.index')->with('success', 'Status report created successfully.');
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

    
}
