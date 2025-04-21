@extends('vendor.argon.layout')
@section('content')
    <h1>Create Book Collection</h1>
    <form method="POST" action="{{ route('bookcollection.store') }}">
        @csrf
        <label>Name:</label>
        <input type="text" name="name" required><br>
        <label>Description:</label>
        <textarea name="description"></textarea><br>
        <label>Cover Image URL:</label>
        <input type="text" name="cover_image"><br>
        <button type="submit">Create</button>
    </form>
@endsection
