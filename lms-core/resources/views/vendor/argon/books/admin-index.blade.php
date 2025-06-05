@extends('layouts.app')

@section('title', 'Admin Books Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header with Book Library Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-md-8">
                            <h2 class="text-white mb-0 font-weight-bolder">Admin Book Management</h2>
                            <p class="text-white opacity-8 mb-0 text-sm">Manage your collection of books with advanced tools</p>
                        </div>
                        <div class="col-lg-4 col-md-4 text-end">
                            <button type="button" class="btn btn-icon btn-sm btn-success me-2" data-bs-toggle="modal" data-bs-target="#createBookModal" title="Add New Book">
                                <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                <span class="btn-inner--text">Add Book</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Books -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card overflow-hidden">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Books</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalBooks }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-book text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Available Books -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card overflow-hidden">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Available Books</p>
                                <h5 class="font-weight-bolder mb-0">{{ $availableBooks }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="fas fa-check-circle text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Borrowed Books -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card overflow-hidden">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Borrowed Books</p>
                                <h5 class="font-weight-bolder mb-0">{{ $borrowedBooks }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                <i class="fas fa-hand-holding text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Categories -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card overflow-hidden">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Categories</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalCategories }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="fas fa-tags text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Book Table -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-md-8">
                            <h6 class="mb-1">Manage Books</h6>
                            <p class="text-sm text-muted mb-0">View, edit and manage your book collection</p>
                        </div>
                        <!-- Search and Filter Form -->
                        <div class="col-lg-6 col-md-4 d-flex align-items-center justify-content-end">
                            <form action="{{ route('admin.books.index') }}" method="GET" class="d-flex flex-wrap align-items-center w-100 justify-content-end" id="search-form">
                                @if(request('view'))
                                    <input type="hidden" name="view" value="{{ request('view') }}">
                                @endif
                                <div class="me-2 mb-2 mb-md-0">
                                    <select name="category" class="form-select form-select-sm shadow-none" id="categoryFilter">
                                        <option value="">All Categories</option>
                                        @foreach($categories ?? [] as $category)
                                            <option value="{{ $category->category_id }}" {{ request('category') == $category->category_id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="me-2 mb-2 mb-md-0">
                                    <select name="availability" class="form-select form-select-sm shadow-none" id="availabilityFilter">
                                        <option value="">All Books</option>
                                        <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available Only</option>
                                        <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Unavailable Only</option>
                                    </select>
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" class="form-control shadow-none" placeholder="Search books..." value="{{ request('search') }}" id="searchInput">
                                    @if(request('search') || request('category') || request('availability'))
                                        <a href="{{ route('admin.books.index') }}{{ request('view') ? '?view='.request('view') : '' }}" class="btn btn-outline-secondary mb-0" aria-label="Clear filters">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-3 pb-2">
                    <div id="book-table-view" class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Book</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Stock</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($books as $book)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <div class="avatar avatar-sm me-3 bg-gradient-primary shadow-primary border-radius-md">
                                                    @if($book->cover_img)
                                                        <img src="{{ asset('storage/' . $book->cover_img) }}" alt="book" class="w-100 h-100 object-cover">
                                                    @else
                                                        <i class="fas fa-book text-white p-2"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ Str::limit($book->title, 40) }}</h6>
                                                <p class="text-xs text-secondary mb-0">by {{ $book->author }}</p>
                                                <p class="text-xs text-muted mb-0">ISBN: {{ $book->isbn ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $book->category->category_name ?? '-' }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <div class="me-3">
                                                <p class="text-xs font-weight-bold mb-0">Total</p>
                                                <h6 class="text-sm mb-0">{{ $book->total_stock }}</h6>
                                            </div>
                                            <div class="me-3">
                                                <p class="text-xs font-weight-bold mb-0">Borrowed</p>
                                                <h6 class="text-sm mb-0">{{ $book->borrowed_qty }}</h6>
                                            </div>
                                            <div>
                                                <p class="text-xs font-weight-bold mb-0">Available</p>
                                                <h6 class="text-sm mb-0">{{ $book->available_qty }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <span class="badge badge-sm {{ $book->available_qty > 0 ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                            {{ $book->available_qty > 0 ? 'Available' : 'Unavailable' }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="ms-auto text-end">
                                            <a href="{{ route('books.show', $book->book_id) }}" class="btn btn-link text-dark px-3 mb-0" title="View Details">
                                                <i class="fas fa-eye text-dark me-2"></i>View
                                            </a>
                                            <a href="{{ route('books.edit', $book->book_id) }}" class="btn btn-link text-primary px-3 mb-0" title="Edit Book">
                                                <i class="fas fa-pencil-alt text-primary me-2"></i>Edit
                                            </a>
                                            <form action="{{ route('books.destroy', $book->book_id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger px-3 mb-0" title="Delete Book" onclick="return confirm('Are you sure you want to delete this book?')">
                                                    <i class="fas fa-trash text-danger me-2"></i>Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-book fa-4x text-secondary mb-3"></i>
                                            <h4>No Books Found</h4>
                                            @if(request('search') || request('category') || request('availability'))
                                                <p class="text-muted mb-3">No books match your current filters. Try adjusting your search criteria.</p>
                                                <a href="{{ route('admin.books.index') }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-undo me-1"></i> Clear Filters
                                                </a>
                                            @else
                                                <p class="text-muted mb-3">Your library doesn't have any books yet.</p>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createBookModal">
                                                    <i class="fas fa-plus me-1"></i> Add First Book
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('vendor.argon.books._create_modal')

@push('js')
<script>
    // Debounce function to limit how often a function can be called
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    // When document is ready
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('search-form');
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const availabilityFilter = document.getElementById('availabilityFilter');
        
        // Set up loading indicator (very lightweight)
        const loadingIndicator = document.createElement('div');
        loadingIndicator.className = 'position-fixed top-0 end-0 p-3';
        loadingIndicator.style.zIndex = '1050';
        loadingIndicator.style.display = 'none';
        loadingIndicator.innerHTML = `
            <div class="toast align-items-center bg-light border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <div class="d-flex align-items-center">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                            <span>Loading results...</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(loadingIndicator);

        // Create a debounced search function that only triggers after 500ms of inactivity
        const debouncedSearch = debounce(function() {
            // Only search if there are at least 3 characters or the search field is empty
            if (searchInput.value.length >= 3 || searchInput.value.length === 0) {
                loadingIndicator.style.display = 'block';
                searchForm.submit();
            }
        }, 500);

        // Listen for search input changes
        if (searchInput) {
            searchInput.addEventListener('input', debouncedSearch);
        }

        // Listen for select changes (immediately submit)
        if (categoryFilter) {
            categoryFilter.addEventListener('change', function() {
                loadingIndicator.style.display = 'block';
                searchForm.submit();
            });
        }

        if (availabilityFilter) {
            availabilityFilter.addEventListener('change', function() {
                loadingIndicator.style.display = 'block';
                searchForm.submit();
            });
        }
    });
</script>
@endpush
@endsection
