@extends('layouts.app')
@section('title', $category->category_name . ' - Category Details')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header with Category Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary shadow-lg border-0">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="text-white">
                                <h2 class="mb-0 text-white font-weight-bold">{{ $category->category_name }}</h2>
                                <p class="text-white opacity-8 mb-0">Category Details and Statistics</p>
                            </div>
                        </div>
                        <div class="col-lg-4 text-right d-flex justify-content-end">
                            <a href="{{ route('categories.edit', $category->category_id) }}" class="btn btn-icon btn-warning btn-sm me-2">
                                <span class="btn-inner--icon"><i class="fas fa-edit"></i></span>
                                <span class="btn-inner--text">Edit</span>
                            </a>
                            <a href="{{ route('categories.index') }}" class="btn btn-icon btn-secondary btn-sm">
                                <span class="btn-inner--icon"><i class="fas fa-arrow-left"></i></span>
                                <span class="btn-inner--text">Back</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Category Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card overflow-hidden">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Books</p>
                                <h2 class="font-weight-bolder mb-0">{{ $totalBooks }}</h2>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                <i class="fas fa-book text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card overflow-hidden">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Available</p>
                                <h2 class="font-weight-bolder mb-0">{{ $totalAvailableQty }}</h2>
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
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card overflow-hidden">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Borrowed</p>
                                <h2 class="font-weight-bolder mb-0">{{ $totalBorrowedQty }}</h2>
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
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card overflow-hidden">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Availability</p>
                                <h2 class="font-weight-bolder mb-0">{{ $availabilityRatio }}%</h2>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="fas fa-chart-pie text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Popular Books & Recent Additions -->
    <div class="row mb-4">
        <!-- Popular Books in this Category -->
        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card card-background card-background-mask-primary h-100">
                <div class="full-background" style="background-image: url('{{ asset('assets/img/curved-images/white-curved.jpeg') }}');"></div>
                <div class="card-header pb-0 pt-3 text-center">
                    <h5 class="text-white mb-0">Most Popular Books</h5>
                    <p class="text-sm text-white opacity-8 mb-3">Books with the most borrows in this category</p>
                </div>
                <div class="card-body p-3">
                    @if($popularBooks->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <tbody>
                                    @foreach($popularBooks as $book)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="text-white mb-0 text-sm">{{ Str::limit($book->title, 30) }}</h6>
                                                        <p class="text-white text-xs opacity-7 mb-0">by {{ $book->author }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="badge badge-sm bg-gradient-info">{{ $book->borrow_count }} borrows</span>
                                            </td>
                                            <td class="align-middle text-end">
                                                <a href="{{ route('books.show', $book->book_id) }}" class="btn btn-link text-white px-3 mb-0">
                                                    <i class="fas fa-eye text-white me-2"></i>View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state text-center py-4">
                            <i class="fas fa-chart-line fa-4x text-white mb-3 opacity-8"></i>
                            <h4 class="text-white">No Popularity Data Yet</h4>
                            <p class="text-white opacity-8 mb-3">Books in this category haven't been borrowed yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Recent Books in this Category -->
        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0 pt-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-0">Recent Additions</h6>
                            <p class="text-sm text-muted mb-0">Latest books added to this category</p>
                        </div>
                        @if($recentBooks->count() > 0)
                            <a href="{{ auth()->user()->role === 'Admin' ? route('admin.books.index') : route('member.books.index') }}?category={{ $category->category_id }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-book me-1"></i> View All
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body p-3">
                    @if($recentBooks->count() > 0)
                        <div class="timeline timeline-one-side">
                            @foreach($recentBooks as $book)
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="fas fa-book {{ $book->available_qty > 0 ? 'text-success' : 'text-secondary' }}"></i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">{{ $book->title }}</h6>
                                        <p class="text-secondary text-xs mt-1 mb-0">by {{ $book->author }}</p>
                                        <div class="mt-2 d-flex">
                                            <span class="badge badge-sm {{ $book->available_qty > 0 ? 'bg-gradient-success' : 'bg-gradient-secondary' }} me-2">
                                                {{ $book->available_qty > 0 ? 'Available' : 'Unavailable' }}
                                            </span>
                                            <a href="{{ route('books.show', $book->book_id) }}" class="btn btn-link text-primary text-sm mb-0 px-0 ms-auto">
                                                View Details <i class="fas fa-arrow-right text-xs ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state text-center py-4">
                            <i class="fas fa-clock fa-4x text-secondary mb-3"></i>
                            <h4>No Recent Additions</h4>
                            <p class="text-muted mb-3">No books have been added to this category recently.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- All Books in this Category -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-md-8">
                            <h6 class="mb-1">Books in {{ $category->category_name }}</h6>
                            <p class="text-sm text-muted mb-0">Browse all books in this category</p>
                        </div>
                        
                        <!-- Search and Filter Form -->
                        <div class="col-lg-6 col-md-4 d-flex align-items-center justify-content-end">
                            <form action="{{ route('categories.show', $category) }}" method="GET" class="d-flex flex-wrap align-items-center w-100 justify-content-end" id="search-form">
                                <div class="me-2 mb-2 mb-md-0">
                                    <select name="availability" class="form-select form-select-sm shadow-none" id="availabilityFilter">
                                        <option value="">All Books</option>
                                        <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available Only</option>
                                        <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Unavailable Only</option>
                                    </select>
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" class="form-control shadow-none" placeholder="Search by title, author, ISBN..." value="{{ request('search') }}" id="searchInput">
                                    @if(request('search') || request('availability'))
                                        <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-secondary mb-0" aria-label="Clear filters">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-3 pb-2">
                    @if($books->count() > 0)
                        <!-- Book Grid Display -->
                        <div class="row px-3">
                            @foreach($books as $book)
                                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                    <div class="card card-profile card-plain h-100">
                                        <div class="card-header p-0 position-relative mx-3 mt-n4 z-index-2">
                                            <a href="{{ route('books.show', $book->book_id) }}" class="d-block">
                                                <div class="shadow-xl border-radius-xl overflow-hidden" style="height: 200px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                                    @if($book->cover_img)
                                                        <img src="{{ asset('storage/' . $book->cover_img) }}" class="w-100 h-100 object-fit-cover" alt="{{ $book->title }}" style="object-fit: cover;">
                                                    @else
                                                        <div class="text-center py-5 w-100">
                                                            <i class="fas fa-book fa-4x text-gradient text-primary opacity-8"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </a>
                                            <span class="badge {{ $book->available_qty > 0 ? 'bg-gradient-success' : 'bg-gradient-secondary' }} position-absolute top-0 end-0 mt-2 me-2">
                                                {{ $book->available_qty > 0 ? 'Available' : 'Unavailable' }}
                                            </span>
                                        </div>
                                        <div class="card-body pt-2">
                                            <div class="text-center mt-n5">
                                                <h5 class="font-weight-bolder">
                                                    <a href="{{ route('books.show', $book->book_id) }}" class="text-dark">{{ Str::limit($book->title, 40) }}</a>
                                                </h5>
                                                <p class="text-sm text-secondary mb-1">by {{ $book->author }}</p>
                                                <p class="text-xs mb-0">ISBN: {{ $book->isbn }}</p>
                                            </div>
                                            <div class="text-center mt-3">
                                                <a href="{{ route('books.show', $book->book_id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $books->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="empty-state text-center py-5">
                            <i class="fas fa-book-open fa-4x text-secondary mb-3"></i>
                            <h4>No Books Found</h4>
                            @if(request('search') || request('availability'))
                                <p class="text-muted mb-3">No books match your search criteria. Try adjusting your filters.</p>
                                <a href="{{ route('categories.show', $category) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-undo me-1"></i> Clear Filters
                                </a>
                            @else
                                <p class="text-muted mb-3">There are no books in this category yet.</p>
                                <a href="{{ route('books.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-1"></i> Add Book
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add debounce functionality for search input
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const availabilityFilter = document.getElementById('availabilityFilter');
        const searchForm = document.getElementById('search-form');
        
        if (searchInput && searchForm) {
            function debounce(func, wait) {
                let timeout;
                return function(...args) {
                    const context = this;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }
            
            // Only submit if at least 3 characters or empty (cleared)
            const debouncedSubmit = debounce(function(e) {
                const value = searchInput.value.trim();
                if (value.length >= 3 || value.length === 0) {
                    searchForm.submit();
                }
            }, 500);
            
            searchInput.addEventListener('input', debouncedSubmit);
            
            // Submit form when filter changes
            if (availabilityFilter) {
                availabilityFilter.addEventListener('change', function() {
                    searchForm.submit();
                });
            }
            
            // Show loading indicator during form submission
            searchForm.addEventListener('submit', function() {
                // Prevent multiple submissions
                const submitButtons = searchForm.querySelectorAll('button[type="submit"]');
                submitButtons.forEach(button => {
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    button.disabled = true;
                });
            });
        }
    });
</script>
@endpush
