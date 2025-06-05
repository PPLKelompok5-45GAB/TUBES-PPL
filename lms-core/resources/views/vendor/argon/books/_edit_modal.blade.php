<!-- Edit Book Modal -->
<div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> 
    <div class="modal-content">
      <form id="edit-book-form" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editBookModalLabel">Edit Book</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @include('vendor.argon.books.form', ['book' => $book])
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
  var editBookModal = document.getElementById('editBookModal');
  if (editBookModal) {
    editBookModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var bookId = button ? button.getAttribute('data-book-id') : null;
      var title = button ? button.getAttribute('data-title') : '';
      var author = button ? button.getAttribute('data-author') : '';
      var isbn = button ? button.getAttribute('data-isbn') : '';
      var publisher = button ? button.getAttribute('data-publisher') : '';
      var publicationYear = button ? button.getAttribute('data-publication-year') : '';
      var categoryId = button ? button.getAttribute('data-category-id') : '';
      var totalStock = button ? button.getAttribute('data-total-stock') : '';
      var synopsis = button ? button.getAttribute('data-synopsis') : '';
      var form = document.getElementById('edit-book-form');
      if (form && bookId) {
        form.action = '/books/' + bookId;
      }
      if (form) {
        if (form.querySelector('[name=title]')) form.querySelector('[name=title]').value = title;
        if (form.querySelector('[name=author]')) form.querySelector('[name=author]').value = author;
        if (form.querySelector('[name=isbn]')) form.querySelector('[name=isbn]').value = isbn;
        if (form.querySelector('[name=publisher]')) form.querySelector('[name=publisher]').value = publisher;
        if (form.querySelector('[name=publication_year]')) form.querySelector('[name=publication_year]').value = publicationYear;
        if (form.querySelector('[name=category_id]')) form.querySelector('[name=category_id]').value = categoryId;
        if (form.querySelector('[name=stock]')) form.querySelector('[name=stock]').value = totalStock;
        if (form.querySelector('[name=synopsis]')) form.querySelector('[name=synopsis]').value = synopsis;
      }
    });
  }
});
</script>
