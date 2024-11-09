@extends('layouts.admin')

@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Header and Add Button -->
        <h3>NVR List</h3>
        <!-- NVR Table -->
        <div class="wg-box">
        <div>
        <form action="#" method="GET">
                <fieldset style="display: flex; gap: 15px;">
                    <div class="block">
                        <div class="body-title">Select Depot</div>
                        <div class="select flex-grow">
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
                    <div class="block">
                        <div class="body-title">Select Location</div>
                        <div class="select flex-grow">
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
                </fieldset>
                <button type="submit" style="width: 120px; height: 40px; margin-top: 20px;" class="tf-button style-1 ">Filter</button>
            </form>
        </div>


            <div class="table-responsive">
                @if(Session::has('status'))
                    <p class="alert alert-success">{{ Session::get('status') }}</p>
                @endif

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="width: 80px;">Depot</th>
                            <th>Location</th>
                            <th>Sub-Location</th>
                            <th>Model</th>
                            <th>Serial Number</th>
                            <th>Status</th>
                            <th>Purchase Date</th>
                            <th>Installation Date</th>
                            <th>Warranty Expiration</th>
                            <th colspan="2" style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nvrs as $nvr)
                            <tr>
                                <td class="td-space" style="text-align: center;">{{ $loop->iteration }}</td>
                                <td class="td-space" style="width: 80px;">{{ $nvr->location->depot->name }}</td>
                                <td class="td-space">{{ $nvr->location->name }}</td>
                                <td class="td-space">{{ optional($nvr->sublocation)->name }}</td>
                                <td class="td-space">{{ $nvr->model }}</td>
                                <td class="td-space">{{ $nvr->serial_number }}</td>
                                <td class="td-space">{{ ucfirst($nvr->status) }}</td>
                                <td class="td-space">{{ $nvr->purchase_date ? $nvr->purchase_date : 'N/A' }}</td>
                                <td class="td-space">{{ $nvr->installation_date ? $nvr->installation_date : 'N/A' }}</td>
                                <td class="td-space">{{ $nvr->warranty_expiration ? $nvr->warranty_expiration : 'N/A' }}</td>
                                <td class="td-space" colspan="2">
                                    <div class="list-icon-function" style="display: flex; justify-content: center; align-items: center; gap: 15px;">
                                        <a href="{{ route('admin.nvrs.show', $nvr->id) }}" style="margin-left: 15px;">
                                            <div class="list-icon-function view-icon">
                                                <div class="item eye">
                                                    <i class="icon-eye"></i>
                                                </div>
                                            </div>
                                        </a>

                                        <!-- Only show the edit and replace buttons if status is 'working' -->
                                        @if($nvr->status === 'working')
                                            <a href="{{ route('admin.nvrs.edit', $nvr->id) }}" style="margin-left: 15px;">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <a href="{{ route('admin.nvrs.replaceForm', $nvr) }}" style="margin-left: 15px;">
                                                <div class="item edit">
                                                    <i class="fa-solid fa-repeat"></i>
                                                </div>
                                            </a>
                                        @endif
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="td-space" colspan="8" class="text-center">No NVRs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Pagination Links -->
        <div class="pagination" style="font-size: 15px;">
            {{ $nvrs->links('vendor.pagination.bootstrap-5') }} <!-- Use Bootstrap 4 pagination -->
        </div>
    </div>
</div>


<style>
    .block{
        display: inline-block;
        width: 50%;
    }
.td-space{
    padding: 0 !important;
}
.table {
    width: 100%;
    font-size: 0.85em; /* Smaller font size for a professional, compact look */
    table-layout: auto;
    border-collapse: collapse; /* Remove double borders */
    white-space: nowrap; /* Prevent text from wrapping */
}

.td-space {
    padding: 6px 10px; /* Adds padding to make cells more readable without taking too much space */
    font-size: 12px !important;
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
    #filter{
        flex-direction: column;
    }
}

</style>
@endsection

@push('scripts')
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
    
});
</script>

@endpush
