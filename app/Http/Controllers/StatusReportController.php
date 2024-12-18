<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Location;
use App\Models\StatusReport;
use App\Models\CctvStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use App\Exports\ReportExport;
use App\Models\Nvr;
use App\Models\Dvr;
use App\Models\Hdd;
use App\Models\Cctv;
use Symfony\Contracts\Service\Attribute\Required;

class StatusReportController extends Controller
{
    
    public function pdf_generator_get(Request $request){
        
    // Initialize variables for depot, location, start date, and end date
    $depotId = $request->input('depot_id');
    $locationId = $request->input('location_id');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Log the received filter values for debugging
    Log::info('Filtering reports with:', compact('depotId', 'locationId', 'startDate', 'endDate'));

    // Prepare the query to fetch reports based on the selected filters
    $reports = StatusReport::when($depotId, function ($query, $depotId) {
            return $query->where('depot_id', $depotId);
        })
        ->when($locationId, function ($query, $locationId) {
            return $query->where('location_id', $locationId);
        })
        ->when($startDate, function ($query, $startDate) {
            return $query->where('created_at', '>=', $startDate . ' 00:00:00'); // Include start of the day
        })
        ->when($endDate, function ($query, $endDate) {
            return $query->where('created_at', '<=', $endDate . ' 23:59:59'); // Include end of the day
        })
        ->with(['depot', 'location', 'nvr.sublocation', 'dvr.sublocation', 'hdd.sublocation', 'cctv']) // eager load relationships for display
        ->get();
    
        $data=[
            'title' => 'PDF DOWNLOAD',
            'date' => date('d/m/y'),
            'reports' => $reports
        ];
        $pdf = PDF::loadView('myPDF',$data);
        return $pdf->download('Report.pdf');
    }
    public function index(Request $request)
{
    // Get all depots for the dropdown
    $depots = Depot::all();

    // Initialize variables for depot, location, start date, and end date
    $depotId = $request->input('depot_id');
    $locationId = $request->input('location_id');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Log the received filter values for debugging
    Log::info('Filtering reports with:', compact('depotId', 'locationId', 'startDate', 'endDate'));

    // Prepare the query to fetch reports based on the selected filters
    $reports = StatusReport::when($depotId, function ($query, $depotId) {
            return $query->where('depot_id', $depotId);
        })
        ->when($locationId, function ($query, $locationId) {
            return $query->where('location_id', $locationId);
        })
        ->when($startDate, function ($query, $startDate) {
            return $query->where('created_at', '>=', $startDate . ' 00:00:00'); // Include start of the day
        })
        ->when($endDate, function ($query, $endDate) {
            return $query->where('created_at', '<=', $endDate . ' 23:59:59'); // Include end of the day
        })
        ->with(['depot', 'location', 'nvr.sublocation', 'dvr.sublocation', 'hdd.sublocation', 'cctv']) // eager load relationships for display
        ->paginate(5);

    return view('admin.REPORTS.index', compact('reports', 'depots', 'depotId', 'locationId', 'startDate', 'endDate'));
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

        foreach ($request->input('cctv_status', []) as $cameraId => $status) {
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
        'nvr_reason' =>  $request->input('off_reason')["nvr_{$nvrId}"] ?? null, // Serialize to JSON
        'dvr_status' => $request->dvr_status, // Serialize to JSON
        'dvr_reason' => $request->input('off_reason')["dvr_{$dvrId}"] ?? null,
        'hdd_status' => $request->hdd_status, // Serialize to JSON
        'hdd_reason' => $request->input('off_reason')["hdd_{$hddId}"] ?? null, // Serialize to JSON
        'cctv_off_count' => $cctvOffCount,
        'cctv_on_count' => $cctvOnCount,
        'remark_image' => $path,
    ]);

    Log::info('Image Path:', ['path' => $path]);
    Log::info('Status Report Data:', $StatusReport->toArray());

    foreach ($request->input('cctv_status', []) as $cameraId => $status) {
        $offReason = $status === 'OFF' ? ($request->input("cctv_reason.{$cameraId}") ?? '') : null;
    
        DB::table('cctv_status')->insert(
            [
                'cctv_id' => $cameraId,
                'report_id' => $StatusReport->id,
                'status' => $status,
                'off_reason' => $offReason,
                'depot_id' => $request->depot_id,
                'location_id' => $request->location_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
    return redirect()->back()->with('success', 'Status report created successfully.');
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
                ->where('location_id', operator: $request->location_id)
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

    // Retrieve last status report for each device type
    $lastReport = StatusReport::latest()->first();
    $cctvStatuses = CctvStatus::all()->mapWithKeys(function($cctvStatus) {
        return [
            $cctvStatus->cctv_id => [
                'status' => $cctvStatus->status,
                'off_reason' => $cctvStatus->off_reason,  // Assuming the column is 'off_reason'
            ]
        ];
    })->toArray();
    // Create a structure with last known status and reasons, if available
    $deviceStatuses = [
        'nvrs' => $nvrs->map(fn($nvr) => [
            'id' => $nvr->id,
            'model' => $nvr->model,
            'serial_number' => $nvr->serial_number,
            'status' => $lastReport->nvr_status ?? 'ON',
            'reason' => $lastReport->nvr_reason ?? '',
        ]),
        'dvrs' => $dvrs->map(fn($dvr) => [
            'id' => $dvr->id,
            'model' => $dvr->model,
            'serial_number' => $dvr->serial_number,
            'status' => $lastReport->dvr_status ?? 'ON',
            'reason' => $lastReport->dvr_reason ?? '',
        ]),
        'hdds' => $hdds->map(fn($hdd) => [
            'id' => $hdd->id,
            'model' => $hdd->model,
            'serial_number' => $hdd->serial_number,
            'status' => $lastReport->hdd_status ?? 'ON',
            'reason' => $lastReport->hdd_reason ?? '',
        ]),
        'cctvs' => $cctvs->map(fn($cctv) => [
            'id' => $cctv->id,
            'model' => $cctv->model,
            'serial_number' => $cctv->serial_number,
            'status' => $cctvStatuses[$cctv->id]['status'] ?? 'ON', // Status from CctvStatus
            'reason' => $cctvStatuses[$cctv->id]['off_reason'] ?? $lastReport->off_reason ?? '',  // Reason from CctvStatus or StatusReport
        ]),
    ];

    return response()->json($deviceStatuses);
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
    // Validate the request but exclude depot_id and location_id from validation
    $request->validate([
        'nvr_status' => 'nullable|in:ON,OFF',
        'dvr_status' => 'nullable|in:ON,OFF',
        'hdd_status' => 'required|in:ON,OFF',
        'remark_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Find the status report
    $report = StatusReport::findOrFail($id);

    // Update NVR, DVR, and HDD status and reasons
    $report->nvr_status = $request->input('nvr_status') ?? $report->nvr_status; // Keep old value if not provided
    $report->nvr_reason = $request->input('off_reason.nvr') ?? $report->nvr_reason; // Keep old value

    $report->dvr_status = $request->input('dvr_status') ?? $report->dvr_status; // Keep old value
    $report->dvr_reason = $request->input('off_reason.dvr') ?? $report->dvr_reason; // Keep old value

    $report->hdd_status = $request->input('hdd_status') ?? $report->hdd_status; // Keep old value
    $report->hdd_reason = $request->input('off_reason.hdd') ?? $report->hdd_reason; // Keep old value

    // Handle image upload
    if ($request->hasFile('remark_image')) {
        if ($report->remark_image) {
            Storage::delete($report->remark_image);
        }
        $image = $request->file('remark_image');
        $fileName = 'remark_' . time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('uploads/remark_images');
        $image->move($destinationPath, $fileName);
        $report->remark_image = 'uploads/remark_images/' . $fileName;
    }

    // Count CCTV statuses
    $cctvOnCount = 0;
    $cctvOffCount = 0;
    foreach ($request->input('cctv_status', []) as $cameraId => $status) {
        $offReason = $status === 'OFF' ? ($request->input("cctv_reason.{$cameraId}") ?? '') : null;

        DB::table('cctv_status')->updateOrInsert(
            ['cctv_id' => $cameraId, 'report_id' => $report->id],
            [
                'status' => $status,
                'off_reason' => $offReason,
                'depot_id' => $report->depot_id, // Keep the current depot_id
                'location_id' => $report->location_id, // Keep the current location_id
                'updated_at' => now(),
            ]
        );

        // Count ON and OFF statuses
        if ($status === 'ON') {
            $cctvOnCount++;
        } elseif ($status === 'OFF') {
            $cctvOffCount++;
        }
    }

    // Update the counts in the report
    $report->cctv_on_count = $cctvOnCount;
    $report->cctv_off_count = $cctvOffCount;

    // Save the status report
    $report->save();

    return redirect()->route('status_reports.index')->with('success', 'Status report updated successfully.');
}



public function show($id)
{
    $report = StatusReport::with(['depot', 'location', 'nvr', 'dvr', 'hdd', 'cctv', 'cctvStatuses'])->findOrFail($id);
    return view('admin.REPORTS.show', compact('report'));
}



}