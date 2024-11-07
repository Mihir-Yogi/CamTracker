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

    .dropdown-container {
    display: flex;
    gap: 20px; /* Adjust spacing between the dropdowns */
    align-items: center; /* Center vertically, if needed */
}

.dropdown-item {
    flex: 1; /* Makes each dropdown take up equal width */
    min-width: 500px; /* Adjust minimum width for each dropdown */
}

.body-title {
    margin-bottom: 8px; /* Space between title and select box */
}

    
</style>

<div class="main-content-inner">
    <div class="main-content-wrap">
        <h3>Transactions</h3>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
<div class="wg-box">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <!-- Filter Form -->
            <form action="#" method="GET">
            <fieldset>
    <div class="dropdown-container">
        <!-- Select Depot Section -->
        <div class="dropdown-item">
            <div class="body-title">Select Depot</div>
            <div class="select flex-grow" style="width: 100%;">
                <select name="depot_id" id="depot_id">
                    <option value="">Select a depot</option>
                    @foreach($depots as $depot)
                        <option value="{{ $depot->id }}" @if($depotId == $depot->id) selected @endif>
                            {{ $depot->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Select Location Section -->
        <div class="dropdown-item">
            <div class="body-title">Select Location</div>
            <div class="select flex-grow" style="width: 100%;">
                <select name="location_id" id="location_id">
                    <option value="">Select a location</option>
                    @if ($depotId)
                        @foreach ($depots->find($depotId)->locations as $location)
                            <option value="{{ $location->id }}" @if($locationId == $location->id) selected @endif>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        
    </div>
    <div class="dropdown-container">
        <div class="dropdown-item">
            <div class="body-title">Start Date</div>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Start Date">
        </div>
        <div class="dropdown-item">
            <div class="body-title">Start Date</div>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="End Date">
        </div>
    </div>
</fieldset>

                <button type="submit" style="width: 120px; height: 40px; margin-top: 20px;" class="tf-button style-1 ">Filter</button>
            </form>
            <a href="{{ route('status_reports.create') }}" class="tf-button style-1 w208">Create New Transcation</a>
        </div>

        <!-- Status Reports Table -->
        <div class="wg-table table-all-user">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="width: 120px;">Depot</th>
                            <th>Location</th>
                            <th>Device Type</th>
                            <th>Model</th>
                            <th>Serial Number</th>
                            <th>Status</th>
                            <th>Remark</th>
                            <th>Purchase Date</th>
                            <th>Instralled Date</th>
                            <th>Expiry Date</th>
                            <th>Created At</th>
                            <th colspan="3" style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $index => $report)
                            @php
                                $rowCount = 1 + ($report->cctvStatuses->count() ?: 0); // Include NVR and HDD rows
                                $rowSpan = $rowCount > 16 ?: $rowCount + 1 ; // Set rowspan to rowCount if > 4, otherwise 1
                            @endphp

                            <!-- NVR Row -->
                            <tr>
                                <td rowspan="{{ $rowSpan }}">{{ $index + 1 }}</td>
                                <td rowspan="{{ $rowSpan }}">{{ optional($report->depot)->name }}</td>
                                <td rowspan="{{ $rowSpan }}">{{ optional($report->location)->name }}</td>
                                @if ($report->nvr)
                                <td>NVR</td>
                                <td>{{ $report->nvr->model ?? 'N/A' }}</td>
                                <td>{{ $report->nvr->serial_number ?? 'N/A' }}</td>
                                <td>{{ $report->nvr_status ?? 'N/A' }}</td>
                                <td>{{ $report->nvr_reason ?? 'N/A' }}</td>
                                <td>{{ $report->nvr->purchase_date ?? 'N/A' }}</td>
                                <td>{{ $report->nvr->installation_date ?? 'N/A' }}</td>
                                <td>{{ $report->nvr->warranty_expiration ?? 'N/A' }}</td>
                                @endif
                                @if ($report->dvr)
                                <td>DVR</td>
                                <td>{{ $report->dvr->model ?? 'N/A' }}</td>
                                <td>{{ $report->dvr->serial_number ?? 'N/A' }}</td>
                                <td>{{ $report->dvr_status ?? 'N/A' }}</td>                   
                                <td>{{ $report->dvr_reason ?? 'N/A' }}</td>
                                <td>{{ $report->dvr->purchase_date ?? 'N/A' }}</td>
                                <td>{{ $report->dvr->installation_date ?? 'N/A' }}</td>
                                <td>{{ $report->dvr->warranty_expiration ?? 'N/A' }}</td>
                                @endif
                                <td  rowspan="{{ $rowSpan }}">{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                                <td  rowspan="{{ $rowSpan }}" style="text-align: center;">
                                    <a href="{{ route('status_reports.show', ['id' => $report->id]) }}" class="item edit">
                                        <i class="icon-eye"></i> View
                                    </a>
                                </td>
                                <td  rowspan="{{ $rowSpan }}" style="text-align: center;">
                                    <a href="{{ route('status_reports.edit', $report->id) }}" class="item edit">
                                        <i class="icon-edit-3"></i> Edit
                                    </a>
                                </td>
                                <td  rowspan="{{ $rowSpan }}" style="text-align: center;">
                                    <form action="#" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="item text-danger delete">
                                            <i class="icon-trash-2"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <!-- HDD Row -->
                            <tr>
                                <td>HDD</td>
                                <td>{{ $report->hdd->model ?? 'N/A' }}</td>
                                <td>{{ $report->hdd->serial_number ?? 'N/A' }}</td>
                                <td>{{ $report->hdd_status ?? 'N/A' }}</td>
                                <td>{{ $report->hdd_reason ?? 'N/A' }}</td>
                                <td>{{ $report->hdd->purchase_date ?? 'N/A' }}</td>
                                <td>{{ $report->hdd->installation_date ?? 'N/A' }}</td>
                                <td>{{ $report->hdd->warranty_expiration ?? 'N/A' }}</td>
                            </tr>

                            <!-- CCTV Row -->
                        @if($report->cctvStatuses->isNotEmpty())
                            @foreach($report->cctvStatuses as $cctvStatus)
                            <tr>
                                <td>CCTV</td>
                                <td>{{ $cctvStatus->cctv->model ?? 'N/A' }}</td>
                                <td>{{ $cctvStatus->cctv->serial_number ?? 'N/A' }}</td>
                                <td>{{ $cctvStatus->status  ?? 'N/A' }}</td>
                                <td>{{ $cctvStatus->off_reason  ?? 'N/A' }}</td>
                                <td>{{ $cctvStatus->cctv->purchase_date ?? 'N/A' }}</td>
                                <td>{{ $cctvStatus->cctv->installation_date ?? 'N/A' }}</td>
                                <td>{{ $cctvStatus->cctv->warranty_expiration ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        @endif

                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td colspan="2"> ON Camera</td>
                            <td>{{ $report->cctv_on_count?? 'N/A' }}</td>
                            <td colspan="2"> OFF Camera</td>
                            <td>{{ $report->cctv_off_count?? 'N/A' }}</td>
                            <td >Total</td>
                            <td>{{ ($report->cctv_off_count)+($report->cctv_on_count)?? 'N/A' }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No reports found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Pagination Links -->
        <div class="pagination" style="font-size: 15px;">
            {{ $reports->links('vendor.pagination.bootstrap-5') }} <!-- Use Bootstrap 4 pagination -->
        </div>
    </div>
    </div>
</div>

<!-- Script for Delete Confirmation -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(document).ready(function() {
        $('#depot_id').change(function() {
            var selectedDepotId = $(this).val();
            if (selectedDepotId) {
                $.ajax({
                    url: "{{ url('/admin/locations-by-depot') }}/" + selectedDepotId,
                    type: 'GET',
                    success: function(data) {
                        $('#location_id').empty().append('<option value="">Select a location</option>');
                        $.each(data, function(index, location) {
                            $('#location_id').append('<option value="' + location.id + '">' + location.name + '</option>');
                        });
                    },
                    error: function() {
                        alert('Failed to fetch locations. Please try again.');
                    }
                });
            } else {
                $('#location_id').empty().append('<option value="">Select a location</option>');
            }
        });

        // Delete button confirmation
        $('.delete-button').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this report!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
