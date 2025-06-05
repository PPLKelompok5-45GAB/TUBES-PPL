<!-- Create Review Modal -->
<div class="modal fade" id="createReviewModal" tabindex="-1" aria-labelledby="createReviewModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="create-review-form" method="POST" action="/reviews">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="createReviewModalLabel">Add Review</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="review_rating_create" class="form-label">Rating</label>
            <input type="number" step="0.1" min="1" max="5" class="form-control" id="review_rating_create" name="rating" placeholder="Enter a rating from 1 to 5" required>
            <small class="form-text text-muted">Rate 1-5 (you can use decimals, e.g., 4.5)</small>
          </div>
          <div class="mb-3">
            <label for="review_text_create" class="form-label">Review</label>
            <textarea class="form-control" id="review_text_create" name="comment" rows="3" maxlength="255" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>
