@extends('layouts.app')
@section('title', 'Bookmarks')
@section('content')
<div class="card">
    <div class="card-header pb-0">
        <h6>Bookmarked Books</h6>
    </div>
    <div class="card-body">
        <table class="table align-items-center mb-0">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Member</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookmarks as $bookmark)
                <tr>
                    <td>{{ $bookmark->buku->title ?? '-' }}</td>
                    <td>{{ $bookmark->member->name ?? '-' }}</td>
                    <td>
                        <form action="{{ route('bookmarks.destroy', $bookmark) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Remove bookmark?')">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">{{ $bookmarks->links() }}</div>
    </div>
</div>
@endsection
