@extends('layouts.app')
@section('title', 'Book Details')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-lg border-0 position-relative" style="min-height: 380px;">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between" style="background: #f3f4f6; border-top-left-radius: 1rem; border-top-right-radius: 1rem; border-bottom: none; min-height:64px;">
                    <h5 class="mb-0 text-dark fw-bold ms-1" style="font-size: 1.6rem;">{{ $book->title }}</h5>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary d-flex align-items-center px-4 py-2"><i class="fa fa-arrow-left me-1"></i> Back</a>
                </div>
                <div class="card-body d-flex flex-column align-items-center position-relative">
                    <div class="w-100 d-flex flex-column flex-md-row align-items-start gap-4">
                        <!-- Book Image under header -->
                        <div class="text-center flex-shrink-0">
                            @if($book->image)
                                <img src="{{ asset('storage/'.$book->image) }}" alt="Book Image" class="img-fluid rounded shadow" style="max-width: 220px; max-height: 320px; object-fit: cover;">
                            @else
                                <div class="bg-light border text-secondary d-flex align-items-center justify-content-center rounded" style="width:220px; height:320px;">
                                    <span>No Image</span>
                                </div>
                            @endif
                            @php
                                // Define these variables outside of any conditional blocks so they're always available
                                $isBookAvailable = $book->available_qty > 0;
                                $member = auth()->check() && auth()->user()->role === 'Member' ? auth()->user()->member : null;
                                $hasExistingBorrow = $member ? \App\Models\Log_Pinjam_Buku::where('book_id', $book->book_id)
                                    ->where('member_id', $member->member_id)
                                    ->whereIn('status', ['pending', 'approved', 'overdue'])
                                    ->exists() : false;
                                    
                                // Check if book is already bookmarked by the user
                                $isBookmarked = false;
                                $isWishlisted = false;
                                
                                if ($member) {
                                    $isBookmarked = \App\Models\Bookmark::where('book_id', $book->book_id)
                                        ->where('member_id', $member->member_id)
                                        ->exists();
                                        
                                    $isWishlisted = \App\Models\Wishlist::where('book_id', $book->book_id)
                                        ->where('member_id', $member->member_id)
                                        ->exists();
                                }
                            @endphp
                            
                            @if(auth()->check() && auth()->user()->role === 'Member')
                            <div class="d-flex flex-wrap gap-3 mt-3 justify-content-center align-items-center">
                                <button type="button" 
                                    class="btn {{ $isBookmarked ? 'btn-secondary' : 'btn-primary' }} btn-lg shadow rounded-pill px-3 d-flex align-items-center justify-content-center" 
                                    style="width:48px; height:48px;" 
                                    title="{{ $isBookmarked ? 'Manage bookmark' : 'Add to bookmarks' }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#bookmarkModal">
                                    <i class="fa fa-bookmark text-white"></i>
                                </button>
                                <form method="POST" action="{{ route('books.wishlist', $book) }}" class="m-0">
                                    @csrf
                                    <input type="hidden" name="member_id" value="{{ $member ? $member->member_id : '' }}">
                                    <input type="hidden" name="book_id" value="{{ $book->book_id }}">
                                    <button type="submit" class="btn {{ $isWishlisted ? 'btn-secondary' : 'btn-success' }} btn-lg shadow rounded-pill px-3 d-flex align-items-center justify-content-center" style="width:48px; height:48px;" title="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}">
                                        <i class="fa fa-heart text-white"></i>
                                    </button>
                                </form>
                                <div class="w-100"></div>
                                @if($member)
                                <!-- Borrow Request Modal Trigger Button -->
                                <button type="button"
                                    class="btn {{ $isBookAvailable && !$hasExistingBorrow ? 'btn-warning' : 'btn-secondary' }} btn-lg shadow rounded-pill px-4 d-flex align-items-center justify-content-center {{ $isBookAvailable && !$hasExistingBorrow ? 'text-white' : 'text-muted' }} mt-2"
                                    style="min-width: 170px; height: 48px; font-weight: 600; font-size: 1.07rem;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#borrowRequestModal"
                                    {{ !$isBookAvailable || $hasExistingBorrow ? 'disabled' : '' }}
                                    dusk="borrow-request-btn">
                                    <i class="fa {{ $isBookAvailable && !$hasExistingBorrow ? 'fa-hand-paper' : 'fa-ban' }} me-2"></i>
                                    {{ $isBookAvailable ? ($hasExistingBorrow ? 'Already Requested' : 'Borrow Request') : 'Unavailable' }}
                                </button>
                                @else
                                <div class="alert alert-danger mt-2">Your account is not linked to a member profile. Please contact the administrator.</div>
                                @endif
                            </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <!-- Wider Synopsis Box -->
                            <div class="card shadow border-0 p-3 mb-3" style="min-height: 50px; max-width: 700px; background: #f8fafc;">
                                <div class="fw-bold mb-2" style="font-size: 1.13rem;">Synopsis</div>
                                <div class="text-muted" style="font-size: 1.07rem;">{{ $book->synopsis }}</div>
                            </div>
                            <!-- Qty Cards under Synopsis -->
                            <div class="d-flex gap-3 mb-3">
                                <div class="card shadow-sm border-0 px-3 py-2 text-center" style="min-width: 140px; background: #e6f9f0;">
                                    <div class="fw-bold" style="font-size:1.1rem; color:#16a34a;">Available Qty</div>
                                    <div class="fs-5 fw-bold" style="color:#16a34a;">{{ $book->available_qty }}</div>
                                </div>
                                <div class="card shadow-sm border-0 px-3 py-2 text-center" style="min-width: 140px; background: #fff6ea;">
                                    <div class="fw-bold" style="font-size:1.1rem; color:#fb923c;">Borrowed Qty</div>
                                    <div class="fs-5 fw-bold" style="color:#fb923c;">{{ $book->borrowed_qty }}</div>
                                </div>
                            </div>
                            <!-- Book Info Grid left-aligned beside image -->
                            <div class="book-info-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem 2.5rem; min-width: 350px; max-width: 520px; text-align: left;">
                                <div><strong>Author:</strong><br><span class="text-muted">{{ $book->author }}</span></div>
                                <div><strong>Category:</strong><br><span class="text-muted">{{ $book->category->category_name ?? '-' }}</span></div>
                                <div><strong>Publisher:</strong><br><span class="text-muted">{{ $book->publisher }}</span></div>
                                <div><strong>ISBN:</strong><br><span class="text-muted">{{ $book->isbn }}</span></div>
                                <div><strong>Publication Year:</strong><br><span class="text-muted">{{ $book->publication_year }}</span></div>
                            </div>
                        </div>
                    </div>
                    <!-- Empty div for spacing -->
                    <div style="height: 60px;"></div>
                    @if(!auth()->check() || auth()->user()->role !== 'Member')
                    <div style="position: absolute; right: 2rem; bottom: 2rem; z-index: 10;" class="d-flex gap-2">
                        <!-- Edit triggers modal with book data -->
                        <button type="button" class="btn btn-warning d-flex align-items-center px-4 py-2" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editBookModal"
                            data-book-id="{{ $book->book_id }}"
                            data-title="{{ $book->title }}"
                            data-author="{{ $book->author }}"
                            data-isbn="{{ $book->isbn }}"
                            data-publisher="{{ $book->publisher }}"
                            data-publication-year="{{ $book->publication_year }}"
                            data-category-id="{{ $book->category_id }}"
                            data-total-stock="{{ $book->total_stock }}"
                            data-synopsis="{{ $book->synopsis }}"
                        ><i class="fa fa-pencil me-1"></i> Edit</button>
                        <form action="{{ route('books.destroy', $book->book_id) }}" method="POST" onsubmit="return confirm('Delete this book?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger d-flex align-items-center px-4 py-2"><i class="fa fa-trash me-1"></i> Delete</button>
                        </form>
                    </div>
                    @endif
                    <!-- Include Edit Modal Partial with categories -->
                    @include('vendor.argon.books._edit_modal', ['book' => $book, 'categories' => $categories])
                </div>
            </div>
        </div>
        <!-- Reviews Card: make compact, height auto -->
        <div class="col-lg-4 mb-4 d-flex align-items-start">
            <div class="card shadow-lg border-0 w-100" style="min-height:unset;height:auto;max-height:90vh;overflow-y:auto;">
                <div class="card-header bg-white pb-2 border-0">
                    <h6 class="fw-bold text-primary mb-0"><i class="fa fa-comments me-1"></i>Reviews</h6>
                </div>
                <div class="card-body p-3 d-flex flex-column">
                    @php $reviews = $book->reviews()->with('member')->latest()->take(5)->get(); @endphp
                    @if($reviews->count())
                        <div class="list-group mb-3">
                            @foreach($reviews as $review)
                                <div class="list-group-item border-0 px-0 py-2 d-flex align-items-start">
                                    <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center me-2" style="width:36px;height:36px;">
                                        <span class="text-white fw-bold">{{ strtoupper(substr($review->member->name ?? 'U', 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <span class="fw-semibold text-dark">{{ $review->member->name ?? 'Unknown' }}</span>
                                        <!-- Show rating as stars -->
                                        @if(isset($review->rating))
                                            <span class="ms-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fa fa-star text-warning"></i>
                                                    @else
                                                        <i class="fa fa-star-o text-secondary"></i>
                                                    @endif
                                                @endfor
                                            </span>
                                        @endif
                                        <span class="text-muted d-block small">{{ $review->review_text ?? '' }}</span>
                                        <small class="text-secondary">{{ $review->review_date ? date('M d, Y', strtotime($review->review_date)) : '' }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted">No reviews available for this book.</div>
                    @endif
                    <div class="mt-auto w-100 text-end">
                        <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addReviewModal" dusk="add-review-modal-btn"><i class="fa fa-plus"></i> Add Review</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Borrow Request Modal -->
<div class="modal fade" id="borrowRequestModal" tabindex="-1" aria-labelledby="borrowRequestModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
          <h5 class="modal-title" id="borrowRequestModalLabel"><i class="fas fa-book-reader me-2"></i>Borrow Request</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{ route('borrow.request', $book) }}" id="borrowRequestForm">
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
          
          <!-- Validation Alerts -->
          @if(!$isBookAvailable)
          <div class="alert alert-danger mb-3">
            <i class="fas fa-exclamation-triangle me-2"></i>
            This book is currently unavailable for borrowing.
          </div>
          @endif
          
          @if($hasExistingBorrow)
          <div class="alert alert-warning mb-3">
            <i class="fas fa-exclamation-circle me-2"></i>
            You already have an active borrow request or loan for this book.
          </div>
          @endif
          
          @if(auth()->user()->role === 'Admin')
          <div class="mb-3">
            <label for="member_id" class="form-label">Member</label>
            <select class="form-select" id="member_id" name="member_id" required>
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
            <label for="borrow_date" class="form-label">Borrow Date</label>
            <input type="date" class="form-control" id="borrow_date" name="borrow_date" value="{{ old('borrow_date', now()->toDateString()) }}" required>
          </div>
          <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" class="form-control" id="due_date" name="due_date" value="{{ old('due_date', now()->addDays(14)->toDateString()) }}" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning text-white" {{ !$isBookAvailable || $hasExistingBorrow ? 'disabled' : '' }}>
            <i class="fas fa-paper-plane me-1"></i> Submit Request
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Review Modal Partial -->
@include('vendor.argon.books._review_modal', ['book' => $book])

<!-- Edit Book Modal -->
@include('vendor.argon.books._edit_modal', ['book' => $book, 'categories' => \App\Models\Kategori::all()])

@push('js')
<script>
    // Toast notification system for book borrowing workflow
    document.addEventListener('DOMContentLoaded', function() {
        // Create toast container if it doesn't exist
        if (!document.getElementById('toast-container')) {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }
        
        // Function to show toast notifications
        window.showToast = function(message, type = 'info') {
            const toastId = 'toast-' + Date.now();
            const toast = `
                <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-${type === 'warning' ? 'exclamation-triangle' : (type === 'danger' ? 'times-circle' : 'info-circle')} me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            
            document.getElementById('toast-container').innerHTML += toast;
            const toastElement = new bootstrap.Toast(document.getElementById(toastId), {
                delay: 5000
            });
            toastElement.show();
        };
        
        // Specific toast functions for borrowing workflow
        window.showUnavailableAlert = function() {
            showToast('This book is currently unavailable for borrowing.', 'danger');
        };
        
        window.showDuplicateBorrowAlert = function() {
            showToast('You already have an active borrow request or loan for this book.', 'warning');
        };
        
        // Add click handlers for disabled borrow buttons
        const borrowBtn = document.querySelector('button[dusk="borrow-request-btn"]');
        if (borrowBtn && borrowBtn.disabled) {
            borrowBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Check which condition caused the button to be disabled
                @if(!$isBookAvailable)
                    showUnavailableAlert();
                @elseif($hasExistingBorrow)
                    showDuplicateBorrowAlert();
                @endif
                
                return false;
            });
        }
        
        // Form validation for borrow request
        const borrowForm = document.getElementById('borrowRequestForm');
        if (borrowForm) {
            borrowForm.addEventListener('submit', function(e) {
                const bookQty = {{ $book->available_qty }};
                if (bookQty <= 0) {
                    e.preventDefault();
                    showUnavailableAlert();
                    return false;
                }
                
                @if($hasExistingBorrow)
                    e.preventDefault();
                    showDuplicateBorrowAlert();
                    return false;
                @endif
            });
        }
    });
</script>
@endpush
@endsection

<!-- Bookmark Modal -->
<div class="modal fade" id="bookmarkModal" tabindex="-1" aria-labelledby="bookmarkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('books.bookmark', $book) }}">
                @csrf
                <input type="hidden" name="member_id" value="{{ $member ? $member->member_id : '' }}">
                <input type="hidden" name="book_id" value="{{ $book->book_id }}">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="bookmarkModalLabel">{{ $isBookmarked ? 'Update Bookmark' : 'Add Bookmark' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Track your reading progress and add personal notes for <strong>{{ $book->title }}</strong>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="page_number" class="form-control-label">Current Page</label>
                        <input type="number" class="form-control" id="page_number" name="page_number" 
                               placeholder="Enter current page number" 
                               min="1" max="{{ $book->pages ?? 1000 }}" 
                               value="{{ $existingBookmark->page_number ?? '' }}">
                        <small class="form-text text-muted">Track which page you're currently reading</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes" class="form-control-label">Personal Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="4" 
                                  placeholder="Add your personal notes about this book...">{{ $existingBookmark->notes ?? '' }}</textarea>
                        <small class="form-text text-muted">Add thoughts, quotes, or reminders about this book</small>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn {{ $isBookmarked ? 'btn-info' : 'btn-primary' }}">
                        <i class="fas fa-{{ $isBookmarked ? 'save' : 'bookmark' }} me-1"></i>
                        {{ $isBookmarked ? 'Update Bookmark' : 'Add Bookmark' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
