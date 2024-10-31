<?php
// app/Http/Controllers/FailureController.php

namespace App\Http\Controllers;

use App\Models\Nvr; // Ensure you have the right models imported
use App\Models\Dvr;
use App\Models\Hdd;
use App\Models\Cctv;

class FailureController extends Controller
{
    public function index()
    {
        // Retrieve items with status 'failed'
        $failedNvr = Nvr::where('status', 'failed')->get();
        $failedDvr = Dvr::where('status', 'failed')->get();
        $failedHdd = Hdd::where('status', 'failed')->get();
        $failedCctv = Cctv::where('status', 'failed')->get();

        return view('admin.Replacement_History.index', compact(
            'failedNvr',
            'failedDvr',
            'failedHdd',
            'failedCctv'
        ));
    }
}
