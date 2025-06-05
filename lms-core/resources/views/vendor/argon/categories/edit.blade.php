@extends('layouts.app')
@section('title', 'Edit Category')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0"><h6>Edit Category</h6></div>
                <div class="card-body">
                    <form action="{{ route('categories.update', $category->category_id) }}" method="POST">
                        @csrf @method('PUT')
                        @include('vendor.argon.categories.form')
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
