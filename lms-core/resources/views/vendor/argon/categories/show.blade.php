@extends('layouts.app')
@section('title', 'Category Details')
@section('content')
<div class="card">
    <div class="card-header pb-0"><h6>Category Details</h6></div>
    <div class="card-body">
        <div class="mb-2"><strong>Name:</strong> {{ $category->category_name }}</div>
        <a href="{{ route('categories.edit', $category->category_id) }}" class="btn btn-warning btn-sm">Edit</a>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
</div>
@endsection
