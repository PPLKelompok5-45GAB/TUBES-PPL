<!-- Edit Member Modal -->
<div class="modal fade" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="edit-member-form" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editMemberModalLabel">Edit Member</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @include('vendor.argon.members.form')
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  var editMemberModal = document.getElementById('editMemberModal');
  if (editMemberModal) {
    editMemberModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var memberId = button ? button.getAttribute('data-member-id') : null;
      var name = button ? button.getAttribute('data-name') : '';
      var email = button ? button.getAttribute('data-email') : '';
      var phone = button ? button.getAttribute('data-phone') : '';
      var address = button ? button.getAttribute('data-address') : '';
      var status = button ? button.getAttribute('data-status') : '';
      
      var form = document.getElementById('edit-member-form');
      if (form && memberId) {
        form.action = '{{ url("/members") }}/' + memberId;
      }
      
      // Update form fields
      if (form) {
        if (form.querySelector('[name=name]')) form.querySelector('[name=name]').value = name;
        if (form.querySelector('[name=email]')) form.querySelector('[name=email]').value = email;
        if (form.querySelector('[name=phone]')) form.querySelector('[name=phone]').value = phone;
        if (form.querySelector('[name=address]')) form.querySelector('[name=address]').value = address;
        if (form.querySelector('[name=status]')) form.querySelector('[name=status]').value = status;
      }
    });
  }
});
</script>
@endpush
