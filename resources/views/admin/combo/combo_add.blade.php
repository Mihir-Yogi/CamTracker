@extends('layouts.admin')

@section('content')
<style>
.modal-backdrop {
    background-color: transparent !important; /* Makes the backdrop transparent */
}
.modal-dialog{
    max-width: 500px;
    font-size: 13px ;
}
.model_button{
    font-size: 13px;
    width: 100px;
}
</style>

<div class="main-content-inner">
    <div class="main-content-wrap">
        <h3>Add NVR, DVR, HDD, and Combo Details</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
            <!-- Session Status Message -->
            @if(Session::has('status'))
                <p class="alert alert-success">{{ Session::get('status') }}</p>
            @endif
        <div class="wg-box">
            <form class="form-style-2" action="{{ route('admin.combos.store') }}" method="POST">
                @csrf
                <div class="fom-new-product form-style-1 tow-column-form">
                <!-- Select Depot Field -->
                <fieldset>
                    <div class="body-title">Select Depot <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="depot_id" id="depot_id" required>
                            <option value="">Select a depot</option>
                            @foreach($depots as $depot)
                                <option value="{{ $depot->id }}" @if(old('depot_id') == $depot->id) selected @endif>{{ $depot->name }} </option>
                            @endforeach
                            <option value="new" style="color: #4CAF50;"><i class="fa-solid fa-xmark" style="color: black;"></i> Add New Depot</option> 
                        </select>
                    </div>
                </fieldset>
                @error('depot_id')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Select Location Field -->
                <fieldset>
                    <div class="body-title">Select Location <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="location_id" id="location_id" required>
                            <option value="">Select a location</option>
                        </select>
                    </div>
                </fieldset>
                @error('location_id')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror
                </div>
                <div class="fom-new-product form-style-1 tow-column-form">
<!-- Selection between NVR and DVR -->
                <fieldset>
                    <div class="body-title">Select Device Type <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="device_type" id="device_type" required>
                            <option value="">Select Device</option>
                            <option value="nvr" @if(old('device_type') == 'nvr') selected @endif>NVR</option>
                            <option value="dvr" @if(old('device_type') == 'dvr') selected @endif>DVR</option>
                        </select>
                    </div>
                </fieldset>
                </div>


<div class="form-block">
    
    <div class="block">
                <!-- HDD Fields -->
                <h4>HDD Details</h4>
                <!-- Select Sublocation Field -->
                
                <fieldset>
                    <div class="body-title">HDD Model <span class="tf-color-1">*</span></div>
                    <input type="text" name="hdd_model" value="{{ old('hdd_model') }}" required>
                    @error('hdd_model')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </fieldset>
                <fieldset>
                    <div class="body-title">HDD Serial Number <span class="tf-color-1">*</span></div>
                    <input type="text" name="hdd_serial_number" value="{{ old('hdd_serial_number') }}" required>
                    @error('hdd_serial_number')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </fieldset>
                <fieldset>
                    <div class="body-title">HDD Capacity (GB) <span class="tf-color-1">*</span></div>
                    <input type="number" name="hdd_capacity" value="{{ old('hdd_capacity') }}" required>
                    @error('hdd_capacity')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </fieldset>

                    
                    <fieldset>
                        <div class="body-title">HDD Installation Date</div>
                        <input type="date" name="hdd_installation_date" value="{{ old('hdd_installation_date') }}">
                        @error('hdd_installation_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </fieldset>
                    

                <!-- Purchase Date Field -->
                <fieldset class="name">
                    <div class="body-title">Purchase Date</div>
                    <input class="flex-grow" type="date" name="hdd_purchase_date" id="hdd_purchase_date" value="{{ old('hdd_purchase_date') }}">
                </fieldset>
                @error('hdd_purchase_date')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Warranty Duration Field -->
                <fieldset class="name">
                    <div class="body-title">Warranty Duration (Years)</div>
                    <select class="flex-grow" name="hdd_warranty_duration" id="hdd_warranty_duration">
                        <option value="1">1 Year</option>
                        <option value="2">2 Years</option>
                        <option value="3">3 Years</option>
                    </select>
                </fieldset>

                <!-- Warranty Expiration Field -->
                <fieldset class="name">
                    <div class="body-title">Warranty Expiration</div>
                    <input class="flex-grow" type="date" name="hdd_warranty_expiration" id="hdd_warranty_expiration" value="{{ old('hdd_warranty_expiration') }}" readonly>
                </fieldset>
                @error('hdd_warranty_expiration')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror
        </div>
        <div class="block device_block">

                
        </div>
                

</div>
                
                <!-- Combo Fields -->
                <h4>Combo Details</h4>
                <div class="fom-new-product form-style-1 tow-column-form">
                <fieldset>
                    
                    <div class="body-title">Camera Capacity <span class="tf-color-1">*</span></div>
                    <input type="number" name="camera_capacity" value="{{ old('camera_capacity') }}" required>
                    @error('camera_capacity')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </fieldset>
                </div>

                <!-- Submit Button -->
                <div class="form-footer">
                    <button class="tf-button w208" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal for Adding New Location -->
<div class="modal fade" id="addLocationModal" tabindex="-1" role="dialog" aria-labelledby="addLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLocationModalLabel">Create New Location</h5>
                <button type="button" class="close_location" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="new_location_name" style="margin-bottom: 10px;">Location Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="new_location_name" id="new_location_name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close_location model_button" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary model_button" id="saveLocationButton">Save Location</button>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal for Adding New Sub-Location -->
<div class="modal fade" id="addSubLocationModal" tabindex="-1" role="dialog" aria-labelledby="addSubLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubLocationModalLabel">Create New Sub-Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="new_sub_location_name" style="margin-bottom: 10px;">Sub-Location Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="new_sub_location_name" id="new_sub_location_name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveSubLocationButton">Save Sub-Location</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Adding New Sub-Location -->
<div class="modal fade" id="dvraddSubLocationModal" tabindex="-1" role="dialog" aria-labelledby="dvraddSubLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dvraddSubLocationModalLabel">Create New Sub-Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="dvrnew_sub_location_name" style="margin-bottom: 10px;">Sub-Location Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="dvrnew_sub_location_name" id="dvrnew_sub_location_name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="dvrsaveSubLocationButton">Save Sub-Location</button>
            </div>
        </div>
    </div>
</div>

                <!-- Modal for Adding New Depot -->
<div class="modal fade" id="addDepotModal" tabindex="-1" role="dialog" aria-labelledby="addDepotModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDepotModalLabel">Create New Depot</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="new_depot_name" style="margin-bottom: 10px;">Depot Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="new_depot_name" id="new_depot_name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close model_button" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary model_button" id="saveDepotButton">Save Depot</button>
            </div>
        </div>
    </div>
</div>

<style>
    .block{
        display: inline-block;
        width: 50%;
    }
    .common-fields {
        margin-bottom: 15px; /* Adjust as needed */
        padding: 10px;
    }

    select, input {
        width: 100%; /* Ensures consistency in field width */
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    $('.editSubLocationButton').on('click', function() {
        var sublocationId = $(this).data('id');
        var sublocationName = $(this).data('name');
        
        $('#edit_sublocation_id').val(sublocationId);
        $('#edit_sub_location_name').val(sublocationName);
        $('#editSubLocationModal').modal('show');
    });
    // Save changes to the sublocation name
    $('#updateSubLocationButton').on('click', function() {
        var sublocationId = $('#edit_sublocation_id').val();
        var updatedName = $('#edit_sub_location_name').val();

        $.ajax({
            url: "#",
            type: 'PUT',
            data: {
                id: sublocationId,
                name: updatedName,
                _token: '{{ csrf_token() }}' // Include CSRF token
            },
            success: function(data) {
                $('#editSubLocationModal').modal('hide');
                location.reload(); // Refresh page to show updated data
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                alert('Error: ' + errors.name[0]);
            }
        });
    });
    // Toggle NVR/DVR fields based on selection
    $('#device_type').change(function() {
            var deviceType = $(this).val();
            
            // Clear the container div before adding new content
            $('.device_block').empty();

            if (deviceType === 'nvr') {
                // Dynamically create and append NVR fields
                var nvrFields = `
                    <!-- NVR Fields -->
                <div id="nvr_fields" ">
                    <h4>NVR Details</h4>
                    <fieldset >
                    <div class="body-title">Select Sub-Location <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="sublocation_id" id="sublocation_id" >
                            <option value="">Select a Sub-Location</option>
                            @foreach($sublocations as $sublocation)
                                <option value="{{ $sublocation->id }}">{{ $sublocation->name }}</option>
                            @endforeach
                            <option value="new_nvr" style="color: #4CAF50;">Add New Sub-Location</option>
                        </select>
                    </div>
                </fieldset>
                @error('sublocation_id')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror
                    <fieldset>
                        <div class="body-title">NVR Model <span class="tf-color-1">*</span></div>
                        <input type="text" name="nvr_model" value="{{ old('nvr_model') }}">
                        @error('nvr_model')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </fieldset>
                    <fieldset>
                        <div class="body-title">NVR Serial Number <span class="tf-color-1">*</span></div>
                        <input type="text" name="nvr_serial_number" value="{{ old('nvr_serial_number') }}">
                        @error('nvr_serial_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </fieldset>

                    <fieldset>
                        <div class="body-title">NVR Installation Date</div>
                        <input type="date" name="nvr_installation_date" value="{{ old('nvr_installation_date') }}">
                        @error('nvr_installation_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </fieldset>
                    <!-- Purchase Date Field -->
                <fieldset class="name">
                    <div class="body-title">Purchase Date</div>
                    <input class="flex-grow" type="date" name="nvr_purchase_date" id="nvr_purchase_date" value="{{ old('nvr_purchase_date') }}">
                </fieldset>
                @error('nvr_purchase_date')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Warranty Duration Field -->
                <fieldset class="name">
                    <div class="body-title">Warranty Duration (Years)</div>
                    <select class="flex-grow" name="nvr_warranty_duration" id="nvr_warranty_duration">
                        <option value="1">1 Year</option>
                        <option value="2">2 Years</option>
                        <option value="3">3 Years</option>
                    </select>
                </fieldset>

                <!-- Warranty Expiration Field -->
                <fieldset class="name">
                    <div class="body-title">Warranty Expiration</div>
                    <input class="flex-grow" type="date" name="nvr_warranty_expiration" id="nvr_warranty_expiration" value="{{ old('nvr_warranty_expiration') }}" readonly>
                </fieldset>
                @error('nvr_warranty_expiration')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror
                    
                </div>
                `;
                $('.device_block').append(nvrFields);
            } else if (deviceType === 'dvr') {
                // Dynamically create and append DVR fields
                var dvrFields = `
                    <!-- DVR Fields -->
                <div id="dvr_fields" >
                    <h4>DVR Details</h4>
                    <!-- Sub-Location Field -->
                    <fieldset>
                        <div class="body-title">Select Sub-Location <span class="tf-color-1">*</span></div>
                        <div class="select flex-grow">
                            <select name="dvrsublocation_id" id="dvr_sublocation">
                                <option value="">Select a Sub-Location</option>
                                @foreach($sublocations as $sublocation)
                                    <option value="{{ $sublocation->id }}">{{ $sublocation->name }}</option>
                                @endforeach
                                <option value="new" style="color: #4CAF50;">Add New Sub-Location</option>
                            </select>
                        </div>
                        @error('dvrsublocation_id')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset>
                        <div class="body-title">DVR Model <span class="tf-color-1">*</span></div>
                        <input type="text" name="dvr_model" value="{{ old('dvr_model') }}">
                        @error('dvr_model')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </fieldset>
                    <fieldset>
                        <div class="body-title">DVR Serial Number <span class="tf-color-1">*</span></div>
                        <input type="text" name="dvr_serial_number" value="{{ old('dvr_serial_number') }}">
                        @error('dvr_serial_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </fieldset>
                    
                    <fieldset>
                        <div class="body-title">DVR Installation Date</div>
                        <input type="date" name="dvr_installation_date" value="{{ old('dvr_installation_date') }}">
                        @error('dvr_installation_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </fieldset>
                    <!-- Purchase Date Field -->
                <fieldset class="name">
                    <div class="body-title">Purchase Date</div>
                    <input class="flex-grow" type="date" name="dvr_purchase_date" id="dvr_purchase_date" value="{{ old('dvr_purchase_date') }}">
                </fieldset>
                @error('dvr_purchase_date')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Warranty Duration Field -->
                <fieldset class="name">
                    <div class="body-title">Warranty Duration (Years)</div>
                    <select class="flex-grow" name="dvr_warranty_duration" id="dvr_warranty_duration">
                        <option value="1">1 Year</option>
                        <option value="2">2 Years</option>
                        <option value="3">3 Years</option>
                    </select>
                </fieldset>

                <!-- Warranty Expiration Field -->
                <fieldset class="name">
                    <div class="body-title">Warranty Expiration</div>
                    <input class="flex-grow" type="date" name="dvr_warranty_expiration" id="dvr_warranty_expiration" value="{{ old('dvr_warranty_expiration') }}" readonly>
                </fieldset>
                @error('dvr_warranty_expiration')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror
                    
                </div>
                `;
                $('.device_block').append(dvrFields);
            }
        });
    // Load locations by depot
    $('#depot_id').change(function() {
        if (this.value === 'new') {
            $('#addDepotModal').modal('show'); // Show the modal for adding a new depot
        }else{
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
                    $('#location_id').append('<option value="new" style="color: #4CAF50;"  id="addNewLocation">Add New Location</option>');
                },
                error: function() {
                    $('#location_id').append('<option value="new" style="color: #4CAF50;"  id="addNewLocation">Add New Location</option>');
                }
            });
        } else {
            $('#location_id').empty().append('<option value="new" style="color: #4CAF50;"  id="addNewLocation">Add New Location</option>');
        }
        }
    });

    $('#addNewLocation').on('click', function() {
        $('#addLocationModal').modal('show');
    });

    $('#location_id').on('change', function() {
        if ($(this).val() === 'new') {
            $('#addLocationModal').modal('show');
        }
    });
    $('.close_location').on('click', function () {
        $('#addLocationModal').modal('hide'); 
        $('#location_id').val('');
    });

    $('#addNewSubLocation').on('click', function() {
        $('#addLocationModal').modal('show');
    });

    $('#location_id').on('change', function() {
        if ($(this).val() === 'new') {
            $('#addLocationModal').modal('show');
        }
    });
    $('.close_location').on('click', function () {
        $('#addLocationModal').modal('hide'); 
        $('#location_id').val('');
    });

        // Calculate warranty expiration date
    $('#hdd_purchase_date, #hdd_warranty_duration').change(function() {
        var purchaseDate = $('#hdd_purchase_date').val();
        var warrantyDuration = parseInt($('#hdd_warranty_duration').val());

        if (purchaseDate && warrantyDuration) {
            var date = new Date(purchaseDate);
            date.setFullYear(date.getFullYear() + warrantyDuration);
            var expirationDate = date.toISOString().split('T')[0];
            $('#hdd_warranty_expiration').val(expirationDate);
        } else {
            $('#hdd_warranty_expiration').val('');
        }
    });
    $(document).on('change', '#nvr_purchase_date, #nvr_warranty_duration', function() {
        calculateExpirationDate('#nvr_purchase_date', '#nvr_warranty_duration', '#nvr_warranty_expiration');
    });

    $(document).on('change', '#dvr_purchase_date, #dvr_warranty_duration', function() {
        calculateExpirationDate('#dvr_purchase_date', '#dvr_warranty_duration', '#dvr_warranty_expiration');
    });

    $('.close').on('click', function () {
        $('#addDepotModal').modal('hide'); 
        $('#depot_id').val('');
    });


    function calculateExpirationDate(purchaseDateId, durationId, expirationId) {
        var purchaseDate = $(purchaseDateId).val();
        var warrantyDuration = parseInt($(durationId).val());

        if (purchaseDate && warrantyDuration) {
            var date = new Date(purchaseDate);
            date.setFullYear(date.getFullYear() + warrantyDuration);
            var expirationDate = date.toISOString().split('T')[0];
            $(expirationId).val(expirationDate);
        } else {
            $(expirationId).val('');
        }
    }
document.getElementById('saveDepotButton').addEventListener('click', function() {
    var depotName = document.getElementById('new_depot_name').value;

    $.ajax({
        url: "{{ route('admin.depots.store') }}", // Use the route defined above
        type: 'POST',
        data: {
            name: depotName,
            _token: '{{ csrf_token() }}' // Include CSRF token
        },
        success: function(data) {
            // Handle successful response
            $('#addDepotModal').modal('hide');

            // Optionally, add the new depot to the select element
            var select = document.getElementById('depot_id');
            var newOption = document.createElement('option');
            newOption.value = data.id; // Use the ID returned from the server
            newOption.text = data.name; // Use the name returned from the server
            select.add(newOption);
            select.value = newOption.value; 
            location.reload();
        },
        error: function(xhr) {
            // Handle errors
            var errors = xhr.responseJSON.errors;
            alert('Error: ' + errors.name[0] ); // Example of error handling
        }
    });
});


    // Save the new location via AJAX
    $('#saveLocationButton').on('click', function() {
        var locationName = $('#new_location_name').val();
        var selectedDepotId = $('#depot_id').val();

        $.ajax({
            url: "{{ route('admin.locations.store') }}", // Change this to your actual store route
            type: 'POST',
            data: {
                name: locationName,
                depot_id: selectedDepotId,
                _token: '{{ csrf_token() }}' // Include CSRF token
            },
            success: function(data) {
                // Handle successful response
                $('#addLocationModal').modal('hide');

                // Optionally, add the new location to the select element
                var select = document.querySelector('select[name="location_id"]'); // Make sure this matches your select element for locations
                var newOption = document.createElement('option');
                newOption.value = data.id; // Use the ID returned from the server
                newOption.text = data.name; // Use the name returned from the server
                select.add(newOption);
                select.value = newOption.value; 
                location.reload();
            },
            error: function(xhr) {
                // Handle errors
                var errors = xhr.responseJSON.errors;
                alert('Error: ' + errors.name[0]); // Example of error handling
            }
        });
    });
    $(document).on('change', '#sublocation_id', function() {
    if ($(this).val() === 'new_nvr') {
        $('#addSubLocationModal').modal('show');
    }
});

    $('.close').on('click', function() {
        $('#addSubLocationModal').modal('hide'); 
        $('#sublocation_id').val('');
    });
  // Save the new sub-location via AJAX
    $('#saveSubLocationButton').on('click', function() {
        var subLocationName = $('#new_sub_location_name').val();
        var selectedDepotId = $('#depot_id').val(); // Include the depot ID if needed

        $.ajax({
            url: "{{ route('admin.locations.sub_store') }}", // Use the correct route for storing sub-locations
            type: 'POST',
            data: {
                name: subLocationName,
                depot_id: selectedDepotId, // Include the depot ID in the request if necessary
                _token: '{{ csrf_token() }}' // Include CSRF token
            },
            success: function(data) {
                // Handle successful response
                $('#addSubLocationModal').modal('hide');

                // Optionally, add the new sub-location to the select element
                var select = document.querySelector('select[name="sublocation_id"]'); // Ensure this matches your select element for sub-locations
                var newOption = document.createElement('option');
                newOption.value = data.id; // Use the ID returned from the server
                newOption.text = data.name; // Use the name returned from the server
                select.add(newOption);
                select.value = newOption.value; 
                location.reload();
            },
            error: function(xhr) {
                // Handle errors
                var errors = xhr.responseJSON.errors;
                alert('Error: ' + errors.name[0]); // Example of error handling
            }
        });
    });
    

    $(document).on('change', '#dvr_sublocation', function() {
    if ($(this).val() === 'new') {
        $('#dvraddSubLocationModal').modal('show');
    }
});

    $('.dvrclose').on('click', function() {
        $('#dvraddSubLocationModal').modal('hide'); 
        $('#dvr_sublocation').val('');
    });

    // Save the new sub-location via AJAX
    $('#dvrsaveSubLocationButton').on('click', function(e) {
        e.preventDefault();
        var subLocationName = $('#dvrnew_sub_location_name').val();

        $.ajax({
            url: "{{ route('admin.locations.sub_store') }}",
            type: 'POST',
            data: {
                name: subLocationName,
                _token: '{{ csrf_token() }}' // Include CSRF token
            },
            success: function(data) {
                $('#dvraddSubLocationModal').modal('hide');

                // Add new sub-location to the DVR select element
                var select = document.querySelector('select[name="dvrsublocation_id"]');
                var newOption = document.createElement('option');
                newOption.value = data.id;
                newOption.text = data.name;
                select.add(newOption);
                select.value = newOption.value; 
                location.reload();
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                alert('Error: ' + errors.name[0]); // Handle errors
            }
        });
    });
});
</script>
@endsection
