@extends('layouts.admin')

@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Page Header and Breadcrumbs -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Combos</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <div class="text-tiny">Combos</div>
                </li>
            </ul>
        </div>

        <!-- Search and Add Button -->
        <div class="wg-box">
            <div>
            <form action="#" method="GET">
                <fieldset style="display: flex; gap: 15px;">
                    <div class="block">
                        <div class="body-title">Select Depot</div>
                        <div class="select flex-grow" >
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
                <div style="display: flex; gap: 20px; align-items: center; margin-top: 15px;">
                        <button type="submit" style="width: 120px; height: 40px; " class="tf-button style-1">Filter</button>
                        <a class="tf-button style-1 w208" style="width: 160px; height: 40px; font-size: 12px;" href="{{ route('admin.combos.create') }}">
                            Create New Combo
                        </a>
                </div>

            </form>
            </div>

            <!-- Session Status Message -->
            @if(Session::has('status'))
                <p class="alert alert-success">{{ Session::get('status') }}</p>
            @endif

            <!-- Combos Table -->
                <div class="table-responsive" >
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Depot</th>
                                <th>Location</th>
                                <th>NVR Model</th>
                                <th>DVR Model</th>
                                <th>HDD Model</th>
                                <th>Camera Capacity</th>
                                <th>Current CCTV Count</th>
                                <th colspan="2"  style="text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($combos as $index => $combo)
                                <tr>
                                    <td class="td-space" style="text-align: center;" >{{ $index + 1 }}</td>
                                    <td class="td-space">{{ optional($combo->location->depot)->name ?? 'N/A' }}</td>
                                    <td class="td-space">{{ optional($combo->location)->name ?? 'N/A' }}</td>
                                    <td class="td-space">{{ optional($combo->nvr)->model ?? 'N/A' }}</td>
                                    <td class="td-space">{{ optional($combo->dvr)->model ?? 'N/A' }}</td>
                                    <td class="td-space">{{ optional($combo->hdd)->model ?? 'N/A' }}</td>
                                    <td class="td-space">{{ $combo->camera_capacity }}</td>
                                    <td class="td-space">{{ $combo->current_cctv_count }}</td>
                                    <td class="td-space" colspan="2">
                                        <div class="list-icon-function" style="display: flex; justify-content: center; align-items: center; gap: 15px;">
                                            <a href="{{ route('admin.combos.show', ['id' => $combo->id]) }}" style="margin-left: 15px;">
                                                <div class="list-icon-function view-icon">
                                                    <div class="item eye">
                                                        <i class="icon-eye"></i>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="{{ route('admin.combos.edit', ['id' => $combo->id]) }}" style="margin-left: 15px;">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <form action="{{route('admin.combos.destroy',['id' => $combo->id])}}" method="POST" style="display:inline;" >
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
                                    <td class="td-space" colspan="9" class="text-center">No combos found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $combos->links('pagination::bootstrap-5') }}
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

    #filter {
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
                if (result) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
