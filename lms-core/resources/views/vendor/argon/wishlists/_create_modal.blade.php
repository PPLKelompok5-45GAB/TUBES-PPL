<!-- Create Wishlist Modal -->
<div class="modal fade" id="createWishlistModal" tabindex="-1" aria-labelledby="createWishlistModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="create-wishlist-form" method="POST" action="/wishlists">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="createWishlistModalLabel">Add Wishlist</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @include('vendor.argon.wishlists.form')
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>
