<!-- Edit Borrow Modal -->
@php
    // Ensure $books and $members are available for the modal
    if (!isset($books)) {
        $books = \App\Models\Buku::all();
    }
    if (!isset($members)) {
        $members = \App\Models\Member::all();
    }
@endphp
<div class="modal fade" id="editBorrowModal" tabindex="-1" aria-labelledby="editBorrowModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="edit-borrow-form" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editBorrowModalLabel">Edit Borrow</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @include('vendor.argon.borrow.form')
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
  var editBorrowModal = document.getElementById('editBorrowModal');
  if (editBorrowModal) {
    editBorrowModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var borrowId = button ? button.getAttribute('data-borrow-id') : null;
      var bookId = button ? button.getAttribute('data-book-id') : '';
      var memberId = button ? button.getAttribute('data-member-id') : '';
      var borrowDate = button ? button.getAttribute('data-borrow-date') : '';
      var dueDate = button ? button.getAttribute('data-due-date') : '';
      var status = button ? button.getAttribute('data-status') : '';
      var form = document.getElementById('edit-borrow-form');
      if (form && borrowId) {
        form.action = '{{ url("/borrow") }}/' + borrowId;
      }
      if (form) {
        if (form.querySelector('[name=book_id]')) { form.querySelector('[name=book_id]').value = bookId; form.querySelector('[name=book_id]').dispatchEvent(new Event('change')); }
        if (form.querySelector('[name=member_id]')) { form.querySelector('[name=member_id]').value = memberId; form.querySelector('[name=member_id]').dispatchEvent(new Event('change')); }
        if (form.querySelector('[name=borrow_date]')) { form.querySelector('[name=borrow_date]').value = borrowDate; }
        if (form.querySelector('[name=due_date]')) { form.querySelector('[name=due_date]').value = dueDate; }
        if (form.querySelector('[name=status]')) { form.querySelector('[name=status]').value = status; }
      }
    });
  }
});
</script>
