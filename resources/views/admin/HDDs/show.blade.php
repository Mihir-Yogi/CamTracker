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
        font-size: 16px; /* Increase font size */
    }

    .details-table th,
    .details-table td {
        padding: 14px 20px; /* Increased padding */
        border: 1px solid #ddd;
    }

    .details-table th {
        background-color: #f5f5f5;
        font-weight: bold;
        color: #333;
        text-align: left;
        font-size: 18px; /* Larger font for headers */
    }

    .details-table td {
        color: #333;
    }

    /* Image Preview */
    .image-preview img {
        border: 1px solid #ccc;
        border-radius: 5px;
        max-width: 150px;
        height: auto;
        padding: 5px;
    }

    /* Action buttons styling */
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .action-buttons .tf-button {
        display: inline-block;
        padding: 12px 24px; /* Increased padding */
        font-size: 16px; /* Larger font for buttons */
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
            <h3>HDD Details</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('admin.hdds.index') }}">
                        <div class="text-tiny">HDDs</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">HDD Details</div></li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="details-view">
                <table class="details-table">
                    <tr>
                        <th>Model</th>
                        <td>{{ $hdd->model }}</td>
                    </tr>
                    <tr>
                        <th>Capaity</th>
                        <td>{{ $hdd->capacity }}</td>
                    </tr>
                    <tr>
                        <th>Serial Number</th>
                        <td>{{ $hdd->serial_number }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ ucfirst($hdd->status) }}</td>
                    </tr>
                    <tr>
                        <th>Failure Reason</th>
                        <td>{{ $hdd->failure_reason ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Depot</th>
                        <td>{{ $hdd->depot->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td>{{ $hdd->location->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Purchase Date</th>
                        <td>{{ $hdd->purchase_date ? $hdd->purchase_date : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Installation Date</th>
                        <td>{{ $hdd->installation_date ? $hdd->installation_date : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Warranty Expiration</th>
                        <td>{{ $hdd->warranty_expiration ? $hdd->warranty_expiration : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Replacement Reason Image</th>
                        <td class="image-preview">
                            @if($hdd->image_replace)
                                <img src="{{ asset($hdd->image_replace) }}" alt="Replacement Reason Image">
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                </table>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('admin.hdds.index') }}" class="tf-button">Back to List</a>
                    <!-- Only display the Edit button if the status is 'working' -->
                    @if($hdd->status === 'working')
                        <a href="{{ route('admin.hdds.edit', $hdd) }}" class="tf-button">Edit hdd</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
