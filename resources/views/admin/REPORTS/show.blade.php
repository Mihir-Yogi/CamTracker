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

    /* Section Heading Style */
    .section-heading {
        font-size: 20px;
        font-weight: bold;
        margin: 20px 0 10px;
    }

    #downloadImageBtn {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 16px;
        margin-top: 10px;
        cursor: pointer;
        display: inline-block;
    }
</style>

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Report Details</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('status_reports.index') }}">
                        <div class="text-tiny">Reports</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Report Details</div></li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="details-view">
                <table class="details-table">
                    <!-- Depot and Location -->
                    <tr>
                        <th>Depot</th>
                        <td>{{ $report->depot->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td>{{ $report->location->name ?? 'N/A' }}</td>
                    </tr>
                </table>

                <!-- NVR Details -->
                @if($report->nvr)
                    <div class="section-heading">NVR Details</div>
                    <table class="details-table">
                        <tr>
                            <th>NVR Model</th>
                            <td>{{ $report->nvr->model }}</td>
                        </tr>
                        <tr>
                            <th>Sub-Location</th>
                            <td>{{ $report->nvr->sublocation }}</td>
                        </tr>
                        <tr>
                            <th>Serial Number</th>
                            <td>{{ $report->nvr->serial_number }}</td>
                        </tr>
                        <tr>
                            <th>Purchase Date</th>
                            <td>{{ $report->nvr->purchase_date }}</td>
                        </tr>
                        <tr>
                            <th>Installation Date</th>
                            <td>{{ $report->nvr->installation_date }}</td>
                        </tr>
                        <tr>
                            <th>Warranty Expiration</th>
                            <td>{{ $report->nvr->warranty_expiration }}</td>
                        </tr>
                        <tr>
                            <th>STATUS</th>
                            <td>{{ $report->nvr_status }}</td>
                        </tr>
                        <tr>
                            <th>STATUS REMARK</th>
                            <td>{{ $report->nvr_reason }}</td>
                        </tr>
                    </table>
                @endif

                <!-- DVR Details -->
                @if($report->dvr)
                    <div class="section-heading">DVR Details</div>
                    <table class="details-table">
                        <tr>
                            <th>DVR Model</th>
                            <td>{{ $report->dvr->model }}</td>
                        </tr>
                        <tr>
                            <th>Sub-Location</th>
                            <td>{{ $report->dvr->sublocation }}</td>
                        </tr>
                        <tr>
                            <th>Serial Number</th>
                            <td>{{ $report->dvr->serial_number }}</td>
                        </tr>
                        <tr>
                            <th>Purchase Date</th>
                            <td>{{ $report->dvr->purchase_date }}</td>
                        </tr>
                        <tr>
                            <th>Installation Date</th>
                            <td>{{ $report->dvr->installation_date }}</td>
                        </tr>
                        <tr>
                            <th>Warranty Expiration</th>
                            <td>{{ $report->dvr->warranty_expiration }}</td>
                        </tr>
                        <tr>
                            <th>STATUS</th>
                            <td>{{ $report->dvr_status ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>STATUS REMARK</th>
                            <td>{{ $report->dvr_reason ?? 'N/A' }}</td>
                        </tr>
                    </table>
                @endif

                <!-- HDD Details -->
                <div class="section-heading">HDD Details</div>
                <table class="details-table">
                    <tr>
                        <th>Model</th>
                        <td>{{ $report->hdd->model }}</td>
                    </tr>
                    <tr>
                        <th>Sub-Location</th>
                        <td>{{ $report->hdd->sublocation }}</td>
                    </tr>
                    <tr>
                        <th>Serial Number</th>
                        <td>{{ $report->hdd->serial_number }}</td>
                    </tr>
                    <tr>
                        <th>Capacity (GB)</th>
                        <td>{{ $report->hdd->capacity }}</td>
                    </tr>
                    <tr>
                        <th>Purchase Date</th>
                        <td>{{ $report->hdd->purchase_date }}</td>
                    </tr>
                    <tr>
                        <th>Installation Date</th>
                        <td>{{ $report->hdd->installation_date }}</td>
                    </tr>
                    <tr>
                        <th>Warranty Expiration</th>
                        <td>{{ $report->hdd->warranty_expiration }}</td>
                    </tr>
                    <tr>
                        <th>STATUS</th>
                        <td>{{ $report->hdd_status ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>STATUS REMARK</th>
                        <td>{{ $report->hdd_reason ?? 'N/A' }}</td>
                    </tr>
                </table>

                @if($report->cctvStatuses->isNotEmpty())
                    <!-- CCTV Details -->
                    <div class="section-heading">CCTV Details</div>
                    @foreach($report->cctvStatuses as $cctvStatus)
                    <table class="details-table">
                        <tr>
                            <th>Model</th>
                            <td>{{ $cctvStatus->cctv->model ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Serial Number</th>
                            <td>{{ $cctvStatus->cctv->serial_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Purchase Date</th>
                            <td>{{ $cctvStatus->cctv->purchase_date ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Installation Date</th>
                            <td>{{ $cctvStatus->cctv->installation_date ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Warranty Expiration</th>
                            <td>{{ $cctvStatus->cctv->warranty_expiration ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>STATUS</th>
                            <td>{{ $cctvStatus->status ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>STATUS REMARK</th>
                            <td>{{ $cctvStatus->off_reason ?? 'N/A' }}</td>
                        </tr>
                    </table>
                    @endforeach
                @endif

                <!-- Report Details -->
                <div class="section-heading">Report Details</div>
                <table class="details-table">
                    <tr>
                        <th>TOTAL ON CCTV</th>
                        <td>{{ $report->cctv_on_count }}</td>
                    </tr>
                    <tr>
                        <th>TOTAL OFF CCTV</th>
                        <td>{{ $report->cctv_off_count }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $report->updated_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>REMARK IMAGE</th>
                        <td>
                            @if($report->remark_image)
                                <img src="{{ asset($report->remark_image) }}" alt="REMARK IMAGE" style="cursor: pointer; max-width: 100%; height: auto;" class="remark-image" id="remarkImage">
                                <br>
                                <button id="downloadImageBtn">Download Image</button>
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                </table>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('status_reports.index') }}" class="tf-button">Back to List</a>
                    <a href="{{ route('status_reports.edit', $report) }}" class="tf-button">Edit Report</a>
                    <button id="downloadPdfBtn" class="tf-button">Download PDF</button>
                    <button id="downloadExcelBtn" class="tf-button">Download Excel</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('downloadImageBtn').addEventListener('click', function () {
        const image = document.getElementById('remarkImage').src; // Get image source
        const fileName = 'remark_image.jpg'; // Define a name for the downloaded image

        // Create a temporary anchor element
        const downloadLink = document.createElement('a');
        downloadLink.href = image;
        downloadLink.download = fileName;

        // Append the anchor to the body, trigger the download, and remove the anchor
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    });
</script>


@endsection
