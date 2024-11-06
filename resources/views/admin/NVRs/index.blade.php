@extends('layouts.admin')

@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Header and Add Button -->
        <h3>NVR List</h3>
        <!-- NVR Table -->
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
                            <td>Depot</td>
                            <td>Location</td>
                            <td>Sub-Location</td>
                            <th>Model</th>
                            <th>Serial Number</th>
                            <th>Status</th>
                            <th>Purchase Date</th>
                            <th>Installation Date</th>
                            <th>Warranty Expiration</th>
                            <th colspan="3" style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nvrs as $nvr)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $nvr->location->depot->name }}</td>
                                <td>{{ $nvr->location->name }}</td>
                                <td>{{ optional($nvr->sublocation)->name }}</td>
                                <td>{{ $nvr->model }}</td>
                                <td>{{ $nvr->serial_number }}</td>
                                <td>{{ ucfirst($nvr->status) }}</td>
                                <td>{{ $nvr->purchase_date ? $nvr->purchase_date : 'N/A' }}</td>
                                <td>{{ $nvr->installation_date ? $nvr->installation_date : 'N/A' }}</td>
                                <td>{{ $nvr->warranty_expiration ? $nvr->warranty_expiration : 'N/A' }}</td>
                                <td colspan="3">
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
                                <td colspan="8" class="text-center">No NVRs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Pagination Links -->
        <div class="pagination">
            {{ $nvrs->links('vendor.pagination.bootstrap-5') }} <!-- Use Bootstrap 4 pagination -->
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
    
});
</script>

@endpush
