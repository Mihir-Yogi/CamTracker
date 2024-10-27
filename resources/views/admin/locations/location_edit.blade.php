@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Edit Location</h3>
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
                    <a href="{{ route('admin.locations.index') }}">
                        <div class="text-tiny">Locations</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Edit Location</div>
                </li>
            </ul>
        </div>

        <!-- Edit Location Form -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.locations.update', ['location' => $location->id]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Location Name Field -->
                <fieldset class="name">
                    <div class="body-title">Location Name <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Enter location name" name="name" value="{{ old('name', $location->name) }}" required>
                </fieldset>
                @error('name')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror

                <!-- Select Depot Field -->
                <fieldset>
                    <div class="body-title">Select Depot <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="depot_id" required>
                            <option value="">Select a depot</option>
                            @foreach($depots as $depot)
                                <option value="{{ $depot->id }}" @if(old('depot_id', $location->depot_id) == $depot->id) selected @endif>
                                    {{ $depot->name }} ({{ $depot->city }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </fieldset>
                @error('depot_id')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror
                
                <!-- Save Button -->
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Update Location</button>
                    <a href="{{ route('admin.locations.index') }}" class="tf-button w208">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
