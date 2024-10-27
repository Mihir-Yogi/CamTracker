@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Add NVR</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{ route('admin.nvrs.index') }}">
                        <div class="text-tiny">NVRs</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Add New NVR</div>
                </li>
            </ul>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Add NVR Form -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.nvrs.store') }}" method="POST">
                @csrf
                
                <!-- Model Field -->
                <fieldset class="name">
                    <div class="body-title">Model <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Enter model" name="model" value="{{ old('model') }}" required>
                </fieldset>
                @error('model')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Serial Number Field -->
                <fieldset class="name">
                    <div class="body-title">Serial Number <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Enter serial number" name="serial_number" value="{{ old('serial_number') }}" required>
                </fieldset>
                @error('serial_number')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

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

                <!-- Purchase Date Field -->
                <fieldset class="name">
                    <div class="body-title">Purchase Date</div>
                    <input class="flex-grow" type="date" name="purchase_date" value="{{ old('purchase_date') }}">
                </fieldset>
                @error('purchase_date')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Installation Date Field -->
                <fieldset class="name">
                    <div class="body-title">Installation Date</div>
                    <input class="flex-grow" type="date" name="installation_date" value="{{ old('installation_date') }}">
                </fieldset>
                @error('installation_date')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Warranty Expiration Field -->
                <fieldset class="name">
                    <div class="body-title">Warranty Expiration</div>
                    <input class="flex-grow" type="date" name="warranty_expiration" value="{{ old('warranty_expiration') }}">
                </fieldset>
                @error('warranty_expiration')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Save Button -->
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Add NVR</button>
                    <a href="{{ route('admin.nvrs.index') }}" class="tf-button w208">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript to handle depot selection -->
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
