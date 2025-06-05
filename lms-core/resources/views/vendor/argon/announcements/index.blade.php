@extends('layouts.app')
@section('title', 'Announcements')
@section('content')
<div class="card">
    <div class="card-header pb-0">
        <h6>Announcements</h6>
        <a href="{{ route('announcements.create') }}" class="btn btn-primary btn-sm float-end">Add Announcement</a>
    </div>
    <div class="card-body">
        <table class="table align-items-center mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($announcements as $announcement)
                <tr>
                    <td>{{ $announcement->title }}</td>
                    <td><span class="badge bg-{{ $announcement->status == 'published' ? 'success' : 'secondary' }}">{{ ucfirst($announcement->status) }}</span></td>
                    <td>{{ $announcement->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this announcement?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">{{ $announcements->links() }}</div>
    </div>
</div>
@endsection
