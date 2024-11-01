@extends('layouts.admin')

@section('content')

<style>
    .depot-location-text {
    color: #a9a9a9;
    opacity: 0.7; 
    }
</style>
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Edit HDD</h3>
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
                    <a href="{{ route('admin.hdds.index') }}">
                        <div class="text-tiny">HDDs</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Edit HDD</div>
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
        
        <!-- Edit hdd Form -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.hdds.update', $hdd->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Model Field -->
                <fieldset class="name">
                    <div class="body-title">Model <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Enter model" name="model" value="{{ old('model', $hdd->model) }}" required>
                </fieldset>
                @error('model')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <fieldset>
                    <div class="body-title">HDD Sub-Location <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="hdd_sublocation" id="hdd_sublocation" >
                            <option value="">Select a Sub-location</option>
                            <option value="Deasal Station" {{ (old('hdd_sublocation', $hdd->sublocation ?? '') == 'Deasal Station') ? 'selected' : '' }}>DEASAL STATION</option>
                            <option value="Washing Station" {{ (old('hdd_sublocation', $hdd->sublocation ?? '') == 'Washing Station') ? 'selected' : '' }}>WASHING STATION</option>
                        </select>
                    </div>
                    @error('dvr_sublocation')
                        <span class="alert alert-danger">{{ $message }}</span>
                    @enderror
                </fieldset>
                <!-- Capacity Field -->
                <fieldset class="name">
                    <div class="body-title">Capacity <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Enter Capacity" name="capacity" value="{{ old('capacity', $hdd->capacity) }}" required>
                </fieldset>
                @error('model')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Serial Number Field -->
                <fieldset class="name">
                    <div class="body-title">Serial Number <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Enter serial number" name="serial_number" value="{{ old('serial_number', $hdd->serial_number) }}" required>
                </fieldset>
                @error('serial_number')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Depot Field (Non-Editable) -->
                <fieldset>
                    <div class="body-title">Depot</div>
                    <input class="flex-grow depot-location-text" type="text" value="{{ $hdd->depot->name }} ({{ $hdd->depot->city }})" disabled>
                    <input type="hidden" name="depot_id" value="{{ $hdd->depot_id }}">
                </fieldset>

                <!-- Location Field (Non-Editable) -->
                <fieldset>
                    <div class="body-title">Location</div>
                    <input class="flex-grow depot-location-text" type="text" value="{{ $hdd->location->name }}" disabled>
                    <input type="hidden" name="location_id" value="{{ $hdd->location_id }}">
                </fieldset>

                <!-- Purchase Date Field -->
                <fieldset class="name">
                    <div class="body-title">Purchase Date</div>
                    <input class="flex-grow" type="date" name="purchase_date" value="{{ old('purchase_date', $hdd->purchase_date ? $hdd->purchase_date : '') }}">
                </fieldset>
                @error('purchase_date')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Installation Date Field -->
                <fieldset class="name">
                    <div class="body-title">Installation Date</div>
                    <input class="flex-grow" type="date" name="installation_date" value="{{ old('installation_date', $hdd->installation_date ? $hdd->installation_date : '') }}">
                </fieldset>
                @error('installation_date')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Warranty Expiration Field -->
                <fieldset class="name">
                    <div class="body-title">Warranty Expiration</div>
                    <input class="flex-grow" type="date" name="warranty_expiration" value="{{ old('warranty_expiration', $hdd->warranty_expiration ? $hdd->warranty_expiration : '') }}">
                </fieldset>
                @error('warranty_expiration')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Save Button -->
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save Changes</button>
                    <a href="{{ route('admin.hdds.index') }}" class="tf-button w208">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
