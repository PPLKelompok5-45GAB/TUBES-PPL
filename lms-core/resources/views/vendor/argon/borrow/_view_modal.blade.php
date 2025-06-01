<!-- Borrow Details Modal -->
<div class="modal fade" id="viewBorrowModal" tabindex="-1" aria-labelledby="viewBorrowModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="viewBorrowModalLabel"><i class="fas fa-book-reader me-2"></i>Borrow Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <!-- Book Info -->
        <div class="p-3 border-bottom">
          <h6 class="text-muted mb-2">Book</h6>
          <div class="d-flex align-items-center">
            <div class="me-3 bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
              <i class="fas fa-book text-white"></i>
            </div>
            <div>
              <h6 class="mb-0" id="viewBookTitle">-</h6>
              <small class="text-muted" id="viewBookAuthor">-</small>
            </div>
          </div>
        </div>
        
        <!-- Member Info -->
        <div class="p-3 border-bottom">
          <h6 class="text-muted mb-2">Member</h6>
          <div class="d-flex align-items-center">
            <div class="me-3 bg-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
              <span class="text-white" id="viewMemberInitials">-</span>
            </div>
            <div>
              <h6 class="mb-0" id="viewMemberName">-</h6>
              <small class="text-muted" id="viewMemberEmail">-</small>
            </div>
          </div>
        </div>
        
        <!-- Dates Info -->
        <div class="p-3">
          <div class="row">
            <div class="col-6 mb-3">
              <h6 class="text-muted mb-2">Borrow Date</h6>
              <p class="mb-0" id="viewBorrowDate">-</p>
            </div>
            <div class="col-6 mb-3">
              <h6 class="text-muted mb-2">Due Date</h6>
              <p class="mb-0" id="viewDueDate">-</p>
            </div>
            <div class="col-6 mb-3" id="viewReturnDateContainer">
              <h6 class="text-muted mb-2">Return Date</h6>
              <p class="mb-0" id="viewReturnDate">-</p>
            </div>
            <div class="col-6 mb-3">
              <h6 class="text-muted mb-2">Status</h6>
              <p class="mb-0"><span class="badge bg-secondary" id="viewStatusBadge">-</span></p>
            </div>
          </div>
        </div>
        
        <!-- Status Alert -->
        <div class="alert m-3" id="viewStatusAlert" role="alert" style="display: none;"></div>
      </div>
      
      <div class="modal-footer justify-content-between">
        <div class="d-flex gap-2 flex-wrap" id="viewActionButtons"></div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  var viewBorrowModal = document.getElementById('viewBorrowModal');
  if (viewBorrowModal) {
    viewBorrowModal.addEventListener('show.bs.modal', function(event) {
      // Get the button that triggered the modal
      var button = event.relatedTarget;
      
      // Extract data attributes
      var borrowId = button.getAttribute('data-borrow-id');
      var bookId = button.getAttribute('data-book-id');
      var bookTitle = button.getAttribute('data-book-title');
      var bookAuthor = button.getAttribute('data-book-author');
      var memberId = button.getAttribute('data-member-id');
      var memberName = button.getAttribute('data-member-name');
      var memberEmail = button.getAttribute('data-member-email');
      var memberInitials = button.getAttribute('data-member-initials');
      var borrowDate = button.getAttribute('data-borrow-date');
      var dueDate = button.getAttribute('data-due-date');
      var returnDate = button.getAttribute('data-return-date');
      var status = button.getAttribute('data-status');
      
      // Debug the data received
      console.log('View Modal data:', {
        borrowId, bookId, bookTitle, bookAuthor, memberId, memberName, memberEmail, memberInitials, borrowDate, dueDate, returnDate, status
      });
      
      // Clear any previous data
      document.getElementById('viewBookTitle').textContent = '-';
      document.getElementById('viewBookAuthor').textContent = '-';
      document.getElementById('viewMemberName').textContent = '-';
      document.getElementById('viewMemberEmail').textContent = '-';
      document.getElementById('viewMemberInitials').textContent = '-';
      document.getElementById('viewBorrowDate').textContent = '-';
      document.getElementById('viewDueDate').textContent = '-';
      document.getElementById('viewReturnDate').textContent = '-';
      document.getElementById('viewStatusBadge').textContent = '-';
      
      // Clean and validate data
      bookTitle = (bookTitle && bookTitle !== 'null' && bookTitle !== 'undefined') ? bookTitle : 'Unknown Book';
      bookAuthor = (bookAuthor && bookAuthor !== 'null' && bookAuthor !== 'undefined') ? bookAuthor : 'No author information';
      memberName = (memberName && memberName !== 'null' && memberName !== 'undefined') ? memberName : 'Unknown Member';
      memberEmail = (memberEmail && memberEmail !== 'null' && memberEmail !== 'undefined') ? memberEmail : 'No email information';
      memberInitials = (memberInitials && memberInitials !== 'null' && memberInitials !== 'undefined') ? memberInitials : 'U';
      borrowDate = (borrowDate && borrowDate !== 'null' && borrowDate !== 'undefined') ? borrowDate : 'Not specified';
      dueDate = (dueDate && dueDate !== 'null' && dueDate !== 'undefined') ? dueDate : 'Not specified';
      returnDate = (returnDate && returnDate !== 'null' && returnDate !== 'undefined') ? returnDate : '-';
      status = (status && status !== 'null' && status !== 'undefined') ? status : 'unknown';
      
      // Set modal content with validated data
      document.getElementById('viewBookTitle').textContent = bookTitle;
      document.getElementById('viewBookAuthor').textContent = bookAuthor;
      document.getElementById('viewMemberName').textContent = memberName;
      document.getElementById('viewMemberEmail').textContent = memberEmail;
      document.getElementById('viewMemberInitials').textContent = memberInitials.substring(0, 2);
      document.getElementById('viewBorrowDate').textContent = borrowDate;
      document.getElementById('viewDueDate').textContent = dueDate;
      document.getElementById('viewReturnDate').textContent = returnDate;
      
      // Show/hide return date based on status
      if (status === 'returned') {
        document.getElementById('viewReturnDateContainer').style.display = 'block';
      } else {
        document.getElementById('viewReturnDateContainer').style.display = 'none';
      }
      
      // Set status badge
      var statusBadge = document.getElementById('viewStatusBadge');
      var statusText = status.charAt(0).toUpperCase() + status.slice(1);
      statusBadge.textContent = statusText;
      
      // Set status badge color
      if (status === 'returned') {
        statusBadge.className = 'badge bg-success';
      } else if (status === 'overdue') {
        statusBadge.className = 'badge bg-danger';
      } else if (status === 'approved') {
        statusBadge.className = 'badge bg-warning';
      } else if (status === 'requested') {
        statusBadge.className = 'badge bg-secondary';
      } else {
        statusBadge.className = 'badge bg-info';
      }
      
      // Set status alert
      var statusAlert = document.getElementById('viewStatusAlert');
      statusAlert.style.display = 'block';
      
      if (status === 'returned') {
        statusAlert.className = 'alert alert-success m-3';
        statusAlert.innerHTML = '<i class="fas fa-check-circle me-2"></i> This book has been returned successfully.';
      } else if (status === 'overdue') {
        statusAlert.className = 'alert alert-danger m-3';
        statusAlert.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i> This book is overdue and should be returned immediately.';
      } else if (status === 'approved') {
        statusAlert.className = 'alert alert-warning m-3';
        statusAlert.innerHTML = '<i class="fas fa-clock me-2"></i> This book is currently borrowed and due on ' + dueDate + '.';
      } else if (status === 'requested') {
        statusAlert.className = 'alert alert-secondary m-3';
        statusAlert.innerHTML = '<i class="fas fa-hourglass-half me-2"></i> This borrow request is pending approval.';
      } else {
        statusAlert.className = 'alert alert-info m-3';
        statusAlert.innerHTML = '<i class="fas fa-info-circle me-2"></i> Status: ' + statusText;
      }
      
      // Set action buttons based on status and user role
      var actionButtons = document.getElementById('viewActionButtons');
      actionButtons.innerHTML = '';
      
      var isAdmin = {{ auth()->user()->role === 'Admin' ? 'true' : 'false' }};
      
      // Create a button group container for better alignment
      var buttonGroup = document.createElement('div');
      buttonGroup.className = 'btn-group';
      actionButtons.appendChild(buttonGroup);
      
      // Edit button for all statuses
      if (isAdmin) {
        var editButton = document.createElement('a');
        editButton.href = '#';
        editButton.className = 'btn btn-warning btn-sm';
        editButton.innerHTML = '<i class="fas fa-edit me-1"></i> Edit';
        editButton.setAttribute('data-bs-toggle', 'modal');
        editButton.setAttribute('data-bs-target', '#editBorrowModal');
        editButton.setAttribute('data-borrow-id', borrowId);
        editButton.setAttribute('data-book-id', bookId);
        editButton.setAttribute('data-member-id', memberId);
        editButton.setAttribute('data-borrow-date', borrowDate);
        editButton.setAttribute('data-due-date', dueDate);
        editButton.setAttribute('data-status', status);
        editButton.onclick = function() {
          var viewModal = bootstrap.Modal.getInstance(viewBorrowModal);
          viewModal.hide();
        };
        buttonGroup.appendChild(editButton);
      }
      
      // Add status-specific action buttons
      if (isAdmin && status === 'requested') {
        // Create action button group for request actions
        var requestActionGroup = document.createElement('div');
        requestActionGroup.className = 'btn-group ms-2';
        actionButtons.appendChild(requestActionGroup);
        
        // Approve button
        var approveForm = document.createElement('form');
        approveForm.action = '{{ url("/borrow") }}/' + borrowId + '/approve';
        approveForm.method = 'POST';
        approveForm.style.display = 'inline-block';
        approveForm.className = 'me-0';
        
        var csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        var approveButton = document.createElement('button');
        approveButton.type = 'submit';
        approveButton.className = 'btn btn-success btn-sm';
        approveButton.innerHTML = '<i class="fas fa-check me-1"></i> Approve';
        
        approveForm.appendChild(csrfToken);
        approveForm.appendChild(approveButton);
        requestActionGroup.appendChild(approveForm);
        
        // Reject button
        var rejectForm = document.createElement('form');
        rejectForm.action = '{{ url("/borrow") }}/' + borrowId + '/reject';
        rejectForm.method = 'POST';
        rejectForm.style.display = 'inline-block';
        rejectForm.className = 'ms-2';
        
        var csrfToken2 = document.createElement('input');
        csrfToken2.type = 'hidden';
        csrfToken2.name = '_token';
        csrfToken2.value = '{{ csrf_token() }}';
        
        var rejectButton = document.createElement('button');
        rejectButton.type = 'submit';
        rejectButton.className = 'btn btn-danger btn-sm';
        rejectButton.innerHTML = '<i class="fas fa-times me-1"></i> Reject';
        
        rejectForm.appendChild(csrfToken2);
        rejectForm.appendChild(rejectButton);
        requestActionGroup.appendChild(rejectForm);
      } else if (isAdmin && status === 'approved') {
        // Return button
        var returnActionGroup = document.createElement('div');
        returnActionGroup.className = 'ms-2';
        actionButtons.appendChild(returnActionGroup);
        
        var returnForm = document.createElement('form');
        returnForm.action = '{{ url("/borrow") }}/' + borrowId + '/return';
        returnForm.method = 'POST';
        returnForm.style.display = 'inline-block';
        
        var csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        var returnButton = document.createElement('button');
        returnButton.type = 'submit';
        returnButton.className = 'btn btn-success btn-sm';
        returnButton.innerHTML = '<i class="fas fa-undo me-1"></i> Return';
        
        returnForm.appendChild(csrfToken);
        returnForm.appendChild(returnButton);
        returnActionGroup.appendChild(returnForm);
      }
      
      // Delete button for admin
      if (isAdmin) {
        var deleteButtonGroup = document.createElement('div');
        deleteButtonGroup.className = 'ms-2';
        actionButtons.appendChild(deleteButtonGroup);
        
        var deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.className = 'btn btn-danger btn-sm';
        deleteButton.innerHTML = '<i class="fas fa-trash me-1"></i> Delete';
        deleteButton.setAttribute('data-bs-toggle', 'modal');
        deleteButton.setAttribute('data-bs-target', '#deleteBorrowModal' + borrowId);
        deleteButton.onclick = function() {
          var viewModal = bootstrap.Modal.getInstance(viewBorrowModal);
          viewModal.hide();
        };
        deleteButtonGroup.appendChild(deleteButton);
      }
    });
  }
});
</script>
@endpush
