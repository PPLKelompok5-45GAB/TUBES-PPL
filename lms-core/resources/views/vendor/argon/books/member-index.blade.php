@extends('layouts.app')

@section('title', 'Browse Library Books')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header with Book Library Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-info shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-md-8">
                            <h2 class="text-white mb-0 font-weight-bolder">Browse Our Library</h2>
                            <p class="text-white opacity-8 mb-0 text-sm">Discover and explore our collection of books</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Book Gallery -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-md-8">
                            <h6 class="mb-1">Explore Books</h6>
                            <p class="text-sm text-muted mb-0">Discover books available in our library</p>
                        </div>
                        <!-- Search and Filter Form -->
                        <div class="col-lg-6 col-md-4 d-flex align-items-center justify-content-end">
                            <form action="{{ route('member.books.index') }}" method="GET" class="d-flex flex-wrap align-items-center w-100 justify-content-end" id="search-form">
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
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" class="form-control shadow-none" placeholder="Search books..." value="{{ request('search') }}" id="searchInput">
                                    @if(request('search') || request('category'))
                                        <a href="{{ route('member.books.index') }}{{ request('view') ? '?view='.request('view') : '' }}" class="btn btn-outline-secondary mb-0" aria-label="Clear filters">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Gallery View (Default) -->
                    <div class="row">
                        @forelse ($books as $book)
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="position-relative">
                                    <span class="badge position-absolute top-0 start-0 mt-2 ms-2 {{ $book->available_qty > 0 ? 'bg-info text-white' : 'bg-secondary text-white' }}" style="z-index: 2">
                                        {{ $book->available_qty > 0 ? 'AVAILABLE' : 'UNAVAILABLE' }}
                                    </span>
                                    <div style="height: 180px; background-color: #f8f9fa;">
                                        @if ($book->cover_img)
                                            <img src="{{ asset('storage/'.$book->cover_img) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $book->title }}">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-book fa-3x text-secondary"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <h5 class="card-title mb-1">
                                        <a href="{{ route('books.show', $book->book_id) }}" class="text-dark text-decoration-none">{{ Str::limit($book->title, 40) }}</a>
                                    </h5>
                                    <p class="text-muted mb-1">by {{ $book->author }}</p>
                                    <p class="text-muted small mb-2">Category: {{ $book->category->category_name ?? '-' }}</p>
                                    <p class="text-muted small mb-3 text-truncate">
                                        {{ $book->synopsis ? Str::limit($book->synopsis, 80) : 'No synopsis available' }}
                                    </p>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('books.show', $book->book_id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </a>
                                        @if($book->available_qty > 0)
                                        <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#borrowRequestModal-{{ $book->book_id }}">
                                            <i class="fas fa-book-reader me-1"></i> Borrow
                                        </button>
                                        @endif
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
                                    @if(request('search') || request('category'))
                                        <p class="text-muted mb-3">No books match your current filters. Try adjusting your search criteria.</p>
                                        <a href="{{ route('member.books.index') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-undo me-1"></i> Clear Filters
                                        </a>
                                    @else
                                        <p class="text-muted mb-3">There are currently no books in the library collection.</p>
                                        <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-home me-1"></i> Return to Dashboard
                                        </a>
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
            </div>
        </div>
    </div>
</div>

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
        
        // Set up loading indicator
        const loadingIndicator = document.createElement('div');
        loadingIndicator.className = 'position-fixed top-0 end-0 p-3';
        loadingIndicator.style.zIndex = '1050';
        loadingIndicator.style.display = 'none';
        loadingIndicator.innerHTML = `
            <div class="toast align-items-center bg-light border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <div class="d-flex align-items-center">
                            <div class="spinner-border spinner-border-sm text-info me-2" role="status"></div>
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
    });
</script>
@endpush
<!-- Borrow Request Modals -->
@foreach($books as $book)
<div class="modal fade" id="borrowRequestModal-{{ $book->book_id }}" tabindex="-1" aria-labelledby="borrowRequestModalLabel-{{ $book->book_id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
          <h5 class="modal-title" id="borrowRequestModalLabel-{{ $book->book_id }}"><i class="fas fa-book-reader me-2"></i>Borrow Request</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{ route('borrow.request', $book) }}" id="borrowRequestForm-{{ $book->book_id }}">
        @csrf
        <input type="hidden" name="book_id" value="{{ $book->book_id }}">
        <div class="modal-body">
          <!-- Book Details Alert -->
          <div class="alert alert-info mb-3">
            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Book Details</h6>
            <p class="mb-1"><strong>Title:</strong> {{ $book->title }}</p>
            <p class="mb-1"><strong>Author:</strong> {{ $book->author }}</p>
            <p class="mb-0"><strong>Available Copies:</strong> {{ $book->available_qty }}</p>
          </div>
          
          @php
            $member = auth()->check() && auth()->user()->role === 'Member' ? auth()->user()->member : null;
            $hasExistingBorrow = $member ? \App\Models\Log_Pinjam_Buku::where('book_id', $book->book_id)
                ->where('member_id', $member->member_id)
                ->whereIn('status', ['pending', 'approved', 'overdue'])
                ->exists() : false;
          @endphp
          
          <!-- Validation Alerts -->
          @if($hasExistingBorrow)
          <div class="alert alert-warning mb-3">
            <i class="fas fa-exclamation-circle me-2"></i>
            You already have an active borrow request or loan for this book.
          </div>
          @endif
          
          @if(auth()->user()->role === 'Admin')
          <div class="mb-3">
            <label for="member_id-{{ $book->book_id }}" class="form-label">Member</label>
            <select class="form-select" id="member_id-{{ $book->book_id }}" name="member_id" required>
              <option value="">Select Member</option>
              @foreach(\App\Models\Member::all() as $memberOption)
                <option value="{{ $memberOption->member_id }}">{{ $memberOption->name }}</option>
              @endforeach
            </select>
          </div>
          @else
          <input type="hidden" name="member_id" value="{{ $member ? $member->member_id : '' }}">
          @endif
          <div class="mb-3">
            <label for="borrow_date-{{ $book->book_id }}" class="form-label">Borrow Date</label>
            <input type="date" class="form-control" id="borrow_date-{{ $book->book_id }}" name="borrow_date" value="{{ old('borrow_date', now()->toDateString()) }}" required>
          </div>
          <div class="mb-3">
            <label for="due_date-{{ $book->book_id }}" class="form-label">Due Date</label>
            <input type="date" class="form-control" id="due_date-{{ $book->book_id }}" name="due_date" value="{{ old('due_date', now()->addDays(14)->toDateString()) }}" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning text-white" {{ $hasExistingBorrow ? 'disabled' : '' }}>
            <i class="fas fa-paper-plane me-1"></i> Submit Request
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

@endsection
