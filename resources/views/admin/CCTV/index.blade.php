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
                            <th colspan="3" style="text-align:center; ">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cctvs as $cctv)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $cctv->location->depot->name }}</td>
                                <td>{{ $cctv->location->name }}</td>
                                <td>{{ $cctv->sublocation}}</td>
                                <td>{{ $cctv->model }}</td>
                                <td>{{ ucfirst($cctv->status) }}</td>
                                <td>{{ $cctv->combo->depot->name ?? 'N/A' }} - {{ $cctv->combo->location->name ?? 'N/A' }}</td>
                                <td>{{ $cctv->purchase_date ?? 'N/A' }}</td>
                                <td>{{ $cctv->installation_date ?? 'N/A' }}</td>
                                <td>{{ $cctv->warranty_expiration ?? 'N/A' }}</td>

                                <td colspan="3">
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
                                <td colspan="10" class="text-center">No CCTV Cameras found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
