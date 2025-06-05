@extends('layouts.app')
@section('title', 'Edit Review')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0"><h6>Edit Review</h6></div>
                <div class="card-body">
                    <form action="{{ route('reviews.update', $review) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Book</label>
                            <select name="book_id" class="form-control" required>
                                @foreach($books as $book)
                                    <option value="{{ $book->book_id }}" @if($review->book_id == $book->book_id) selected @endif>{{ $book->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <input type="number" name="rating" class="form-control" min="1" max="5" value="{{ $review->rating }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Comment</label>
                            <textarea name="comment" class="form-control" rows="3">{{ $review->comment }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
