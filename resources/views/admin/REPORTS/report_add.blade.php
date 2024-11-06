@extends('layouts.admin')

@section('content')

<style>
    /* Stronger border styling for inputs, selects, and textareas */
select, input[type="file"], textarea {
    border: 1px solid #333;
    border-radius: 4px;
    padding: 8px;
    font-size: 16px;
    width: 100%;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

/* Hover and focus effect for inputs and selects */
select:focus, input[type="file"]:focus, textarea:focus {
    border-color: #007bff;
    outline: none;
}

/* Style for the file input preview container */
.image-preview-container img {
    border: 1px solid #333;
    padding: 5px;
    border-radius: 4px;
    max-width: 100%;
}

/* Center-aligned submit button with enhanced styling */
button[type="submit"]{
    color: #fff;
    font-size: 16px;
    padding: 12px;
    border: 1px solid #04AA6D;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

/* Hover effect for buttons */
button[type="submit"]:hover{
    background-color: #038a5d;
    border-color: #038a5d;
}

/* Responsive adjustments */
@media (max-width: 600px) {
    .table-responsive th, .table-responsive td {
        font-size: 14px;
        padding: 8px;
    }
    select, input[type="file"], textarea, button[type="submit"]{
        font-size: 14px;
    }
}

</style>

<div class="main-content-inner">
    <div class="main-content-wrap">
        <h3>Add Transaction</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('status_reports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <fieldset>
                <div class="body-title">Select Depot <span class="tf-color-1">*</span></div>
                <div class="select flex-grow" style="width: 500px;">
                    <select name="depot_id" id="depot_id"  required>
                        <option value="">Select a depot</option>
                        @foreach($depots as $depot)
                            <option value="{{ $depot->id }}" @if(old('depot_id') == $depot->id) selected @endif>{{ $depot->name }} </option>
                        @endforeach
                    </select>
                </div>
            </fieldset>
            @error('depot_id')
                <span class="alert alert-danger">{{ $message }}</span>
            @enderror

            <fieldset>
                <div class="body-title">Select Location <span class="tf-color-1">*</span></div>
                <div class="select flex-grow" style="width: 500px;">
                    <select name="location_id" id="location_id" required>
                        <option value="">Select a location</option>
                    </select>
                </div>
            </fieldset>
            @error('location_id')
                <span class="alert alert-danger">{{ $message }}</span>
            @enderror

            <div id="devicesContainer" class="table-responsive">
            </div>
            <fieldset>
            <!-- Replace Image Field with Preview -->
            <fieldset class="name">
                    <div class="body-title">Replacement Image</div>
                    <div class="image-preview-container" style="display: flex; gap: 20px; align-items: flex-start;">
                        <!-- New Image Preview -->
                        <div class="new-image">
                            <p> Image Preview:</p>
                            <img id="new-image-preview" src="#" alt="Image Preview" style="max-width: 500px; height: auto; display: none; border: 1px solid #ccc; padding: 5px; border-radius: 5px;margin-top: 20px; ">
                        </div>
                    </div>

                    <!-- Image Input Field -->
                    <input type="file" name="remark_image" accept="image/*" style="width: 500px;" onchange="previewNewImage(event)">
                </fieldset>
                @error('remark_image')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

            <button style="background-color: #04AA6D; top: 20px; width: 500px;  "  type="submit">Submit</button>
        </form>
    </div>
</div>
<style>
    /* Custom responsive styling */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* For smoother scrolling on iOS */
}
.table-responsive table {
    width: 100%;
    border-collapse: collapse;
}

.table-responsive th, .table-responsive td {
    white-space: nowrap; /* Prevents content from wrapping */
}

/* Optional: Improve usability on smaller screens */
@media (max-width: 600px) {
    .table-responsive th, .table-responsive td {
        font-size: 14px; /* Adjust font size */
        padding: 8px; /* Adjust padding for smaller screens */
    }
}

</style>
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
            $('#devicesContainer').empty(); // Clear devices when depot is not selected
        }
    });
});

$('#location_id').change(function() {
        searchDevices();
    });
    function searchDevices() {
        let depotId = $('#depot_id').val();
        let locationId = $('#location_id').val();

        if (depotId && locationId) {
            $('#devicesContainer').html('<p>Loading devices...</p>');
            $.ajax({
                url: "{{ route('status_report.devices') }}",
                type: 'POST',
                data: {
                    depot_id: depotId,
                    location_id: locationId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    let devicesHTML = `<div class="table-responsive"><table class="table table-striped table-bordered"><thead><tr><th>Type</th><th>Model</th><th>Serial Number</th><th>Status</th><th>Off Reason</th></tr></thead><tbody>`;

                    // Generate rows for NVRs, DVRs, HDDs, and CCTVs
                    data.nvrs.forEach(nvr => {
                        devicesHTML += `
                            <tr>
                                <td>NVR</td>
                                <td>${nvr.model}</td>
                                <td>${nvr.serial_number}</td>
                                <td>
                                    <select name="nvr_status" class="status-select" required>
                                        <option value="ON" ${nvr.status === 'ON' ? 'selected' : ''}>ON</option>
                                        <option value="OFF" ${nvr.status === 'OFF' ? 'selected' : ''}>OFF</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea name="off_reason[nvr_${nvr.id}]" class="remarks" placeholder="Reason for OFF" style="${nvr.status === 'OFF' ? 'display:block;' : 'display:none;'}">${nvr.reason}</textarea>
                                </td>
                            </tr>`;
                            devicesHTML += `<input type="hidden" name="nvr_id" value="${nvr.id}">`;
                    });
                    
                    
                    // Process DVRs
                    data.dvrs.forEach(dvr => {
                        devicesHTML += `
                            <tr>
                                <td>DVR</td>
                                <td>${dvr.model}</td>
                                <td>${dvr.serial_number}</td>
                                <td>
                                    <select name="dvr_status" class="status-select" required>
                                        <option value="ON" ${dvr.status === 'ON' ? 'selected' : ''}>ON</option>
                                        <option value="OFF" ${dvr.status === 'OFF' ? 'selected' : ''}>OFF</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea name="off_reason[dvr_${dvr.id}]" class="remarks" placeholder="Reason for OFF" style="${dvr.status === 'OFF' ? 'display:block;' : 'display:none;'}">${dvr.reason}</textarea>
                                </td>
                            </tr>`;
                            devicesHTML += `<input type="hidden" name="dvr_id" value="${dvr.id}">`;
                    });

                    // Process HDDs
                    data.hdds.forEach(hdd => {
                        devicesHTML += `
                            <tr>
                                <td>HDD</td>
                                <td>${hdd.model}</td>
                                <td>${hdd.serial_number}</td>
                                <td>
                                    <select name="hdd_status" class="status-select" required>
                                        <option value="ON" ${hdd.status === 'ON' ? 'selected' : ''}>ON</option>
                                        <option value="OFF" ${hdd.status === 'OFF' ? 'selected' : ''}>OFF</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea name="off_reason[hdd_${hdd.id}]" class="remarks" placeholder="Reason for OFF" style="${hdd.status === 'OFF' ? 'display:block;' : 'display:none;'}">${hdd.reason}</textarea>
                                </td>
                            </tr>`;
                            devicesHTML += `<input type="hidden" name="hdd_id" value="${hdd.id}">`;

                    });

                    // Process CCTVs
                    data.cctvs.forEach(cctv => {
                        devicesHTML += `
                            <tr>
                                <td>CCTV</td>
                                <td>${cctv.model}</td>
                                <td>${cctv.serial_number}</td>
                                <td>
                                    <select name="cctv_status[${cctv.id}]" class="status-select" required>
                                        <option value="ON" ${cctv.status === 'ON' ? 'selected' : ''}>ON</option>
                                        <option value="OFF" ${cctv.status === 'OFF' ? 'selected' : ''}>OFF</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea name="cctv_reason[${cctv.id}]" class="remarks" placeholder="Reason for OFF" style="${cctv.status === 'OFF' ? 'display:block;' : 'display:none;'}">${cctv.reason}</textarea>
                                </td>
                            </tr>`;
                    });

                    devicesHTML += '</tbody></table>';
                    $('#devicesContainer').html(devicesHTML);

                    // Show or hide remarks based on selected status
                    $('.status-select').change(function() {
                        const selectedValue = $(this).val();
                        const remarksField = $(this).closest('tr').find('.remarks');
                        if (selectedValue === 'OFF') {
                            remarksField.show();
                        } else {
                            remarksField.hide().val(''); // Hide and clear the field
                        }
                    });
                },
                error: function(xhr) {
                    $('#devicesContainer').html('<p>An error occurred while loading devices. Please try again.</p>');
                    console.error(xhr.responseText);
                }
            });
        } else {
            $('#devicesContainer').empty(); // Clear devices if no depot or location is selected
        }
    }
    
function previewNewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('new-image-preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
