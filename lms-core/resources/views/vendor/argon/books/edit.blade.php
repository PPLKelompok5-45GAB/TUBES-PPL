@extends('layouts.app')
@section('title', 'Edit Book')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0"><h6>Edit Book</h6></div>
                <div class="card-body">
                    <form action="{{ route('books.update', $book->book_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        @include('vendor.argon.books.form', ['book' => $book])
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
