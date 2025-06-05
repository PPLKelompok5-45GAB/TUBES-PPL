@extends('layouts.app')
@section('title', 'Edit Wishlist')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0"><h6>Edit Wishlist</h6></div>
                <div class="card-body">
                    <form action="{{ route('wishlists.update', $wishlist) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Book</label>
                            <select name="book_id" class="form-control" required>
                                @foreach($books as $book)
                                    <option value="{{ $book->book_id }}" @if($wishlist->book_id == $book->book_id) selected @endif>{{ $book->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('wishlists.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
