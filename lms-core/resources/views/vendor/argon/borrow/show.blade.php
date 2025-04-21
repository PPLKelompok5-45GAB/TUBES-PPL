@extends('layouts.app')
@section('title', 'Borrow Entry Details')
@section('content')
<div class="card">
    <div class="card-header pb-0"><h6>Borrow Entry Details</h6></div>
    <div class="card-body">
        <div class="mb-2"><strong>Book:</strong> {{ $borrow->buku->title ?? '-' }}</div>
        <div class="mb-2"><strong>Member:</strong> {{ $borrow->member->name ?? '-' }}</div>
        <div class="mb-2"><strong>Borrow Date:</strong> {{ $borrow->borrow_date }}</div>
        <div class="mb-2"><strong>Due Date:</strong> {{ $borrow->due_date }}</div>
        <div class="mb-2"><strong>Return Date:</strong> {{ $borrow->return_date ?? '-' }}</div>
        <div class="mb-2"><strong>Status:</strong> {{ ucfirst($borrow->status) }}</div>
        <a href="{{ route('borrow.edit', $borrow) }}" class="btn btn-warning btn-sm">Edit</a>
        <a href="{{ route('borrow.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
</div>
@endsection
