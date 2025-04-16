@extends('layouts.app')
@section('content')
<h3>Register</h3>
<form method="POST" action="/register">
    @csrf
    <div class="mb-3">
        <label>Nama</label>
        <input name="name" class="form-control">
    </div>
    <div class="mb-3">
        <label>Username</label>
        <input name="username" class="form-control">
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input name="password" type="password" class="form-control">
    </div>
    <button class="btn btn-success">Register</button>
    <a href="{{ route('login') }}">Login</a>
</form>
@endsection
