@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <h3>Status Reports</h3>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('status_reports.index') }}" method="GET">
            <fieldset>
                <div class="body-title">Select Depot</div>
                <div class="select flex-grow">
                    <select name="depot_id" id="depot_id">
                        <option value="">Select a depot</option>
                        @foreach($depots as $depot)
                            <option value="{{ $depot->id }}" @if($depotId == $depot->id) selected @endif>
                                {{ $depot->name }} ({{ $depot->city }})
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

            <button type="submit">Filter</button>
        </form>

        <div class="wg-table table-all-user">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Depot</th>
                            <th>Location</th>
                            <th>NVR</th>
                            <th>DVR</th>
                            <th>HDD</th>
                            <th>CCTV</th>
                            <th>Status</th>
                            <th>Off Reason</th>
                            <th>Comments</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $index => $report)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ optional($report->depot)->name }}</td>
                                <td>{{ optional($report->location)->name }}</td>
                                <td>{{ optional($report->nvr)->model }}</td>
                                <td>{{ optional($report->dvr)->model }}</td>
                                <td>{{ optional($report->hdd)->model }}</td>
                                <td>{{ optional($report->cctv)->model }}</td>
                                <td>{{ $report->status }}</td>
                                <td>{{ $report->off_reason }}</td>
                                <td>{{ $report->comments }}</td>
                                <td>{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
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
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#depot_id').change(function() {
        var selectedDepotId = $(this).val();

        // Fetch locations based on selected depot
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

@endsection
