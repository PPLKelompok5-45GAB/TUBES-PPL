@extends('layouts.app')
@section('title', 'Review Details')
@section('content')
<div class="card">
    <div class="card-header pb-0"><h6>Review Details</h6></div>
    <div class="card-body">
        <div class="mb-2"><strong>Book:</strong> {{ $review->buku->title ?? '-' }}</div>
        <div class="mb-2"><strong>Member:</strong> {{ $review->member->name ?? '-' }}</div>
        <div class="mb-2"><strong>Rating:</strong> {{ $review->rating }}</div>
        <div class="mb-2"><strong>Comment:</strong> {{ $review->review_text }}</div>
        <a href="{{ route('reviews.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
</div>
@endsection
