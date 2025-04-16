@extends('layouts.app')

@section('content')
<div class="text-center">
    <h1>Halo, {{ Auth::user()->name }}!</h1>
    <p class="lead">Selamat datang kembali di aplikasi koleksi buku.</p>
    <a href="{{ route('books.index') }}" class="btn btn-success">Lihat Koleksi Buku</a>
</div>
@endsection
