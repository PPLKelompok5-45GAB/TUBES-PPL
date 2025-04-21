@extends('layouts.app')
@section('title', 'Book Details')
@section('content')
<div class="card">
    <div class="card-header pb-0"><h6>Book Details</h6></div>
    <div class="card-body">
        <div class="mb-2"><strong>Title:</strong> {{ $book->title }}</div>
        <div class="mb-2"><strong>Author:</strong> {{ $book->author }}</div>
        <div class="mb-2"><strong>ISBN:</strong> {{ $book->isbn }}</div>
        <div class="mb-2"><strong>Category:</strong> {{ $book->kategori->name ?? '-' }}</div>
        <div class="mb-2"><strong>Publication Year:</strong> {{ $book->publication_year }}</div>
        <div class="mb-2"><strong>Publisher:</strong> {{ $book->publisher }}</div>
        <div class="mb-2"><strong>Total Stock:</strong> {{ $book->total_stock }}</div>
        <div class="mb-2"><strong>Borrowed Qty:</strong> {{ $book->borrowed_qty }}</div>
        <div class="mb-2"><strong>Available Qty:</strong> {{ $book->available_qty }}</div>
        <a href="{{ route('books.edit', $book->book_id) }}" class="btn btn-warning btn-sm">Edit</a>
        <a href="{{ route('books.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
</div>
@endsection
