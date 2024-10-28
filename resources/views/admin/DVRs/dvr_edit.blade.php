@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Edit DVR</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <div class="wg-box">
            <form class="form-new-product form-style-1" method="POST" action="{{ route('admin.dvrs.update', $dvr->id) }}">
                @csrf
                @method('PUT')

                <fieldset class="name">
                    <div class="body-title">Model <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" name="model" id="model" placeholder="DVR Model" value="{{ old('model', $dvr->model) }}" required="">
                </fieldset>
                @error('model') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="name">
                    <div class="body-title">Serial Number <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" name="serial_number" id="serial_number" placeholder="DVR Serial Number" value="{{ old('serial_number', $dvr->serial_number) }}" required="">
                </fieldset>
                @error('serial_number') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="name">
                    <div class="body-title">Status <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="status" id="status" required>
                            <option value="working" {{ $dvr->status === 'working' ? 'selected' : '' }}>Working</option>
                            <option value="failed" {{ $dvr->status === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                </fieldset>
                @error('status') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="name">
                    <div class="body-title">Purchase Date</div>
                    <input class="flex-grow" type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', $dvr->purchase_date ? $dvr->purchase_date : '') }}">
                </fieldset>
                @error('purchase_date') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="name">
                    <div class="body-title">Installation Date</div>
                    <input class="flex-grow" type="date" name="installation_date" id="installation_date" value="{{ old('installation_date', $dvr->installation_date ? $dvr->installation_date : '') }}">
                </fieldset>
                @error('installation_date') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="name">
                    <div class="body-title">Warranty Expiration</div>
                    <input class="flex-grow" type="date" name="warranty_expiration" id="warranty_expiration" value="{{ old('warranty_expiration', $dvr->warranty_expiration ? $dvr->warranty_expiration : '') }}">
                </fieldset>
                @error('warranty_expiration') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Update DVR</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
