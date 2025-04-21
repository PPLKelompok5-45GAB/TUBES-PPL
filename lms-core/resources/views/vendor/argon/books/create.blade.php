@extends('layouts.app')
@section('title', 'Add Book')
@section('content')
<div class="card">
    <div class="card-header pb-0"><h6>Add Book</h6></div>
    <div class="card-body">
        <form action="{{ route('books.store') }}" method="POST">
            @csrf
            @include('vendor.argon.books.form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('books.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
