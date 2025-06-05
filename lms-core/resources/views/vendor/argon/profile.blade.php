@extends('layouts.app')

@section('content')
<style>
    .profile-form .mb-3 {
        margin-bottom: 0.75rem !important;
    }
    .profile-form .form-label {
        margin-bottom: 0.15rem;
        font-size: 0.97rem;
    }
    .profile-form .form-control {
        padding: 0.45rem 0.75rem;
        font-size: 0.97rem;
        min-height: 32px;
    }
    .profile-form textarea.form-control {
        min-height: 60px;
    }
    .profile-form {
        margin-bottom: 0;
    }
    .profile-form .row {
        margin-left: -0.375rem;
        margin-right: -0.375rem;
    }
    .profile-form .col-md-6, .profile-form .col-md-4 {
        padding-left: 0.375rem;
        padding-right: 0.375rem;
    }
    /* Only top part orange, rest white */
    .profile-top-bg {
        background: #ff6f47;
        border-radius: 12px 12px 0 0;
        padding: 2rem 2rem 1.5rem 2rem;
        margin-bottom: 0;
    }
    .profile-content-bg {
        background: #fff;
        border-radius: 0 0 12px 12px;
        padding: 2rem 2rem 2rem 2rem;
        margin-top: -1.5rem;
        box-shadow: 0 2px 8px rgba(44,62,80,0.07);
        position: relative;
        z-index: 2;
    }
    .container-profile-section {
        max-width: 900px;
        margin: 0 auto;
        margin-top: 2rem;
        position: relative;
        z-index: 1;
    }
</style>
<div class="container-profile-section">
    <div class="profile-top-bg">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0" style="color: #fff;">Profile Information</h4>
            <div>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </div>
    <div class="profile-content-bg">
        <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email ?? '' }}" disabled>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username ?? '') }}" disabled>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" value="{{ old('firstname', $user->firstname ?? '') }}">
                    @error('firstname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname', $user->lastname ?? '') }}">
                    @error('lastname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $user->address ?? '') }}">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $user->city ?? '') }}">
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country', $user->country ?? '') }}">
                    @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="postal" class="form-label">Postal Code</label>
                    <input type="text" class="form-control @error('postal') is-invalid @enderror" id="postal" name="postal" value="{{ old('postal', $user->postal ?? '') }}">
                    @error('postal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="about" class="form-label">About</label>
                <textarea class="form-control @error('about') is-invalid @enderror" id="about" name="about" rows="3">{{ old('about', $user->about ?? '') }}</textarea>
                @error('about')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</div>
@endsection
