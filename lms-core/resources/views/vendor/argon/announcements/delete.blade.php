@extends('layouts.app')
@section('title', 'Delete Announcement')
@section('content')
<div class="card">
    <div class="card-header pb-0"><h6>Delete Announcement</h6></div>
    <div class="card-body">
        <p>Are you sure you want to delete the announcement <strong>{{ $announcement->title }}</strong>?</p>
        <form action="{{ route('announcements.destroy', $announcement) }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="{{ route('announcements.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
