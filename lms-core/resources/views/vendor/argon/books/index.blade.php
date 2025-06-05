@extends('layouts.app')

@section('title', 'Books Library')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header with Book Library Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-md-8">
                            <h2 class="text-white mb-0 font-weight-bolder">Book Library</h2>
                            <p class="text-white opacity-8 mb-0 text-sm">Manage your collection of books</p>
                        </div>
                        <div class="col-lg-4 col-md-4 text-end">
                            @if(auth()->user()->role === 'Admin')
                                <button type="button" class="btn btn-icon btn-sm btn-success me-2" data-bs-toggle="modal" data-bs-target="#createBookModal" title="Add New Book">
                                    <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                                    <span class="btn-inner--text">Add Book</span>
                                </button>
                                <a href="{{ route('books.index') }}?view=gallery" class="btn btn-icon btn-sm btn-dark" title="Toggle View">
                                    <span class="btn-inner--icon"><i class="fas fa-th-large"></i></span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if(auth()->user()->role === 'Admin' && isset($totalBooks))
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
                                <h5 class="font-weight-bolder mb-0">{{ $availableBooks ?? 0 }}</h5>
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
                                <h5 class="font-weight-bolder mb-0">{{ $borrowedBooks ?? 0 }}</h5>
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
                                <h5 class="font-weight-bolder mb-0">{{ $totalCategories ?? 0 }}</h5>
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
    @endif
    
    <!-- Book List/Grid -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-md-8">
                            <h6 class="mb-1">{{ auth()->user()->role === 'Admin' ? 'Manage Books' : 'Explore Books' }}</h6>
                            <p class="text-sm text-muted mb-0">{{ auth()->user()->role === 'Admin' ? 'View, edit and manage your book collection' : 'Discover books available in our library' }}</p>
                        </div>
                        <!-- Search and Filter Form -->
                        <div class="col-lg-6 col-md-4 d-flex align-items-center justify-content-end">
                            <form action="{{ route('books.index') }}" method="GET" class="d-flex flex-wrap align-items-center w-100 justify-content-end" id="search-form">
                                @if(request('view'))
                                    <input type="hidden" name="view" value="{{ request('view') }}">
                                @endif
                                @if(auth()->user()->role === 'Admin')
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
                                @endif
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" class="form-control shadow-none" placeholder="Search books..." value="{{ request('search') }}" id="searchInput">
                                    @if(request('search') || request('category') || request('availability'))
                                        <a href="{{ route('books.index') }}{{ request('view') ? '?view='.request('view') : '' }}" class="btn btn-outline-secondary mb-0" aria-label="Clear filters">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-3 pb-2">
                    @if(auth()->user()->role === 'Admin')
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
                                                <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-undo me-1"></i> Clear Filters
                                                </a>
                                            @else
                                                <p class="text-muted mb-3">Your library doesn't have any books yet.</p>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-create-book">
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
                    @else
                    <!-- Gallery View -->
                    <div class="row px-3">
                            @forelse ($books as $book)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="card mb-0">
                                    <a href="{{ route('books.show', $book->book_id) }}" class="d-block">
                                        <div class="position-relative" style="height: 160px; background-color: #f8f9fa;">
                                            @if ($book->image)
                                                <img src="{{ asset('storage/'.$book->image) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $book->title }}">
                                            @else
                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                                    <i class="fas fa-book fa-3x text-secondary"></i>
                                                </div>
                                            @endif
                                            <span class="badge {{ $book->available_qty > 0 ? 'bg-gradient-success' : 'bg-gradient-secondary' }} position-absolute top-0 end-0 mt-2 me-2">
                                                {{ $book->available_qty > 0 ? 'Available' : 'Unavailable' }}
                                            </span>
                                        </div>
                                    </a>
                                    <div class="card-body pt-2">
                                        <div class="text-center mt-n5">
                                            <h5 class="font-weight-bolder">
                                                <a href="{{ route('books.show', $book->book_id) }}" class="text-dark">{{ Str::limit($book->title, 40) }}</a>
                                            </h5>
                                            <p class="text-sm text-secondary mb-1">by {{ $book->author }}</p>
                                            <p class="text-xs mb-0">Category: {{ $book->kategori->category_name ?? '-' }}</p>
                                        </div>
                                        <div class="mt-3">
                                            <p class="text-sm text-muted mb-2 text-truncate">
                                                {{ $book->synopsis ? Str::limit($book->synopsis, 80) : 'No synopsis available' }}
                                            </p>
                                        </div>
                                        <div class="text-center mt-3">
                                            <a href="{{ route('books.show', $book->book_id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-book fa-4x text-secondary mb-3"></i>
                                        <h4>No Books Found</h4>
                                        @if(request('search') || request('category') || request('availability'))
                                            <p class="text-muted mb-3">No books match your current filters. Try adjusting your search criteria.</p>
                                            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-undo me-1"></i> Clear Filters
                                            </a>
                                        @else
                                            <p class="text-muted mb-3">Your library doesn't have any books yet.</p>
                                            @if(auth()->user()->role === 'Admin')
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-create-book">
                                                    <i class="fas fa-plus me-1"></i> Add First Book
                                                </button>
                                            @else
                                                <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-home me-1"></i> Return to Dashboard
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforelse
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $books->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'Admin')
    @include('vendor.argon.books._create_modal')
@endif

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
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                        <span>Loading...</span>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(loadingIndicator);

        // Handle search input changes with debounce
        if (searchInput) {
            searchInput.addEventListener('input', debounce(function(e) {
                if (e.target.value.length === 0 || e.target.value.length >= 3) {
                    loadingIndicator.style.display = 'block';
                    searchForm.submit();
                }
            }, 500));
        }

        // Handle filter changes with immediate response
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

        // Add loading state on form submit
        if (searchForm) {
            searchForm.addEventListener('submit', function() {
                loadingIndicator.style.display = 'block';
            });
        }
    });
</script>
@endpush
@endsection

@push('scripts')
<!-- No toggle script needed since view is split by role -->
@endpush
