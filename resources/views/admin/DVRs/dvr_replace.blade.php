@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Replace DVR</h3>
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
                    <a href="{{ route('admin.dvrs.index') }}">
                        <div class="text-tiny">DVRs</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Replace DVR</div>
                </li>
            </ul>
        </div>

        <!-- Replace DVR Form -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.dvrs.replace', $dvr->id) }}" enctype="multipart/form-data" method="POST">
                @csrf

                <!-- Depot Field (Read-Only) -->
                <fieldset class="name">
                    <div class="body-title">Depot</div>
                    <input class="flex-grow" type="text" name="depot" value="{{ $dvr->depot->name }}" disabled style="color: #6c757d;">
                </fieldset>

                <!-- Location Field (Read-Only) -->
                <fieldset class="name">
                    <div class="body-title">Location</div>
                    <input class="flex-grow" type="text" name="location" value="{{ $dvr->location->name }}" disabled style="color: #6c757d;">
                </fieldset>

                <!-- Model Field -->
                <fieldset class="name">
                    <div class="body-title">New Model <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Enter new model" name="model" value="{{ old('model') }}" required>
                </fieldset>
                @error('model')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Serial Number Field -->
                <fieldset class="name">
                    <div class="body-title">New Serial Number <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Enter new serial number" name="serial_number" value="{{ old('serial_number') }}" required>
                </fieldset>
                @error('serial_number')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Reason for Replacement Field -->
                <fieldset class="name">
                    <div class="body-title">Reason for Replacement <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Enter reason for replacing" name="failure_reason" value="{{ old('failure_reason') }}" required>
                </fieldset>
                @error('failure_reason')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Replace Image Field with Preview -->
                <fieldset class="name">
                    <div class="body-title">Replacement Reason Image</div>
                    <div class="image-preview-container" style="display: flex; gap: 20px; align-items: flex-start;">
                        <!-- Existing Image -->
                        @if($dvr->replace_image)
                            <div class="existing-image">
                                <p>Current Image:</p>
                                <img src="{{ $dvr->replace_image }}" alt="Replacement Reason Image" style="max-width: 150px; height: auto; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                            </div>
                        @endif

                        <!-- New Image Preview -->
                        <div class="new-image">
                            <p>New Image Preview:</p>
                            <img id="new-image-preview" src="#" alt="New Image Preview" style="max-width: 150px; height: auto; display: none; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                        </div>
                    </div>

                    <!-- Image Input Field -->
                    <input type="file" name="replace_image" accept="image/*" onchange="previewNewImage(event)">
                </fieldset>
                @error('replace_image')
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
                    <button class="tf-button w208" type="submit">Replace DVR</button>
                    <a href="{{ route('admin.dvrs.index') }}" class="tf-button w208">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
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
