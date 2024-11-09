@extends('layouts.admin')

@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Header and Add Button -->
        <h3>CCTV Camera List</h3>
        <div class="wg-box">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <form action="#" method="GET">
                <fieldset>
                    <div class="body-title">Select Depot</div>
                    <div class="select flex-grow" style="width: 500px;">
                        <select name="depot_id" id="depot_id">
                            <option value="">Select a depot</option>
                            @foreach($depots as $depot)
                                <option value="{{ $depot->id }}" @if($depotId == $depot->id) selected @endif>
                                    {{ $depot->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </fieldset>
                <fieldset>
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
                </fieldset>
                <button type="submit" style="width: 120px; height: 40px; margin-top: 20px;" class="tf-button style-1">Filter</button>
            </form>
            <a href="{{ route('admin.cctvs.create') }}" class="tf-button style-1 w208">Add New CCTV Camera</a>
        </div>

        <!-- CCTV Table -->

            <div class="table-responsive">
                @if(Session::has('status'))
                    <p class="alert alert-success">{{ Session::get('status') }}</p>
                @endif

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="width: 150px;">Depot</th>
                            <th>Location</th>
                            <th>Sub-Location</th>
                            <th>Model</th>
                            <th>Status</th>
                            <th>Combo (Depot - Location)</th>
                            <th>Purchase Date</th>
                            <th>Installation Date</th>
                            <th>Warranty Expiration</th>
                            <th colspan="2" style="text-align:center; ">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cctvs as $cctv)
                            <tr>
                                <td class="td-space" style="text-align: center;">{{ $loop->iteration }}</td>
                                <td class="td-space">{{ $cctv->location->depot->name }}</td>
                                <td class="td-space">{{ $cctv->location->name }}</td>
                                <td class="td-space">{{  $cctv->sublocation->name ?? 'N/A' }}</td>
                                <td class="td-space">{{ $cctv->model }}</td>
                                <td class="td-space">{{ ucfirst($cctv->status) }}</td>
                                <td class="td-space">{{ $cctv->combo->depot->name ?? 'N/A' }} - {{ $cctv->combo->location->name ?? 'N/A' }}</td>
                                <td class="td-space">{{ $cctv->purchase_date ?? 'N/A' }}</td>
                                <td class="td-space">{{ $cctv->installation_date ?? 'N/A' }}</td>
                                <td class="td-space">{{ $cctv->warranty_expiration ?? 'N/A' }}</td>

                                <td class="td-space" colspan="2">
                                    <div class="list-icon-function" style="display: flex; justify-content: center; align-items: center; gap: 15px;">
                                        <!-- View Button -->
                                        <a href="{{ route('admin.cctvs.show', $cctv->id) }}" style="margin-left: 15px;">
                                            <div class="list-icon-function view-icon">
                                                <div class="item eye">
                                                    <i class="icon-eye"></i>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- Only show the edit and replace buttons if status is 'working' -->
                                        @if($cctv->status === 'working')
                                            <a href="{{ route('admin.cctvs.edit', $cctv->id) }}" style="margin-left: 15px;">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <a href="{{ route('admin.cctvs.replaceForm', $cctv) }}" style="margin-left: 15px;">
                                                <div class="item edit">
                                                    <i class="fa-solid fa-repeat"></i>
                                                </div>
                                            </a>
                                        @endif

                                        <!-- Delete Button (Always Available) -->
                                        <form action="{{ route('admin.cctvs.destroy', $cctv->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="item text-danger delete">
                                                <i class="icon-trash-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="td-space" colspan="10" class="text-center">No CCTV Cameras found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .block{
        display: inline-block;
        width: 50%;
    }
.td-space{
    height: 20px !important;
    font-size: 12px !important;
    padding: 0 !important;
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
    $(function(){
        $('.delete').on('click', function(e){
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: ["Cancel", "Yes!"],
                dangerMode: true,
            }).then((result) => {
                if(result) {
                    form.submit();
                }   
            });
        });
    });
});
</script>

@endpush
