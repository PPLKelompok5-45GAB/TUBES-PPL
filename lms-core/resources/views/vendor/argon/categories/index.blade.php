@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="container-fluid py-4">
    <!-- Search bar and add button - styled to match screenshot -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-danger bg-gradient shadow-sm border-0" style="background-color: #ff6347 !important;">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-md-7">
                            <form action="{{ route('categories.index') }}" method="GET" id="searchForm">
                                <div class="input-group">
                                    <input type="text" class="form-control rounded-start" placeholder="Search categories..." name="search" value="{{ request('search') }}" id="searchInput">
                                    <button class="btn btn-light px-4" type="submit">Search</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4 col-md-5 mt-md-0 mt-3 text-md-end">
                            <a href="#" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                <i class="fas fa-plus me-2"></i>Add Category
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Categories grid -->
    @if($categories->count() > 0)
    <div class="row">
        @foreach($categories as $category)
            @php
                // Get category statistics
                $totalBooks = \App\Models\Buku::where('category_id', $category->category_id)->count();
                $totalAvailableQty = \App\Models\Buku::where('category_id', $category->category_id)->sum('available_qty');
                $totalBorrowedQty = \App\Models\Buku::where('category_id', $category->category_id)->sum('borrowed_qty');
                
                // Calculate availability ratio (available vs borrowed)
                $totalQty = $totalAvailableQty + $totalBorrowedQty;
                $availabilityRatio = ($totalQty > 0) ? round(($totalAvailableQty / $totalQty) * 100) : 100;
                
                // Get 3 recent books for this category
                $recentBooks = \App\Models\Buku::where('category_id', $category->category_id)
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
                    
                // Assign a color based on category name pattern (cycling through colors)
                $categoryColors = ['info', 'danger', 'success', 'info', 'success'];
                $categoryIndex = $loop->index % count($categoryColors);
                $cardColorClass = $categoryColors[$categoryIndex];
                
                // Override colors for specific known categories to match screenshot
                $lowerCategoryName = strtolower($category->category_name);
                if (strpos($lowerCategoryName, 'fiction') !== false && strpos($lowerCategoryName, 'non') === false) {
                    $cardColorClass = 'info'; // Fiction gets teal/cyan
                } elseif (strpos($lowerCategoryName, 'history') !== false) {
                    $cardColorClass = 'danger'; // History gets red
                } elseif (strpos($lowerCategoryName, 'non-fiction') !== false) {
                    $cardColorClass = 'success'; // Non-Fiction gets green
                } elseif (strpos($lowerCategoryName, 'science') !== false) {
                    $cardColorClass = 'info'; // Science gets teal/cyan
                } elseif (strpos($lowerCategoryName, 'technology') !== false) {
                    $cardColorClass = 'success'; // Technology gets green
                }
            @endphp
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card category-card">
                    <!-- Category header with edit button -->
                    <div class="card-header bg-{{ $cardColorClass }} text-white p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 text-white">{{ $category->category_name }}</h6>
                                <p class="text-xs mb-0 opacity-8">{{ $totalBooks }} {{ Str::plural('book', $totalBooks) }}</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link text-white p-1" type="button" id="dropdownMenuButton{{ $category->category_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $category->category_id }}">
                                    <li>
                                        <a class="dropdown-item edit-category-btn" href="#" data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-category-id="{{ $category->category_id }}" data-name="{{ $category->category_name }}">
                                            <i class="fas fa-edit me-2"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item text-danger delete-btn" data-category-id="{{ $category->category_id }}" data-category-name="{{ $category->category_name }}">
                                            <i class="fas fa-trash me-2"></i>Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Category metrics -->
                    <div class="card-body py-2 px-3">
                        <div class="row text-center g-0">
                            <div class="col-6">
                                <h3 class="font-weight-bold mb-0">{{ $totalAvailableQty }}</h3>
                                <p class="text-xs text-muted mb-2">Available</p>
                            </div>
                            <div class="col-6">
                                <h3 class="font-weight-bold mb-0">{{ $totalBorrowedQty }}</h3>
                                <p class="text-xs text-muted mb-2">Borrowed</p>
                            </div>
                        </div>
                        
                        <!-- Availability indicator -->
                        <div class="d-flex justify-content-between align-items-center mt-1 mb-1">
                            <p class="text-xs mb-0">Availability</p>
                            <p class="text-xs font-weight-bold mb-0">{{ $availabilityRatio }}%</p>
                        </div>
                        <div class="progress mb-2" style="height: 4px;">
                            <div class="progress-bar bg-{{ $cardColorClass }}" role="progressbar" style="width: {{ $availabilityRatio }}%" aria-valuenow="{{ $availabilityRatio }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        
                        <!-- Recent books section -->
                        <div class="recent-books mb-2">
                            <p class="text-xs font-weight-bold mb-1">Recent Books</p>
                            <div class="d-flex">
                                @forelse($recentBooks as $book)
                                    <div class="book-indicator me-1" data-bs-toggle="tooltip" title="{{ $book->title }}">
                                        <div class="book-dot {{ $book->available_qty > 0 ? 'bg-success' : 'bg-danger' }}"></div>
                                    </div>
                                @empty
                                    <p class="text-muted text-xs">No books in this category yet</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    
                    <!-- View all books button -->
                    <div class="px-3 pb-2">
                        <a href="{{ route('categories.show', $category->category_id) }}" class="btn btn-light btn-sm w-100 text-{{ $cardColorClass }}">
                            <i class="fas fa-eye me-1"></i> View All Books
                        </a>
                    </div>
                    
                    <!-- Hidden delete form -->
                    <form action="{{ route('categories.destroy', $category->category_id) }}" method="POST" id="delete-form-{{ $category->category_id }}" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="row">
        <div class="col-12 d-flex justify-content-center mt-4">
            {{ $categories->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
    
    @else
    <!-- Empty state -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="empty-state">
                        <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                        <h4>No Categories Found</h4>
                        <p class="text-muted mb-3">{{ request('search') ? 'No categories matching "'.request('search').'".' : 'No categories have been created yet.' }}</p>
                        @if(request('search'))
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i> Back to All Categories
                            </a>
                        @else
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                <i class="fas fa-plus me-2"></i>Create Your First Category
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Confirmation Modal for Delete -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="confirmDeleteModalLabel">Confirm Category Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span>Warning: This action cannot be undone!</span>
                </div>
                <p>Are you sure you want to delete the category <strong id="deleteCategoryName"></strong>?</p>
                <p>Deleting this category may affect books assigned to it. Consider reassigning books before deletion.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Category</button>
            </div>
        </div>
    </div>
</div>

@include('vendor.argon.categories._create_modal')
@include('vendor.argon.categories._edit_modal')
@endsection

@push('styles')
<style>
/* Category card styles */
.category-card {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    margin-bottom: 1rem;
    transition: transform 0.2s;
}

.category-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.category-card .card-header {
    border-bottom: none;
    padding: 12px 15px;
}

.card-header.bg-success {
    background-color: #2ecc71 !important;
}

.card-header.bg-info {
    background-color: #00d2ff !important;
}

.card-header.bg-warning {
    background-color: #f39c12 !important;
}

.card-header.bg-danger {
    background-color: #ff3b5c !important;
}

/* Book indicator styles */
.book-indicator {
    margin-right: 5px;
}

.book-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
}

/* Recent books section */
.recent-books {
    min-height: 50px;
}

/* Progress bar styling */
.progress {
    height: 6px;
    border-radius: 3px;
    overflow: hidden;
    background-color: #f6f9fc;
}

.bg-success {
    background-color: #2dce89 !important;
}

.bg-danger {
    background-color: #f5365c !important;
}

/* Empty state styles */
.empty-state {
    padding: 40px 20px;
    text-align: center;
}

/* Responsive adjustments */
@media (max-width: 767.98px) {
    .category-card {
        margin-bottom: 20px;
    }
    
    .category-card .card-header {
        padding: 10px 15px;
    }
    
    .category-card h2 {
        font-size: 1.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Edit category modal functionality
    document.querySelectorAll('.edit-category-btn').forEach(function(btn) {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-category-id');
            const name = this.getAttribute('data-name');
            document.getElementById('edit_category_name').value = name;
            document.getElementById('edit-category-form').action = `/categories/${id}`;
        });
    });
    
    // Delete confirmation modal functionality
    const confirmDeleteModal = document.getElementById('confirmDeleteModal');
    if (confirmDeleteModal) {
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        let currentDeleteForm = null;
        
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-category-id');
                const categoryName = this.getAttribute('data-category-name');
                currentDeleteForm = document.getElementById(`delete-form-${categoryId}`);
                
                document.getElementById('deleteCategoryName').textContent = categoryName;
                new bootstrap.Modal(confirmDeleteModal).show();
            });
        });
        
        confirmDeleteBtn.addEventListener('click', function() {
            if (currentDeleteForm) {
                currentDeleteForm.submit();
            }
        });
    }
    
    // Search input debounce
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    if (searchInput && searchForm) {
        let debounceTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                searchForm.submit();
            }, 300); // Faster response time to match the screenshot example
        });
        
        // Focus the search input when the page loads if there's a search query
        if (searchInput.value) {
            searchInput.focus();
            searchInput.selectionStart = searchInput.selectionEnd = searchInput.value.length;
        }
    }
});
</script>
@endpush
