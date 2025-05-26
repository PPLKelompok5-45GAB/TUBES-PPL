@extends('layouts.app')
@section('title', 'Announcement Details')
@section('content')
<div class="card">
    <div class="card-header pb-0"><h6>Announcement Details</h6></div>
    <div class="card-body">
        <h5>{{ $announcement->title }}</h5>
        <div class="mb-2"><strong>Status:</strong> <span class="badge bg-{{ $announcement->status == 'published' ? 'success' : 'secondary' }}">{{ ucfirst($announcement->status) }}</span></div>
        <div class="mb-2"><strong>Created:</strong> {{ $announcement->created_at->format('Y-m-d H:i') }}</div>
        <div class="mb-4"><strong>Content:</strong><br>{{ $announcement->content }}</div>
        <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-warning btn-sm">Edit</a>
        <a href="{{ route('announcements.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
</div>
@endsection
