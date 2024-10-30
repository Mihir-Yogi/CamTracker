    @extends('layouts.admin')

    @section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <h3>Edit Status Report</h3>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('status_reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <fieldset>
                    <div class="body-title">Select Depot <span class="tf-color-1">*</span></div>
                    <select name="depot_id" id="depot_id" required>
                        <option value="">Select a depot</option>
                        @foreach($depots as $depot)
                            <option value="{{ $depot->id }}" {{ $report->depot_id == $depot->id ? 'selected' : '' }}>
                                {{ $depot->name }} ({{ $depot->city }})
                            </option>
                        @endforeach
                    </select>
                </fieldset>

                <fieldset>
                    <div class="body-title">Select Location <span class="tf-color-1">*</span></div>
                    <select name="location_id" id="location_id" required>
                        <option value="">Select a location</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ $report->location_id == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                </fieldset>

                <button type="button" id="searchButton">Search</button>

                <!-- Devices Table -->
                <div id="devicesContainer" class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Model</th>
                                <th>Serial Number</th>
                                <th>Status</th>
                                <th>Off Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($report->nvr)
                            <tr>
                                <td>NVR</td>
                                <td>{{ $report->nvr->model }}</td>
                                <td>{{ $report->nvr->serial_number }}</td>
                                <td>
                                    <select name="nvr_status" class="status-select" >
                                        <option value="ON" {{ $report->nvr_status == 'ON' ? 'selected' : '' }}>ON</option>
                                        <option value="OFF" {{ $report->nvr_status == 'OFF' ? 'selected' : '' }}>OFF</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea name="off_reason[nvr]" class="remarks" >{{ $report->nvr_reason }}</textarea>
                                </td>
                            </tr>
                            @endif

                            @if($report->dvr)
                            <tr>
                                <td>DVR</td>
                                <td>{{ $report->dvr->model }}</td>
                                <td>{{ $report->dvr->serial_number }}</td>
                                <td>
                                    <select name="dvr_status" class="status-select" >
                                        <option value="ON" {{ $report->dvr_status == 'ON' ? 'selected' : '' }}>ON</option>
                                        <option value="OFF" {{ $report->dvr_status == 'OFF' ? 'selected' : '' }}>OFF</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea name="off_reason[dvr]" class="remarks" >{{ $report->dvr_reason }}</textarea>
                                </td>
                            </tr>
                            @endif

                            @if($report->hdd)
                            <tr>
                                <td>HDD</td>
                                <td>{{ $report->hdd->model }}</td>
                                <td>{{ $report->hdd->serial_number }}</td>
                                <td>
                                    <select name="hdd_status" class="status-select" required>
                                        <option value="ON" {{ $report->hdd_status == 'ON' ? 'selected' : '' }}>ON</option>
                                        <option value="OFF" {{ $report->hdd_status == 'OFF' ? 'selected' : '' }}>OFF</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea name="off_reason[hdd]" class="remarks" >{{ $report->hdd_reason }}</textarea>
                                </td>
                            </tr>
                            @endif

                            @if($report->cctvStatuses->isNotEmpty())
                                @foreach($report->cctvStatuses as $cctvStatus)
                                    <tr>
                                        <td>CCTV</td>
                                        <td>{{ $cctvStatus->cctv->model ?? 'N/A' }}</td>
                                        <td>{{ $cctvStatus->cctv->serial_number ?? 'N/A' }}</td>
                                        <td>
                                            <select name="cctv_status[{{$cctvStatus->cctv_id}}]" class="status-select">
                                                <option value="ON" {{ $cctvStatus->status == 'ON' ? 'selected' : '' }}>ON</option>
                                                <option value="OFF" {{ $cctvStatus->status == 'OFF' ? 'selected' : '' }}>OFF</option>
                                            </select>
                                        </td>
                                        <td>
                                            <textarea name="cctv_reason[{{$cctvStatus->cctv_id}}]" class="remarks">{{ $cctvStatus->off_reason }}</textarea>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Image Preview and Upload -->
                <fieldset class="name">
                    <div class="body-title">Replacement Image</div>
                    <div class="image-preview-container">
                        <div class="new-image">
                            <p>Current Image:</p>
                            <img src="{{ asset($report->remark_image) }}" alt="Current Image" style="max-width: 150px; border: 1px solid #ccc; border-radius: 5px;">
                        </div>
                        <div class="new-image">
                            <p>Image Preview:</p>
                            <img id="new-image-preview" src="#" alt="New Image Preview" style="max-width: 150px; display: none; border: 1px solid #ccc; border-radius: 5px;">
                        </div>
                    </div>
                    <input type="file" name="remark_image" accept="image/*" onchange="previewNewImage(event)">
                </fieldset>

                <button type="submit" style="background-color: #ccc; width: 100%;">Update Report</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Load locations based on depot change
            $('#depot_id').change(function() {
                const depotId = $(this).val();
                if (depotId) {
                    $.ajax({
                        url: "{{ url('/admin/locations-by-depot') }}/" + depotId,
                        type: 'GET',
                        success: function(data) {
                            $('#location_id').empty().append('<option value="">Select a location</option>');
                            $.each(data, function(index, location) {
                                $('#location_id').append('<option value="' + location.id + '">' + location.name + '</option>');
                            });
                        }
                    });
                }
            });


            function toggleRemarksField(element) {
                const remarks = element.closest('tr').find('.remarks');
                if (element.val() === 'OFF') {
                    remarks.show();
                } else {
                    remarks.hide().val(''); // Hide and clear the field
                }
            }
        });

        function previewNewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('new-image-preview').src = reader.result;
                document.getElementById('new-image-preview').style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    @endsection
