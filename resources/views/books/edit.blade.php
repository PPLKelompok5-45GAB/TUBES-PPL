@extends('layouts.app')

@section('content')
<h3>Edit Buku</h3>
<form method="POST" action="{{ route('books.update', $book) }}">
    @csrf @method('PUT')
    @include('books.partials.form', ['book' => $book])
</form>
@endsection
