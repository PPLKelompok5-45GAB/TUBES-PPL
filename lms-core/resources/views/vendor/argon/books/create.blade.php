@extends('layouts.app')
@section('title', 'Add Book')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0"><h6>Add Book</h6></div>
                <div class="card-body">
                    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('vendor.argon.books.form')
                        <div class="form-group">
                            <label for="synopsis">Synopsis</label>
                            <textarea class="form-control" id="synopsis" name="synopsis" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('books.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
