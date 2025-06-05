@extends('layouts.app')
@section('title', 'Wishlist Details')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0"><h6>Wishlist Details</h6></div>
                <div class="card-body">
                    <div class="mb-2"><strong>Book:</strong> {{ $wishlist->buku->title ?? '-' }}</div>
                    <div class="mb-2"><strong>Member:</strong> {{ $wishlist->member->name ?? '-' }}</div>
                    <a href="{{ route('wishlists.index') }}" class="btn btn-secondary btn-sm">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
