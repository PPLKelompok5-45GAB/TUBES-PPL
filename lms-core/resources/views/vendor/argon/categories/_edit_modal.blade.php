<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="edit-category-form" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="edit_category_name" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="edit_category_name" name="category_name" required>
          </div>
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
  var editCategoryModal = document.getElementById('editCategoryModal');
  if (editCategoryModal) {
    editCategoryModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var categoryId = button ? button.getAttribute('data-category-id') : null;
      var categoryName = button ? button.getAttribute('data-name') : '';
      var form = document.getElementById('edit-category-form');
      var input = document.getElementById('edit_category_name');
      if (form && categoryId) {
        form.action = '/categories/' + categoryId;
      }
      if (input && categoryName) {
        input.value = categoryName;
      }
    });
  }
});
</script>
