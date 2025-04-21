@extends('layouts.app')

@section('title', 'Books')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <h6 class="mb-0">Book List</h6>
                        <form method="GET" class="d-flex align-items-center ms-3" action="">
                            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search title, author, ISBN, category..." value="{{ request('search') }}" style="width: 220px;">
                            <select name="category_id" class="form-select form-select-sm me-2" style="width: 160px;">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
                            @if(request('search') || request('category_id'))
                                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Reset</a>
                            @endif
                        </form>
                    </div>
                    <a href="{{ route('books.create') }}" class="btn btn-primary btn-sm mt-2 mt-md-0">Add Book</a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Borrowed</th>
                                    <th>Available</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($books as $book)
                                <tr>
                                    <td>{{ $loop->iteration + ($books->currentPage() - 1) * $books->perPage() }}</td>
                                    <td>{{ $book->title }}</td>
                                    <td>{{ $book->author }}</td>
                                    <td>{{ $book->category->name ?? '-' }}</td>
                                    <td>{{ $book->total_stock }}</td>
                                    <td>{{ $book->borrowed_qty }}</td>
                                    <td>{{ $book->available_qty }}</td>
                                    <td>
                                        <a href="{{ route('books.show', $book->book_id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('books.edit', $book->book_id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('books.destroy', $book->book_id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this book?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No books found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
