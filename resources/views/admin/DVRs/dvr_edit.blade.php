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
            <h3>Edit dvr</h3>
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
                        <div class="text-tiny">dvrs</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Edit dvr</div>
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
        
        <!-- Edit dvr Form -->
        <div class="wg-box">
            <form class="form-new-product form-style-2" style="gap: 20px;" action="{{ route('admin.dvrs.update', $dvr->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Depot Field (Non-Editable) -->
                <fieldset>
                    <div class="block">
                        <div class="body-title">Depot</div>
                        <input class="flex-grow depot-location-text" type="text" value="{{ $dvr->depot->name }} " disabled>
                        <input type="hidden" name="depot_id" value="{{ $dvr->depot_id }}">
                    </div>
                    <div class="block">
                        <div class="body-title">Location</div>
                        <input class="flex-grow depot-location-text" type="text" value="{{ $dvr->location->name }}" disabled>
                        <input type="hidden" name="location_id" value="{{ $dvr->location_id }}">
                    </div>
                </fieldset>

                <fieldset>
                    <div class="block" style="width: 100%;">
                        <div class="body-title">Dvr Sub-Location <span class="tf-color-1">*</span></div>
                        <div class="select flex-grow">
                            <select name="sublocation_id" id="sublocation_id" >
                                <option value="">Select a Sub-location</option>
                                @foreach($sublocations as $sublocation)
                                    <option  {{ $dvr->sublocation->name == $sublocation->name ? 'selected' : '' }}  value="{{ $sublocation->id }}">{{ $sublocation->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('sublocation')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>

                <!-- Model Field -->
                <fieldset class="name">
                    <div class="block">
                        <div class="body-title">Model <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Enter model" name="model" value="{{ old('model', $dvr->model) }}" required>
                        @error('model')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="block">
                        <div class="body-title">Serial Number <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Enter serial number" name="serial_number" value="{{ old('serial_number', $dvr->serial_number) }}" required>
                        @error('serial_number')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>

                <fieldset class="name">
                    <div class="block">
                        <div class="body-title">Installation Date</div>
                        <input class="flex-grow" type="date" name="installation_date" value="{{ old('installation_date', $dvr->installation_date ? $dvr->installation_date : '') }}">
                        @error('installation_date')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="block">
                        <div class="body-title">Purchase Date</div>
                        <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date',$dvr->purchase_date) }}">  
                        @error('purchase_date')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </fieldset>

                <fieldset>
                            
                            <div class="block">
                                <div class="body-title">Warranty Duration (Years)</div>
                                <select name="warranty_duration" id="warranty_duration">
                                    <option value="1">1 Year</option>
                                    <option value="2">2 Years</option>
                                    <option value="3">3 Years</option>
                                </select>
                            </div>
                            <div class="block">
                                <div class="body-title">Warranty Expiration</div>
                                <input type="date" name="warranty_expiration" id="warranty_expiration" value="{{ old('warranty_expiration',$dvr->warranty_expiration) }}" readonly>
                                @error('warranty_expiration')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </fieldset>
                <!-- Save Button -->
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save Changes</button>
                    <a href="{{ route('admin.dvrs.index') }}" class="tf-button w208">Cancel</a>
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
</script>
@endsection
