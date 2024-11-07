@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Replace CCTV Camera</h3>
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
                    <a href="{{ route('admin.cctvs.index') }}">
                        <div class="text-tiny">CCTV Cameras</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Replace CCTV Camera</div>
                </li>
            </ul>
        </div>

        <!-- Replace CCTV Form -->
        <div class="wg-box">
            <form class="form-new-product form-style-2" action="{{ route('admin.cctvs.replace', $cctv->id) }}" enctype="multipart/form-data" method="POST">
                @csrf

                <!-- Depot Field (Read-Only) -->
                <fieldset class="name">
                    <div class="block">
                        <div class="body-title">Depot</div>
                        <input class="flex-grow" type="text" name="depot" value="{{ $cctv->combo->depot->name }}" disabled style="color: #6c757d;">
                    </div>
                    <div class="block">
                        <div class="body-title">Location</div>
                        <input class="flex-grow" type="text" name="location" value="{{ $cctv->combo->location->name }}" disabled style="color: #6c757d;">
                    </div>
                </fieldset>

                <!-- Select sub-Location Field -->
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
                
                <!-- New Model Field -->
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
                        @error('serial_number')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>

                
                <!-- New megapixel Field -->
                <fieldset class="name">
                    <div class="block">
                        <div class="body-title">New Megapixel<span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="number" placeholder="Enter Megapixel" name="megapixel" value="{{ old('megapixel') }}" required>
                        @error('megapixel')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="block">
                        <div class="body-title">Installation Date</div>
                        <input class="flex-grow" type="date" name="installed_date" value="{{ old('installed_date') }}">
                        @error('installed_date')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    </fieldset>
                    <fieldset>
                            
                            <div class="block">
                                <div class="body-title">Purchase Date</div>
                                <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date') }}">
                                @error('purchase_date')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="block">
                                <div class="body-title">Warranty Duration (Years)</div>
                                <select name="warranty_duration" id="warranty_duration">
                                    <option value="1">1 Year</option>
                                    <option value="2">2 Years</option>
                                    <option value="3">3 Years</option>
                                </select>
                            </div>
                        </fieldset>

                        
                        <fieldset>
                            
                            <div class="block">
                                <div class="body-title">Warranty Expiration</div>
                                <input type="date" name="warranty_expiration" id="warranty_expiration" value="{{ old('warranty_expiration') }}" readonly>
                                @error('warranty_expiration')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </fieldset>
                <fieldset>
                    <div class="body-title">Reason for Replacement <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Enter reason for replacing" name="failure_reason" value="{{ old('failure_reason') }}" required>
                    @error('failure_reason')
                        <span class="alert alert-danger">{{ $message }}</span>
                    @enderror
                </fieldset>
                    
                    <!-- Replace Image Field with Preview -->
                <fieldset class="name">
                    <div class="body-title">Replacement Image</div>
                    <div class="image-preview-container" style="display: flex; gap: 20px; align-items: flex-start;">
                        <!-- Existing Image -->
                        @if($cctv->replace_image)
                            <div class="existing-image">
                                <p>Current Image:</p>
                                <img src="{{ $cctv->replace_image }}" alt="Current CCTV Image" style="max-width: 150px; height: auto; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
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

                <!-- Save Button -->
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Replace CCTV</button>
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
</script>
@endsection
