@extends('layouts.admin')

@section('content')
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
        <div class="wg-box">
            <form class="form-style-1" action="{{ route('admin.combos.store') }}" method="POST">
                @csrf
                
                <!-- Select Depot Field -->
                <fieldset>
                    <div class="body-title">Select Depot <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="depot_id" id="depot_id" required>
                            <option value="">Select a depot</option>
                            @foreach($depots as $depot)
                                <option value="{{ $depot->id }}" @if(old('depot_id') == $depot->id) selected @endif>{{ $depot->name }} ({{ $depot->city }})</option>
                            @endforeach
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

                <!-- NVR Fields -->
                <div id="nvr_fields" style="display: none;">
                    <h4>NVR Details</h4>
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
                        <div class="body-title">NVR Purchase Date</div>
                        <input type="date" name="nvr_purchase_date" value="{{ old('nvr_purchase_date') }}">
                        @error('nvr_purchase_date')
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
                    <fieldset>
                        <div class="body-title">NVR Warranty Expiration</div>
                        <input type="date" name="nvr_warranty_expiration" value="{{ old('nvr_warranty_expiration') }}">
                        @error('nvr_warranty_expiration')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </fieldset>
                </div>

                <!-- DVR Fields -->
                <div id="dvr_fields" style="display: none;">
                    <h4>DVR Details</h4>
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
                        <div class="body-title">DVR Purchase Date</div>
                        <input type="date" name="dvr_purchase_date" value="{{ old('dvr_purchase_date') }}">
                        @error('dvr_purchase_date')
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
                    <fieldset>
                        <div class="body-title">DVR Warranty Expiration</div>
                        <input type="date" name="dvr_warranty_expiration" value="{{ old('dvr_warranty_expiration') }}">
                        @error('dvr_warranty_expiration')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </fieldset>
                </div>

                <!-- HDD Fields -->
                <h4>HDD Details</h4>
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
                        <div class="body-title">HDD Purchase Date</div>
                        <input type="date" name="hdd_purchase_date" value="{{ old('hdd_purchase_date') }}">
                        @error('hdd_purchase_date')
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
                    <fieldset>
                        <div class="body-title">HDD Warranty Expiration</div>
                        <input type="date" name="hdd_warranty_expiration" value="{{ old('hdd_warranty_expiration') }}">
                        @error('hdd_warranty_expiration')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </fieldset>

                <!-- Combo Fields -->
                <h4>Combo Details</h4>
                <fieldset>
                    <div class="body-title">Camera Capacity <span class="tf-color-1">*</span></div>
                    <input type="number" name="camera_capacity" value="{{ old('camera_capacity') }}" required>
                    @error('camera_capacity')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </fieldset>

                <!-- Submit Button -->
                <div class="form-footer">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Toggle NVR/DVR fields based on selection
    $('#device_type').change(function() {
        var deviceType = $(this).val();
        if (deviceType === 'nvr') {
            $('#nvr_fields').show();
            $('#dvr_fields').hide();
        } else if (deviceType === 'dvr') {
            $('#nvr_fields').hide();
            $('#dvr_fields').show();
        } else {
            $('#nvr_fields').hide();
            $('#dvr_fields').hide();
        }
    }).trigger('change');

    // Load locations by depot
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
@endsection
