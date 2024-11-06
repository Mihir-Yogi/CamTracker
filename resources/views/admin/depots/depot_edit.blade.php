@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Edit Depot</h3>
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
                    <a href="{{ route('admin.depots.index') }}">
                        <div class="text-tiny">Depots</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Edit Depot</div>
                </li>
            </ul>
        </div>
        
        <!-- Edit Depot Form -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.depots.update', $depot->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Depot Name Field -->
                <fieldset class="name">
                    <div class="body-title">Depot Name <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Enter depot name" name="name" value="{{ old('name', $depot->name) }}" required>
                </fieldset>
                @error('name')
                    <span class="alert alert-danger">{{ $message }}</span>
                @enderror


                <!-- Save Button -->
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
