@extends('layouts.app')

@section('content')
<h3>Tambah Buku</h3>
<form method="POST" action="{{ route('books.store') }}">
    @csrf
    @include('books.partials.form', ['book' => new \App\Models\Book])
</form>
@endsection
