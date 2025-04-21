@extends('layouts.app')
@section('title', 'Edit Member')
@section('content')
<div class="card">
    <div class="card-header pb-0"><h6>Edit Member</h6></div>
    <div class="card-body">
        <form action="{{ route('members.update', $member->id) }}" method="POST">
            @csrf @method('PUT')
            @include('vendor.argon.members.form')
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('members.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
