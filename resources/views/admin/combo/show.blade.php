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
</style>

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Combo Details</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('admin.combos.index') }}">
                        <div class="text-tiny">Combos</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Combo Details</div></li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="details-view">
                <table class="details-table">
                    <!-- Depot and Location -->
                    <tr>
                        <th>Depot</th>
                        <td>{{ $combo->depot->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td>{{ $combo->location->name ?? 'N/A' }}</td>
                    </tr>
                </table>

                <div class="section-heading">NVR Details</div>
                <table class="details-table">
                    <!-- NVR Details -->
                    <tr>
                        <th>NVR Model</th>
                        <td>{{ $combo->nvr->model }}</td>
                    </tr>
                    <tr>
                        <th>NVR Serial Number</th>
                        <td>{{ $combo->nvr->serial_number }}</td>
                    </tr>
                    <tr>
                        <th>NVR Purchase Date</th>
                        <td>{{ $combo->nvr->purchase_date }}</td>
                    </tr>
                    <tr>
                        <th>NVR Installation Date</th>
                        <td>{{ $combo->nvr->installation_date }}</td>
                    </tr>
                    <tr>
                        <th>NVR Warranty Expiration</th>
                        <td>{{ $combo->nvr->warranty_expiration }}</td>
                    </tr>
                </table>

                <div class="section-heading">DVR Details</div>
                <table class="details-table">
                    <!-- DVR Details -->
                    <tr>
                        <th>DVR Model</th>
                        <td>{{ $combo->dvr->model }}</td>
                    </tr>
                    <tr>
                        <th>DVR Serial Number</th>
                        <td>{{ $combo->dvr->serial_number }}</td>
                    </tr>
                    <tr>
                        <th>DVR Purchase Date</th>
                        <td>{{ $combo->dvr->purchase_date }}</td>
                    </tr>
                    <tr>
                        <th>DVR Installation Date</th>
                        <td>{{ $combo->dvr->installation_date }}</td>
                    </tr>
                    <tr>
                        <th>DVR Warranty Expiration</th>
                        <td>{{ $combo->dvr->warranty_expiration }}</td>
                    </tr>
                </table>

                <div class="section-heading">HDD Details</div>
                <table class="details-table">
                    <!-- HDD Details -->
                    <tr>
                        <th>HDD Model</th>
                        <td>{{ $combo->hdd->model }}</td>
                    </tr>
                    <tr>
                        <th>HDD Serial Number</th>
                        <td>{{ $combo->hdd->serial_number }}</td>
                    </tr>
                    <tr>
                        <th>HDD Capacity (GB)</th>
                        <td>{{ $combo->hdd->capacity }}</td>
                    </tr>
                    <tr>
                        <th>HDD Purchase Date</th>
                        <td>{{ $combo->hdd->purchase_date }}</td>
                    </tr>
                    <tr>
                        <th>HDD Installation Date</th>
                        <td>{{ $combo->hdd->installation_date }}</td>
                    </tr>
                    <tr>
                        <th>HDD Warranty Expiration</th>
                        <td>{{ $combo->hdd->warranty_expiration }}</td>
                    </tr>
                </table>

                <div class="section-heading">Combo Details</div>
                <table class="details-table">
                    <!-- Combo Details -->
                    <tr>
                        <th>Camera Capacity</th>
                        <td>{{ $combo->camera_capacity }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $combo->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $combo->updated_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                </table>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('admin.combos.index') }}" class="tf-button">Back to List</a>
                    <a href="{{ route('admin.combos.edit', $combo) }}" class="tf-button">Edit Combo</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
