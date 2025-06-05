<!-- Edit Wishlist Modal -->
<div class="modal fade" id="editWishlistModal" tabindex="-1" aria-labelledby="editWishlistModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="edit-wishlist-form" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editWishlistModalLabel">Edit Wishlist</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @include('vendor.argon.wishlists.form')
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
  var editWishlistModal = document.getElementById('editWishlistModal');
  if (editWishlistModal) {
    editWishlistModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var wishlistId = button ? button.getAttribute('data-wishlist-id') : null;
      var form = document.getElementById('edit-wishlist-form');
      if (form && wishlistId) {
        form.action = '/wishlists/' + wishlistId;
      }
      // Add more fields as needed for pre-filling
    });
  }
});
</script>
