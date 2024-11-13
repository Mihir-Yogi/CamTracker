
@extends('layouts.admin')

@section('content')
<style>
    /* General container styling */
    .details-view {
        display: flex;
        flex-direction: column;
        gap: 15px;
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

.dropdown-item:hover{
    background-color: transparent;
}

.body-title {
    margin-bottom: 8px; /* Space between title and select box */
}
.table {
    width: 100%;
    font-size: 0.85em; /* Smaller font size for a professional, compact look */
    table-layout: auto;
    border-collapse: collapse; /* Remove double borders */
    white-space: wrap; /* Prevent text from wrapping */
}

/* Depot column specifically sized to fit content */
th:nth-child(2), td:nth-child(2) {
    width: 50px !important;
    white-space: nowrap; /* Prevents wrapping in the Depot column */
    width: 1%; /* Forces the column to be as narrow as its content */
}

th {
    background-color: #f8f9fa; /* Light grey background for headers */
    font-weight: bold;
    text-align: center;
    padding: 10px;
    border-bottom: 2px solid #dee2e6; /* Subtle border for header separation */
}

tr:nth-child(even) {
    background-color: #f9f9f9; /* Light background for alternate rows */
}

tr:hover {
    background-color: #e9ecef; /* Highlight row on hover for better readability */
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Small screen adjustments */
@media (max-width: 768px) {
    .table {
        font-size: 0.75em; /* Slightly smaller font for small screens */
    }
    .block {
        width: 100%;
    }
    .body-title {
        font-size: 0.9em;
    }
    .td-space {
        font-size: 0.8em;
    }
}
.td-space{
    padding: 0 !important;
    font-size: 12px !important;
    width: 500px;
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
<div class="wg-box" style="gap: 2px;">
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
<div style="display: flex; gap: 20px; align-items: center; margin-top: 15px;">
                        <button type="submit" style="width: 120px; height: 40px; " id="filter" class="tf-button style-1">Filter</button>
                        <button type="button" id="clearFilterBtn" style="width: 120px; height: 40px;" class="tf-button style-1">Clear Filter</button>
                        <a class="tf-button style-1 w208" style="width: 200px; height: 40px; font-size: 12px;" href="{{ route('status_reports.create') }}">
                            Create New Transcation
                        </a>
                        <a href="{{ url('pdf_generator') }}?depot_id={{ request('depot_id') }}&location_id={{ request('location_id') }}&start_date={{ request('start_date') }}&end_date={{ request('end_date') }}"
   class="tf-button style-1 w208" style="width: 200px; height: 40px; font-size: 12px;"> PDF Download </a>
                </div>
            </form>
        </div>

        <!-- Status Reports Table -->
        <div class="wg-table table-all-user">
            <div class="table-responsive">
                <table class="table table-striped table-bordered ">
                    <thead>
                        <tr>
                            <th class="th-space" style="width: 40px !important;">#</th>
                            <th class="th-space">Depot</th>
                            <th class="th-space" >Location</th>
                            <th class="th-space" >Device Type</th>
                            <th class="th-space" >Sub-Location</th>
                            <th class="th-space" >Status</th>
                            <th class="th-space" >Remark</th>
                            <th class="th-space" >Expiry Date</th>
                            <th class="th-space" >Created At</th>
                            <th class="th-space"  colspan="2" style="text-align: center;">Actions</th>
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
                                <td class="td-space" style="text-align: center;" rowspan="{{ $rowSpan }}">{{ $index + 1 }}</td>
                                <td class="td-space" rowspan="{{ $rowSpan }}">{{ optional($report->depot)->name }}</td>
                                <td class="td-space" rowspan="{{ $rowSpan }}">{{ optional($report->location)->name }}</td>
                                @if ($report->nvr)
                                <td class="td-space">NVR</td>
                                <td class="td-space">{{ $report->nvr->sublocation->name}}</td>
                                <td class="td-space" style="background-color: '{{ $report->nvr_status == 'ON' ? '#d4edda' : ($report->nvr_status == 'OFF' ? '#f8d7da' : 'transparent') }}'">{{ $report->nvr_status ?? 'N/A' }}
                                </td>
                                <td class="td-space">{{ $report->nvr_reason ?? 'N/A' }}</td>
                                <td class="td-space">{{ $report->nvr->warranty_expiration ?? 'N/A' }}</td>
                                @endif
                                @if ($report->dvr)
                                <td class="td-space">DVR</td>
                                <td class="td-space">{{ $report->dvr->sublocation->name}}</td>
                                <td class="td-space" style="background-color: {{ $report->dvr_status == 'ON' ? '#d4edda' : ($report->dvr_status == 'OFF' ? '#f8d7da' : 'transparent') }}">{{ $report->dvr_status ?? 'N/A' }}</td>                   
                                <td class="td-space">{{ $report->dvr_reason ?? 'N/A' }}</td>
                                <td class="td-space">{{ $report->dvr->warranty_expiration ?? 'N/A' }}</td>
                                @endif
                                <td class="td-space"  rowspan="{{ $rowSpan }}">{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                                <td class="td-space" colspan="2"  rowspan="{{ $rowSpan }}" style="text-align: center; width: 100px;">
                                    <a href="{{ route('status_reports.show', ['id' => $report->id]) }}" class="item edit" style="margin-right: 15px;" >
                                        <i class="icon-eye"></i> View
                                    </a>
                                    <a href="{{ route('status_reports.edit', $report->id) }}" class="item edit">
                                        <i class="icon-edit-3"></i> Edit
                                    </a>
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
                                <td class="td-space">HDD</td>
                                <td class="td-space">{{ $report->hdd->sublocation->name}}</td>
                                <td class="td-space" style="background-color: {{ $report->hdd_status == 'ON' ? '#d4edda' : ($report->hdd_status == 'OFF' ? '#f8d7da' : 'transparent') }}">{{ $report->hdd_status ?? 'N/A' }}</td>
                                <td class="td-space">{{ $report->hdd_reason ?? 'N/A' }}</td>
                                <td class="td-space">{{ $report->hdd->warranty_expiration ?? 'N/A' }}</td>
                            </tr>

                            <!-- CCTV Row -->
                        @if($report->cctvStatuses->isNotEmpty())
                            @foreach($report->cctvStatuses as $cctvStatus)
                            <tr>
                                <td class="td-space">CCTV</td>
                                <td class="td-space">{{ $cctvStatus->cctv->sublocation->name ?? 'N/A' }}</td>
                                <td class="td-space" style="background-color: {{ $cctvStatus->status  == 'ON' ? '#d4edda' : ($cctvStatus->status  == 'OFF' ? '#f8d7da' : 'transparent') }}">{{ $cctvStatus->status  ?? 'N/A' }}</td>
                                <td class="td-space">{{ $cctvStatus->off_reason  ?? 'N/A' }}</td>
                                <td class="td-space">{{ $cctvStatus->cctv->warranty_expiration ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        @endif

                        <tr>
                            <td class="td-space" colspan="2"> ON Camera</td>
                            <td class="td-space">{{ $report->cctv_on_count?? 'N/A' }}</td>
                            <td class="td-space" colspan="2"> OFF Camera</td>
                            <td class="td-space">{{ $report->cctv_off_count?? 'N/A' }}</td>
                            <td class="td-space" >Total</td>
                            <td class="td-space">{{ ($report->cctv_off_count)+($report->cctv_on_count)?? 'N/A' }}</td>
                            <td class="td-space"></td>
                            <td class="td-space"></td>
                            <td class="td-space"></td>
                        </tr>
                        @empty
                            <tr>
                                <td class="td-space" colspan="11" class="text-center">No reports found.</td>
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
        $('#clearFilterBtn').on('click', function() {
            // Clear all input fields in the form
            $('form').find('select, input[type="date"]').val('');
            $('#filter').click();
        });

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
