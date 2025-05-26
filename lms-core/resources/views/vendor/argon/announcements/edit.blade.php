@extends('layouts.app')
@section('title', 'Edit Announcement')
@section('content')
<div class="card">
    <div class="card-header pb-0"><h6>Edit Announcement</h6></div>
    <div class="card-body">
        <form action="{{ route('announcements.update', $announcement) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ $announcement->title }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="4" required>{{ $announcement->content }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="draft" @if($announcement->status=='draft')selected @endif>Draft</option>
                    <option value="published" @if($announcement->status=='published')selected @endif>Published</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('announcements.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
