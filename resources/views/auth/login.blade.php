@extends('layouts.app')
@section('content')
<h3>Login</h3>
<form method="POST" action="/login">
    @csrf
    <div class="mb-3">
        <label>Username</label>
        <input name="username" class="form-control">
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input name="password" type="password" class="form-control">
    </div>
    <button class="btn btn-primary">Login</button>
    <a href="{{ route('register') }}">Register</a>
</form>
@endsection
