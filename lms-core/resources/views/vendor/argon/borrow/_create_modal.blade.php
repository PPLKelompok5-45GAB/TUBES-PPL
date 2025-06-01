<!-- Create Borrow Modal -->
<div class="modal fade" id="createBorrowModal" tabindex="-1" aria-labelledby="createBorrowModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="create-borrow-form" method="POST" action="/borrow">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="createBorrowModalLabel">Add Borrow</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @include('vendor.argon.borrow.form')
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>
