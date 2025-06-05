<!-- Review Modal -->
<div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      @php
        $canReview = false;
        $reviewMessage = "You can only review books you have borrowed and returned.";
        
        if (auth()->check() && auth()->user()->role === 'Member') {
          $member = auth()->user()->member;
          if ($member) {
            // Check if the member has borrowed and returned this book
            $borrowHistory = App\Models\Log_Pinjam_Buku::where('member_id', $member->member_id)
                            ->where('book_id', $book->book_id)
                            ->where('status', 'returned')
                            ->exists();
            $canReview = $borrowHistory;
            
            if (!$canReview) {
              // Check if currently borrowing
              $currentlyBorrowing = App\Models\Log_Pinjam_Buku::where('member_id', $member->member_id)
                                  ->where('book_id', $book->book_id)
                                  ->whereIn('status', ['pending', 'approved', 'overdue'])
                                  ->exists();
              
              if ($currentlyBorrowing) {
                $reviewMessage = "You can review this book after you return it.";
              }
            }
          }
        }
      @endphp
      
      @if($canReview)
        <form method="POST" action="{{ route('books.addReview', $book->book_id) }}">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="addReviewModalLabel">Add Review</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-success">
              <i class="fas fa-check-circle me-2"></i> You're eligible to review this book!
            </div>
            <div class="mb-3">
              <label for="review_rating" class="form-label">Rating</label>
              <input type="number" step="0.1" min="1" max="5" class="form-control" id="review_rating" name="rating" placeholder="Enter a rating from 1 to 5" required>
              <small class="form-text text-muted">Rate 1-5 (you can use decimals, e.g., 4.5)</small>
            </div>
            <div class="mb-3">
              <label for="review_text" class="form-label">Review</label>
              <textarea class="form-control" id="review_text" name="review_text" rows="3" maxlength="255" required dusk="review-textarea"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" dusk="submit-review-btn">Submit Review</button>
          </div>
        </form>
      @else
        <div class="modal-header">
          <h5 class="modal-title" id="addReviewModalLabel">Review Eligibility</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ $reviewMessage }}
          </div>
          <p class="text-muted">
            To review a book, you must first borrow it and then return it. This ensures reviews come from members who have actually read the book.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <a href="{{ auth()->user()->role === 'Admin' ? route('admin.books.index') : route('member.books.index') }}" class="btn btn-primary">Browse Books</a>
        </div>
      @endif
    </div>
  </div>
</div>
