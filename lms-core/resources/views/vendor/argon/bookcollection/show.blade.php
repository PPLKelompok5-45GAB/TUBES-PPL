@extends('vendor.argon.layout')
@section('content')
    <h1>{{ $bookcollection->name }}</h1>
    <p>{{ $bookcollection->description }}</p>
    <img src="{{ $bookcollection->cover_image }}" alt="Cover Image" style="max-width:200px;">
    <a href="{{ route('bookcollection.edit', $bookcollection) }}">Edit</a>
    <form method="POST" action="{{ route('bookcollection.destroy', $bookcollection) }}" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>
    <a href="{{ route('bookcollection.index') }}">Back to list</a>
@endsection
