@extends('layouts.app')
@section('title', 'Add Member')
@section('content')
<div class="card">
    <div class="card-header pb-0"><h6>Add Member</h6></div>
    <div class="card-body">
        <form action="{{ route('members.store') }}" method="POST">
            @csrf
            @include('vendor.argon.members.form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('members.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
