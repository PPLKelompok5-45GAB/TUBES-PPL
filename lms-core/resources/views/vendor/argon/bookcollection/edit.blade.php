@extends('vendor.argon.layout')
@section('content')
    <h1>Edit Book Collection</h1>
    <form method="POST" action="{{ route('bookcollection.update', $bookcollection) }}">
        @csrf
        @method('PUT')
        <label>Name:</label>
        <input type="text" name="name" value="{{ $bookcollection->name }}" required><br>
        <label>Description:</label>
        <textarea name="description">{{ $bookcollection->description }}</textarea><br>
        <label>Cover Image URL:</label>
        <input type="text" name="cover_image" value="{{ $bookcollection->cover_image }}"><br>
        <button type="submit">Update</button>
    </form>
@endsection
