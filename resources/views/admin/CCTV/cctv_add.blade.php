@extends('layouts.admin')

@section('content')
<style>
.modal-backdrop {
    background-color: transparent !important; /* Makes the backdrop transparent */
}
.modal-dialog{
    max-width: 500px;
    font-size: 13px ;
}
.model_button{
    font-size: 13px;
    width: 100px;
}
</style>

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
            <form class="form-new-product form-style-2 two-column-form" action="{{ route('admin.cctvs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="fom-new-product form-style-1 tow-column-form">
                        <fieldset>
                            <div class="body-title">Select Combo <span class="tf-color-1">*</span></div>
                            <select name="combo_id" required>
                                <option value="">Select a (Depot/Division - Location)</option>
                                @foreach($combos as $combo)
                                    <option value="{{ $combo->id }}" @if(old('combo_id') == $combo->id) selected @endif>
                                        {{ $combo->depot->name }} - {{ $combo->location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                        @error('combo_id')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror

                        <fieldset>
                            <div class="body-title">Select Location <span class="tf-color-1">*</span></div>
                            <select name="sublocation_id" id="sublocation_id">
                                <option value="">Select a Sub-Location</option>
                                @foreach($sublocations as $sublocation)
                                    <option value="{{ $sublocation->id }}">{{ $sublocation->name }}</option>
                                @endforeach
                                <option value="new" style="color: #4CAF50;">Add New Sub-Location</option>
                            </select>
                        </fieldset>
                        @error('sublocation')
                            <span class="alert alert-danger">{{ $message }}</span>
                        @enderror

                </div>
                        <fieldset>
                            <div class="block">
                                <div class="body-title">Model <span class="tf-color-1">*</span></div>
                                <input type="text" placeholder="Enter model" name="model" value="{{ old('model') }}" required>
                                @error('model')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="block">
                                <div class="body-title">Serial Number <span class="tf-color-1">*</span></div>
                                <input type="text" placeholder="Enter serial number" name="serial_number" value="{{ old('serial_number') }}" required>
                                @error('serial_number')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </fieldset>
                        

                        <fieldset>
                            
                            <div class="block">
                                <div class="body-title">Megapixel<span class="tf-color-1">*</span></div>
                                <input type="number" placeholder="Enter Megapixel" name="megapixel" value="{{ old('megapixel') }}" required>
                                @error('megapixel')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="block">
                                <div class="body-title">Installation Date <span class="tf-color-1">*</span></div>
                                <input type="date" name="installation_date" value="{{ old('installation_date') }}">
                                @error('installation_date')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </fieldset>
                        
                        <fieldset>
                            
                            <div class="block">
                                <div class="body-title">Purchase Date <span class="tf-color-1">*</span></div>
                                <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date') }}">
                                @error('purchase_date')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="block">
                                <div class="body-title">Warranty Duration (Years) <span class="tf-color-1">*</span></div>
                                <select name="warranty_duration" id="warranty_duration">
                                    <option value="1">1 Year</option>
                                    <option value="2">2 Years</option>
                                    <option value="3">3 Years</option>
                                </select>
                            </div>
                        </fieldset>
                        <fieldset>
                            
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
                    <button class="tf-button w208" type="submit">Add CCTV Camera</button>
                    <a href="{{ route('admin.cctvs.index') }}" class="tf-button w208">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Adding New Sub-Location -->
<div class="modal fade" id="addSubLocationModal" tabindex="-1" role="dialog" aria-labelledby="addSubLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubLocationModalLabel">Create New Sub-Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="new_sub_location_name" style="margin-bottom: 10px;">Sub-Location Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="new_sub_location_name" id="new_sub_location_name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveSubLocationButton">Save Sub-Location</button>
            </div>
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
    
    $('#sublocation_id').on('change', function() { // Adjusted selector to match the new ID
        if ($(this).val() === 'new') {
            $('#addSubLocationModal').modal('show');
        }
    });

    $('.close').on('click', function() {
        $('#addSubLocationModal').modal('hide'); 
        $('#sublocation_id').val('');
    });
  // Save the new sub-location via AJAX
    $('#saveSubLocationButton').on('click', function() {
        var subLocationName = $('#new_sub_location_name').val();
        var selectedDepotId = $('#depot_id').val(); // Include the depot ID if needed

        $.ajax({
            url: "{{ route('admin.locations.sub_store') }}", // Use the correct route for storing sub-locations
            type: 'POST',
            data: {
                name: subLocationName,
                depot_id: selectedDepotId, // Include the depot ID in the request if necessary
                _token: '{{ csrf_token() }}' // Include CSRF token
            },
            success: function(data) {
                // Handle successful response
                $('#addSubLocationModal').modal('hide');

                // Optionally, add the new sub-location to the select element
                var select = document.querySelector('select[name="sublocation_id"]'); // Ensure this matches your select element for sub-locations
                var newOption = document.createElement('option');
                newOption.value = data.id; // Use the ID returned from the server
                newOption.text = data.name; // Use the name returned from the server
                select.add(newOption);
                select.value = newOption.value; 
                location.reload();
            },
            error: function(xhr) {
                // Handle errors
                var errors = xhr.responseJSON.errors;
                alert('Error: ' + errors.name[0]); // Example of error handling
            }
        });
    });
    

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
