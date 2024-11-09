@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Replace NVR</h3>
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
                    <div class="text-tiny">Replace NVR</div>
                </li>
            </ul>
        </div>

        <!-- Replace NVR Form -->
        <div class="wg-box">
            <form class="form-new-product form-style-2" style="gap: 20px;" action="{{ route('admin.nvrs.replace', $nvr->id) }}"  enctype="multipart/form-data" method="POST">
                @csrf

                <!-- Depot Field (Read-Only) -->
                <fieldset class="name">
                    <div class="block">
                        <div class="body-title">Depot</div>
                        <input class="flex-grow" type="text" name="depot" value="{{ $nvr->depot->name }}" disabled style="color: #6c757d;">
                    </div>
                    <div class="block">
                        <div class="body-title">Location</div>
                        <input class="flex-grow" type="text" name="location" value="{{ $nvr->location->name }}" disabled style="color: #6c757d;">
                    </div>
                </fieldset>

                <fieldset>
                    <div class="blok" style="width: 100%;">
                        <div class="body-title">Select Sub-Location <span class="tf-color-1">*</span></div>
                        <div class="select flex-grow">
                            <select name="sublocation_id" id="sublocation_id" >
                                <option value="">Select a Sub-Location</option>
                                @foreach($sublocations as $sublocation)
                                    <option  {{ $nvr->sublocation->name == $sublocation->name ? 'selected' : '' }}   value="{{ $sublocation->id }}" >{{ $sublocation->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </fieldset>
                @error('sublocation_id')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Model Field -->
                <fieldset class="name">
                    <div class="block">
                        <div class="body-title">New Model <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Enter new model" name="model" value="{{ old('model') }}" required>
                        @error('model')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="block">
                        <div class="body-title">New Serial Number <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Enter new serial number" name="serial_number" value="{{ old('serial_number') }}" required>
                        <!-- Serial Number Field -->
                        @error('serial_number')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>

                <!-- Reason for Replacement Field -->
                <fieldset class="name">
                    <div class="block">
                        <div class="body-title">Reason for Replacement <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Enter reason for replacing" name="failure_reason" value="{{ old('failure_reason') }}" required>
                        @error('failure_reason')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="block">
                        <div class="body-title">Replacement Reason Image <span class="tf-color-1">*</span></div>
                        <div class="image-preview-container" style="display: flex; gap: 20px; align-items: flex-start;">
                            <!-- Existing Image -->
                            @if($nvr->replace_image)
                                <div class="existing-image">
                                    <p>Current Image:</p>
                                    <img src="{{ $nvr->replace_image }}" alt="Replacement Reason Image" style="max-width: 150px; height: auto; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
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
                        
                        @error('replace_image')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>

                <fieldset class="name">
                    <div class="block">
                        <div class="body-title">Installation Date <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="date" name="installation_date" value="{{ old('installation_date')}}">
                        @error('installation_date')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="block">
                        <div class="body-title">Purchase Date <span class="tf-color-1">*</span></div>
                        <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date') }}">  
                        @error('purchase_date')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>

                <fieldset>
                    <div class="block">
                        <div class="body-title">Warranty Duration (Years) <span class="tf-color-1">*</span></div>
                        <select name="warranty_duration" id="warranty_duration">
                            <option value="1">1 Year</option>
                            <option value="2">2 Years</option>
                            <option value="3">3 Years</option>
                        </select>
                    </div>
                    <div class="block">
                        <div class="body-title">Warranty Expiration <span class="tf-color-1">*</span></div>
                        <input type="date" name="warranty_expiration" id="warranty_expiration" value="{{ old('warranty_expiration') }}" readonly>
                        @error('warranty_expiration')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>

                <!-- Save Button -->
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Replace NVR</button>
                    <a href="{{ route('admin.nvrs.index') }}" class="tf-button w208">Cancel</a>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    

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
