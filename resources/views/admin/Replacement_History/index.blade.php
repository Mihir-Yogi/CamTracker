{{-- resources/views/admin/failed_devices/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<style>
    /* General container styling */
    .details-view {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    /* Table Styling */
    .details-table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
        font-size: 16px;
    }

    .details-table th,
    .details-table td {
        padding: 14px 20px;
        border: 1px solid #ddd;
    }

    .details-table th {
        background-color: #f5f5f5;
        font-weight: bold;
        color: #333;
        text-align: left;
        font-size: 18px;
    }

    .details-table td {
        color: #333;
    }

    /* Section Heading Style */
    .section-heading {
        font-size: 20px;
        font-weight: bold;
        margin: 20px 0 10px;
    }

    /* Action buttons styling */
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .action-buttons .tf-button {
        display: inline-block;
        padding: 12px 24px;
        font-size: 16px;
        color: #fff;
        background-color: #007bff;
        text-decoration: none;
        border-radius: 4px;
        text-align: center;
    }

    .action-buttons .tf-button:hover {
        background-color: #0056b3;
    }
</style>

<div class="main-content-inner">
    <div class="main-content-wrap">
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Failed Devices</h3>
        <a href="{{ route('admin.index') }}" class="tf-button"><i class="icon-chevron-right"></i>Back to Dashboard</a>
        </div>
        <div class="details-view">
            <!-- NVR Failures -->
            <div class="section-heading">NVR Failures</div>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Serial Number</th>
                        <th>Model</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($failedNvr as $nvr)
                        <tr>
                            <td>{{ $nvr->serial_number }}</td>
                            <td>{{ $nvr->model }}</td>
                            <td>{{ $nvr->status }}</td>
                            <td>
                                <a href="{{ route('admin.nvrs.show', $nvr->id) }}" style="margin-left: 15px;">
                                    <div class="list-icon-function view-icon">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </div>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No failed NVRs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- DVR Failures -->
            <div class="section-heading">DVR Failures</div>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Serial Number</th>
                        <th>Model</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($failedDvr as $dvr)
                        <tr>
                            <td>{{ $dvr->serial_number }}</td>
                            <td>{{ $dvr->model }}</td>
                            <td>{{ $dvr->status }}</td>
                            <td>
                                <a href="{{ route('admin.dvrs.show', $dvr->id) }}" style="margin-left: 15px;">
                                    <div class="list-icon-function view-icon">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </div>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No failed DVRs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- HDD Failures -->
            <div class="section-heading">HDD Failures</div>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Serial Number</th>
                        <th>Model</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($failedHdd as $hdd)
                        <tr>
                            <td>{{ $hdd->serial_number }}</td>
                            <td>{{ $hdd->model }}</td>
                            <td>{{ $hdd->status }}</td>
                            <td>
                                <a href="{{ route('admin.hdds.show', $hdd->id) }}" style="margin-left: 15px;">
                                    <div class="list-icon-function view-icon">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </div>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No failed HDDs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- CCTV Failures -->
            <div class="section-heading">CCTV Failures</div>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Serial Number</th>
                        <th>Model</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($failedCctv as $cctv)
                        <tr>
                            <td>{{ $cctv->serial_number }}</td>
                            <td>{{ $cctv->model }}</td>
                            <td>{{ $cctv->status }}</td>
                            <td>
                                <a href="{{ route('admin.cctvs.show', $cctv->id) }}" style="margin-left: 15px;">
                                    <div class="list-icon-function view-icon">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </div>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No failed CCTVs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            
        </div>
    </div>
</div>

<script>
    // Add JavaScript functions for downloading PDF/Excel if needed
    document.getElementById('downloadPdfBtn').addEventListener('click', function () {
        // Implement PDF download functionality here
    });

    document.getElementById('downloadExcelBtn').addEventListener('click', function () {
        // Implement Excel download functionality here
    });
</script>

@endsection
