@extends('layouts.app')
@section('title', 'Add to Wishlist')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0"><h6>Add to Wishlist</h6></div>
                <div class="card-body">
                    <form action="{{ route('wishlists.store') }}" method="POST">
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
                        <a href="{{ route('wishlists.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
