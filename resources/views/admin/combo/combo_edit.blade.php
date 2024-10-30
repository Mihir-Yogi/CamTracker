@extends('layouts.admin')

@section('content')

<style>
    .depot-location-text {
        color: #a9a9a9;
        opacity: 0.7;
    }
    .device-toggle-section, .device-section { 
        margin-top: 20px; 
    }
    .hidden { 
        display: none; 
    }
</style>

<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Page Header and Breadcrumbs -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Edit Combo</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('admin.index') }}"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><a href="{{ route('admin.combos.index') }}"><div class="text-tiny">Combos</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Edit Combo</div></li>
            </ul>
        </div>

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

        <!-- Edit Combo Form -->
        <div class="wg-box">
            <form class="form-style-1" action="{{ route('admin.combos.update', $combo->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Depot and Location Selection -->
                <fieldset>
                    <div class="body-title">Depot <span class="tf-color-1">*</span></div>
                    <select name="depot_id" id="depot_id" required>
                        <option value="">Select a depot</option>
                        @foreach($depots as $depot)
                            <option value="{{ $depot->id }}" {{ $combo->depot_id == $depot->id ? 'selected' : '' }}>{{ $depot->name }}</option>
                        @endforeach
                    </select>
                </fieldset>
                <fieldset>
                    <div class="body-title">Location <span class="tf-color-1">*</span></div>
                    <select name="location_id" id="location_id" required>
                        <option value="{{ $combo->location_id }}">{{ $combo->location->name }}</option>
                    </select>
                </fieldset>

                <!-- Device Toggle Section -->
                <div class="device-toggle-section">
                    <label>Select Device Type:</label>
                    <input type="radio" name="device_type" value="NVR" id="nvr_radio" {{ $combo->nvr ? 'checked' : '' }}> NVR
                    <input type="radio" name="device_type" value="DVR" id="dvr_radio" {{ $combo->dvr ? 'checked' : '' }}> DVR
                </div>

                <!-- Conditional NVR Fields -->
                <div id="nvr-fields" style="display: {{ $combo->nvr ? 'block' : 'none' }};">
                    <h4>NVR Details</h4>
                    <fieldset><div class="body-title">NVR Model <span class="tf-color-1">*</span></div><input type="text" name="nvr_model" value="{{ old('nvr_model', $combo->nvr->model ?? '') }}"></fieldset>
                    <fieldset><div class="body-title">NVR Serial Number <span class="tf-color-1">*</span></div><input type="text" name="nvr_serial_number" value="{{ old('nvr_serial_number', $combo->nvr->serial_number ?? '') }}"></fieldset>
                    <fieldset><div class="body-title">NVR Purchase Date</div><input type="date" name="nvr_purchase_date" value="{{ old('nvr_purchase_date', $combo->nvr->purchase_date ?? '') }}"></fieldset>
                    <fieldset><div class="body-title">NVR Installation Date</div><input type="date" name="nvr_installation_date" value="{{ old('nvr_installation_date', $combo->nvr->installation_date ?? '') }}"></fieldset>
                    <fieldset><div class="body-title">NVR Warranty Expiration</div><input type="date" name="nvr_warranty_expiration" value="{{ old('nvr_warranty_expiration', $combo->nvr->warranty_expiration ?? '') }}"></fieldset>
                </div>

                <!-- Conditional DVR Fields -->
                <div id="dvr-fields" style="display: {{ $combo->dvr ? 'block' : 'none' }};">
                    <h4>DVR Details</h4>
                    <fieldset><div class="body-title">DVR Model <span class="tf-color-1">*</span></div><input type="text" name="dvr_model" value="{{ old('dvr_model', $combo->dvr->model ?? '') }}"></fieldset>
                    <fieldset><div class="body-title">DVR Serial Number <span class="tf-color-1">*</span></div><input type="text" name="dvr_serial_number" value="{{ old('dvr_serial_number', $combo->dvr->serial_number ?? '') }}"></fieldset>
                    <fieldset><div class="body-title">DVR Purchase Date</div><input type="date" name="dvr_purchase_date" value="{{ old('dvr_purchase_date', $combo->dvr->purchase_date ?? '') }}"></fieldset>
                    <fieldset><div class="body-title">DVR Installation Date</div><input type="date" name="dvr_installation_date" value="{{ old('dvr_installation_date', $combo->dvr->installation_date ?? '') }}"></fieldset>
                    <fieldset><div class="body-title">DVR Warranty Expiration</div><input type="date" name="dvr_warranty_expiration" value="{{ old('dvr_warranty_expiration', $combo->dvr->warranty_expiration ?? '') }}"></fieldset>
                </div>

                <!-- HDD Fields -->
                <h4>HDD Details</h4>
                <fieldset><div class="body-title">HDD Model <span class="tf-color-1">*</span></div><input type="text" name="hdd_model" value="{{ old('hdd_model', $combo->hdd->model) }}" required></fieldset>
                <fieldset><div class="body-title">HDD Serial Number <span class="tf-color-1">*</span></div><input type="text" name="hdd_serial_number" value="{{ old('hdd_serial_number', $combo->hdd->serial_number) }}" required></fieldset>
                <fieldset><div class="body-title">HDD Capacity (GB) <span class="tf-color-1">*</span></div><input type="number" name="hdd_capacity" value="{{ old('hdd_capacity', $combo->hdd->capacity) }}" required></fieldset>

                <!-- Combo Details -->
                <fieldset><div class="body-title">Camera Capacity <span class="tf-color-1">*</span></div><input type="number" name="camera_capacity" value="{{ old('camera_capacity', $combo->camera_capacity) }}" required></fieldset>

                <!-- Save and Cancel Buttons -->
                <div class="form-footer">
                    <button type="submit" class="tf-button w208">Save Changes</button>
                    <a href="{{ route('admin.combos.index') }}" class="tf-button w208">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>  

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Initial setup: hide or show fields based on the selected device type
    if ($('#nvr_radio').is(':checked')) {
        $('#nvr-fields').show();
        $('#dvr-fields').hide();
    } else {
        $('#dvr-fields').show();
        $('#nvr-fields').hide();
    }

    // Toggle fields when radio buttons are changed
    $('input[name="device_type"]').change(function() {
        if ($(this).val() === 'NVR') {
            $('#nvr-fields').show();
            $('#dvr-fields').hide();

            // Clear DVR fields
            $('input[name="dvr_model"]').val('');
            $('input[name="dvr_serial_number"]').val('');
            $('input[name="dvr_purchase_date"]').val('');
            $('input[name="dvr_installation_date"]').val('');
            $('input[name="dvr_warranty_expiration"]').val('');
        } else {
            $('#dvr-fields').show();
            $('#nvr-fields').hide();

            // Clear NVR fields
            $('input[name="nvr_model"]').val('');
            $('input[name="nvr_serial_number"]').val('');
            $('input[name="nvr_purchase_date"]').val('');
            $('input[name="nvr_installation_date"]').val('');
            $('input[name="nvr_warranty_expiration"]').val('');
        }
    });
});
</script>

@endsection
