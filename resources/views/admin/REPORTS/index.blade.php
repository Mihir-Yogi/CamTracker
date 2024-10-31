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
</style>

<div class="main-content-inner">
    <div class="main-content-wrap">
        <h3>Status Reports</h3>

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
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <!-- Filter Form -->
        <form action="#" method="GET">
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
            <button type="submit" class="tf-button style-1 w208">Filter</button>
        </form>
        <a href="{{ route('status_reports.create') }}" class="tf-button style-1 w208">Create New Status</a>
        </div>

        <!-- Status Reports Table -->
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
                            <th>Created At</th>
                            <th colspan="3" style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $index => $report)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ optional($report->depot)->name }}</td>
                                <td>{{ optional($report->location)->name }}</td>
                                <td>{{ optional($report->nvr)->model ?? 'N/A'}}</td>
                                <td>{{ optional($report->dvr)->model ?? 'N/A'}}</td>
                                <td>{{ optional($report->hdd)->model ?? 'N/A'}}</td>
                                <td>{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                                <td style="text-align: center;">
                                    <a href="{{ route('status_reports.show', ['id' => $report->id]) }}" class="item edit">
                                        <div class="list-icon-function view-icon">
                                            <div class="item eye">
                                                <i class="icon-eye"></i>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ route('status_reports.edit', $report->id) }}" class="item edit">
                                        <i class="icon-edit-3"></i> Edit
                                    </a>
                                </td>
                                <td style="text-align: center;">
                                    <form action="#" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="item text-danger delete">
                                            <i class="icon-trash-2"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center">No reports found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                text: "Once deleted, this record cannot be recovered!",
                icon: "warning",
                buttons: ["Cancel", "Delete"],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
