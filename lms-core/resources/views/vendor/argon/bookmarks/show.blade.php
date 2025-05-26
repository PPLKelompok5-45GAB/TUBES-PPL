@extends('layouts.app')
@section('title', 'Bookmark Details')
@section('content')
<div class="card">
    <div class="card-header pb-0"><h6>Bookmark Details</h6></div>
    <div class="card-body">
        <div class="mb-2"><strong>Book:</strong> {{ $bookmark->buku->title ?? '-' }}</div>
        <div class="mb-2"><strong>Member:</strong> {{ $bookmark->member->name ?? '-' }}</div>
        <a href="{{ route('bookmarks.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
</div>
@endsection
