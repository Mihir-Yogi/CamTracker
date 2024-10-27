@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Add New Depot</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="#">
                        <div class="text-tiny">Depots</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Add Depot</div>
                </li>
            </ul>
        </div>

        <!-- Depot Form -->
        <div class="wg-box">
            <form class="form-new-depot form-style-1" action="{{route('admin.depots.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Depot Name Field -->
                <fieldset class="name">
                    <div class="body-title">Depot Name <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Depot Name" name="name" tabindex="0" value="{{ old('name') }}" required>
                </fieldset>
                @error('name')
                    <span class="alert alert-danger text-center">{{ $message }}</span>
                @enderror

                <!-- City Field -->
                <fieldset class="name">
                    <div class="body-title">City <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="City" name="city" tabindex="1" value="{{ old('city') }}" required>
                </fieldset>
                @error('city')
                    <span class="alert alert-danger text-center">{{ $message }}</span>
                @enderror

                <!-- Submit Button -->
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save Depot</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

