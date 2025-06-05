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
          <div id="form-container">
            <!-- Form will be loaded here via JavaScript -->
            @include('vendor.argon.borrow.form', ['borrow' => null])
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
  var editBorrowModal = document.getElementById('editBorrowModal');
  if (editBorrowModal) {
    editBorrowModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var borrowId = button ? button.getAttribute('data-borrow-id') : null;
      var bookId = button ? button.getAttribute('data-book-id') : '';
      var memberId = button ? button.getAttribute('data-member-id') : '';
      var memberName = button ? button.getAttribute('data-member-name') : '';
      var memberEmail = button ? button.getAttribute('data-member-email') : '';
      var borrowDate = button ? button.getAttribute('data-borrow-date') : '';
      var dueDate = button ? button.getAttribute('data-due-date') : '';
      var status = button ? button.getAttribute('data-status') : '';
      var form = document.getElementById('edit-borrow-form');
      
      if (form && borrowId) {
        form.action = '{{ url("/borrow") }}/' + borrowId;
      }
      
      // Always create/update the hidden member_id field to ensure it's submitted correctly
      var memberIdInput = form.querySelector('input[name="member_id"]');
      if (!memberIdInput) {
        memberIdInput = document.createElement('input');
        memberIdInput.type = 'hidden';
        memberIdInput.name = 'member_id';
        form.appendChild(memberIdInput);
      }
      memberIdInput.value = memberId;
      
      if (form) {
        // Set other field values
        if (form.querySelector('[name=book_id]')) { form.querySelector('[name=book_id]').value = bookId; }
        if (form.querySelector('[name=borrow_date]')) { form.querySelector('[name=borrow_date]').value = borrowDate; }
        if (form.querySelector('[name=due_date]')) { form.querySelector('[name=due_date]').value = dueDate; }
        if (form.querySelector('[name=status]')) { form.querySelector('[name=status]').value = status; }
        
        // If there's a visible member_id select, set it to disabled and update its displayed value
        var memberSelect = form.querySelector('select[name="member_id"]');
        if (memberSelect) {
          memberSelect.disabled = true;
          // Try to select the correct option
          for(var i = 0; i < memberSelect.options.length; i++) {
            if(memberSelect.options[i].value == memberId) {
              memberSelect.options[i].selected = true;
              break;
            }
          }
        }
      }
    });
  }
});
</script>
