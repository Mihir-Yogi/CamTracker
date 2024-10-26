@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Edit User</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <div class="wg-box">
            <form class="form-new-product form-style-1" method="POST" action="{{ route('admin.user.update', $user->id) }}">
                @csrf
                @method('PUT')

                <fieldset class="name">
                    <div class="body-title">Name <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" name="name" id="name" placeholder="User Name" value="{{ old('name', $user->name) }}" aria-required="true" required="">
                </fieldset>
                @error('name') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="name">
                    <div class="body-title">Email <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="email" name="email" id="email" placeholder="User Email" value="{{ old('email', $user->email) }}" aria-required="true" required="">
                </fieldset>
                @error('email') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <fieldset class="category">
                    <div class="body-title">User Type <span class="tf-color-1">*</span></div>
                    <div class="select flex-grow">
                        <select name="utype" id="utype" required>
                            <option value="USR" {{ $user->utype === 'USR' ? 'selected' : '' }}>User</option>
                            <option value="ADM" {{ $user->utype === 'ADM' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                </fieldset>
                @error('utype') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
