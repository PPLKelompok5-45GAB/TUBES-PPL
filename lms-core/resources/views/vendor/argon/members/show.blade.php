@extends('layouts.app')
@section('title', 'Member Details')
@section('content')
<div class="card">
    <div class="card-header pb-0"><h6>Member Details</h6></div>
    <div class="card-body">
        <div class="mb-2"><strong>Name:</strong> {{ $member->name }}</div>
        <div class="mb-2"><strong>Email:</strong> {{ $member->email }}</div>
        <div class="mb-2"><strong>Phone:</strong> {{ $member->phone }}</div>
        <div class="mb-2"><strong>Address:</strong> {{ $member->address }}</div>
        <div class="mb-2"><strong>Status:</strong> {{ ucfirst($member->status) }}</div>
        <div class="mb-2"><strong>Membership Date:</strong> {{ $member->membership_date }}</div>
        <div class="mb-2"><strong>Borrow History:</strong></div>
        <ul>
            @foreach($member->logPinjams as $borrow)
                <li>{{ $borrow->buku->title ?? '-' }} ({{ $borrow->borrow_date }}) - {{ ucfirst($borrow->status) }}</li>
            @endforeach
        </ul>
        <a href="{{ route('members.edit', $member) }}" class="btn btn-warning btn-sm">Edit</a>
        <a href="{{ route('members.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
</div>
@endsection
