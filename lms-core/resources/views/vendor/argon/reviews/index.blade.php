@extends('layouts.app')
@section('title', 'Reviews')
@section('content')
<div class="card">
    <div class="card-header pb-0">
        <h6>Book Reviews</h6>
    </div>
    <div class="card-body">
        <table class="table align-items-center mb-0">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Member</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                <tr>
                    <td>{{ $review->buku->title ?? '-' }}</td>
                    <td>{{ $review->member->name ?? '-' }}</td>
                    <td>{{ $review->rating }}</td>
                    <td>{{ $review->comment }}</td>
                    <td>
                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Remove review?')">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">{{ $reviews->links() }}</div>
    </div>
</div>
@endsection
