@extends('layouts.app')

@section('title', 'Book Collections')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <h6 class="mb-0">Book Collections</h6>
                    <a href="{{ route('bookcollection.create') }}" class="btn btn-primary btn-sm mt-2 mt-md-0">Create New Collection</a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collections as $collection)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $collection->name }}</td>
                                    <td>{{ Str::limit($collection->description, 50) }}</td>
                                    <td>
                                        <a href="{{ route('bookcollection.show', $collection) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('bookcollection.edit', $collection) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('bookcollection.destroy', $collection) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this collection?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No collections found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
