<!-- Edit Review Modal -->
<div class="modal fade" id="editReviewModal" tabindex="-1" aria-labelledby="editReviewModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="edit-review-form" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editReviewModalLabel">Edit Review</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="review_rating_edit" class="form-label">Rating</label>
            <input type="number" step="0.1" min="1" max="5" class="form-control" id="review_rating_edit" name="rating" placeholder="Enter a rating from 1 to 5" required>
            <small class="form-text text-muted">Rate 1-5 (you can use decimals, e.g., 4.5)</small>
          </div>
          <div class="mb-3">
            <label for="review_text_edit" class="form-label">Review</label>
            <textarea class="form-control" id="review_text_edit" name="review_text" rows="3" maxlength="255" required></textarea>
          </div>
          <input type="hidden" id="review_book_id_edit" name="book_id">
          <input type="hidden" id="review_member_id_edit" name="member_id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var editReviewModal = document.getElementById('editReviewModal');
  if (editReviewModal) {
    editReviewModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var reviewId = button ? button.getAttribute('data-review-id') : null;
      var rating = button ? button.getAttribute('data-rating') : '';
      var comment = button ? button.getAttribute('data-comment') : '';
      var bookId = button ? button.getAttribute('data-book-id') : '';
      var memberId = button ? button.getAttribute('data-member-id') : '';
      var form = document.getElementById('edit-review-form');
      if (form && reviewId) {
        form.action = '/reviews/' + reviewId;
      }
      if (form) {
        if (form.querySelector('[name=rating]')) form.querySelector('[name=rating]').value = rating;
        if (form.querySelector('[name=review_text]')) form.querySelector('[name=review_text]').value = comment;
        if (form.querySelector('[name=book_id]')) form.querySelector('[name=book_id]').value = bookId;
        if (form.querySelector('[name=member_id]')) form.querySelector('[name=member_id]').value = memberId;
      }
    });
  }
});
</script>
