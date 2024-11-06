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
            <div class="flex items-center justify-between gap10 flex-wrap">
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
                <a class="tf-button style-1 w208" href="{{ route('admin.combos.create') }}">
                    <i class="icon-plus"></i>Add New Combo
                </a>
            </div>

            <!-- Session Status Message -->
            @if(Session::has('status'))
                <p class="alert alert-success">{{ Session::get('status') }}</p>
            @endif

            <!-- Combos Table -->
            <div class="wg-table table-all-user">
                <div class="table-responsive">
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
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ optional($combo->location->depot)->name ?? 'N/A' }}</td>
                                    <td>{{ optional($combo->location)->name ?? 'N/A' }}</td>
                                    <td>{{ optional($combo->nvr)->model ?? 'N/A' }}</td>
                                    <td>{{ optional($combo->dvr)->model ?? 'N/A' }}</td>
                                    <td>{{ optional($combo->hdd)->model ?? 'N/A' }}</td>
                                    <td>{{ $combo->camera_capacity }}</td>
                                    <td>{{ $combo->current_cctv_count }}</td>
                                    <td colspan="2">
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
                                    <td colspan="9" class="text-center">No combos found.</td>
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
                if (result) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
