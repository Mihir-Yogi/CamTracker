@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Add HDD</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('admin.hdds.index') }}">
                        <div class="text-tiny">HDDs</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <div class="text-tiny">Add New HDD</div>
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

        <!-- Add HDD Form -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.hdds.store') }}" method="POST">
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

                <!-- Capacity Field -->
                <fieldset class="name">
                    <div class="body-title">Capacity (GB) <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="number" placeholder="Enter capacity in GB" name="capacity" value="{{ old('capacity') }}" required>
                </fieldset>
                @error('capacity')
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
                    <div class="body-title">Purchase Date <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date') }}">
                </fieldset>
                @error('purchase_date')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Warranty Duration Field -->
                <fieldset class="name">
                    <div class="body-title">Warranty Duration (Years) <span class="tf-color-1">*</span></div>
                    <select class="flex-grow" name="warranty_duration" id="warranty_duration">
                        <option value="1">1 Year</option>
                        <option value="2">2 Years</option>
                        <option value="3">3 Years</option>
                    </select>
                </fieldset>

                <!-- Warranty Expiration Field -->
                <fieldset class="name">
                    <div class="body-title">Warranty Expiration <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="date" name="warranty_expiration" id="warranty_expiration" value="{{ old('warranty_expiration') }}" readonly>
                </fieldset>
                @error('warranty_expiration')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Save Button -->
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Add HDD</button>
                    <a href="{{ route('admin.hdds.index') }}" class="tf-button w208">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript to handle depot selection and warranty calculation -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Update location list based on depot selection
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

    // Calculate warranty expiration date
    $('#purchase_date, #warranty_duration').change(function() {
        var purchaseDate = $('#purchase_date').val();
        var warrantyDuration = parseInt($('#warranty_duration').val());

        if (purchaseDate && warrantyDuration) {
            var date = new Date(purchaseDate);
            date.setFullYear(date.getFullYear() + warrantyDuration);
            var expirationDate = date.toISOString().split('T')[0];
            $('#warranty_expiration').val(expirationDate);
        } else {
            $('#warranty_expiration').val('');
        }
    });
});
</script>
@endsection
