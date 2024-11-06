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
            <h3>Edit CCTV Camera</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('admin.cctvs.index') }}">
                        <div class="text-tiny">CCTVs</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Edit CCTV Camera</div></li>
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
        
        <!-- Edit CCTV Form -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.cctvs.update', $cctv->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                                <!-- Combo Selection (Editable) -->
                                <fieldset class="name">
                    <div class="body-title">Combo <span class="tf-color-1">*</span></div>
                    <select class="flex-grow" id="combo_id" name="combo_id" required>
                        <option value="">Select Combo</option>
                        @foreach($combos as $combo)
                            <option value="{{ $combo->id }}" {{ old('combo_id', $cctv->combo_id) == $combo->id ? 'selected' : '' }}>
                                {{ $combo->depot->name ?? 'N/A' }} - {{ $combo->location->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </fieldset>
                @error('combo_id')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <fieldset>
                            <div class="body-title">Select Sub-Location <span class="tf-color-1">*</span></div>
                            <div class="select flex-grow">
                                <select name="sublocation_id" id="sublocation_id" >
                                    <option value="">Select a Sub-Location</option>
                                    @foreach($sublocations as $sublocation)
                                        <option value="{{ $sublocation->id }}">{{ $sublocation->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        @error('sublocation_id')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                <!-- Model Field -->
                <fieldset class="name">
                    <div class="body-title">Model <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Enter model" name="model" value="{{ old('model', $cctv->model) }}" required>
                </fieldset>
                @error('model')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror



                <!-- Serial Number Field -->
                <fieldset class="name">
                    <div class="body-title">Serial Number</div>
                    <input class="flex-grow" type="text" placeholder="Enter serial number" name="serial_number" value="{{ old('serial_number', $cctv->serial_number) }}">
                </fieldset>
                @error('serial_number')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- megapixel Field -->
                <fieldset class="name">
                    <div class="body-title">Megapixel<span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="number" placeholder="Enter Megapixel" name="megapixel" value="{{ old('megapixel', $cctv->megapixel) }}" required>
                </fieldset>
                @error('megapixel')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror


                <!-- Purchase Date Field -->
                <fieldset class="name">
                    <div class="body-title">Purchase Date</div>
                    <input class="flex-grow" type="date" name="purchase_date" value="{{ old('purchase_date', $cctv->purchase_date) }}">
                </fieldset>
                @error('purchase_date')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Installation Date Field -->
                <fieldset class="name">
                    <div class="body-title">Installation Date</div>
                    <input class="flex-grow" type="date" name="installation_date" value="{{ old('installation_date', $cctv->installation_date) }}">
                </fieldset>
                @error('installation_date')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Warranty Expiration Field -->
                <fieldset class="name">
                    <div class="body-title">Warranty Expiration</div>
                    <input class="flex-grow" type="date" name="warranty_expiration" value="{{ old('warranty_expiration', $cctv->warranty_expiration) }}">
                </fieldset>
                @error('warranty_expiration')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Save Button -->
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save Changes</button>
                    <a href="{{ route('admin.cctvs.index') }}" class="tf-button w208">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .block{
        display: inline-block;
        width: 50%;
    }
</style>
@endsection
