@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Add CCTV Camera</h3>
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
                <li>
                    <div class="text-tiny">Add New CCTV Camera</div>
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

        <!-- Add CCTV Camera Form -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.cctvs.store') }}" method="POST" enctype="multipart/form-data">
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


                <!-- Combo Selection Field -->
                <fieldset>
                    <div class="body-title">Select Combo <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="combo_id" required>
                            <option value="">Select a combo (Depot - Location)</option>
                            @foreach($combos as $combo)
                                <option value="{{ $combo->id }}" @if(old('combo_id') == $combo->id) selected @endif>
                                    {{ $combo->depot->name }} - {{ $combo->location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </fieldset>
                @error('combo_id')
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
                    <button class="tf-button w208" type="submit">Add CCTV Camera</button>
                    <a href="{{ route('admin.cctvs.index') }}" class="tf-button w208">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
