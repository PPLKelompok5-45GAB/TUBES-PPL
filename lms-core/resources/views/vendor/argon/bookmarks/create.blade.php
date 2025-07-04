@extends('layouts.app')
@section('title', 'Add Bookmark')
@section('content')
<div class="card">
    <div class="card-header pb-0"><h6>Add Bookmark</h6></div>
    <div class="card-body">
        <form action="{{ route('bookmarks.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Book</label>
                <select name="book_id" class="form-control" required>
                    @foreach($books as $book)
                        <option value="{{ $book->book_id }}">{{ $book->title }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('bookmarks.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
