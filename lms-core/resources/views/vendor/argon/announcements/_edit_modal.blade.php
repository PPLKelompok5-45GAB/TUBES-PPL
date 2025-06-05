<!-- Edit Announcement Modal -->
<div class="modal fade" id="editAnnouncementModal" tabindex="-1" aria-labelledby="editAnnouncementModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="edit-announcement-form" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editAnnouncementModalLabel">Edit Announcement</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @include('vendor.argon.announcements.form', ['announcement' => $announcement])
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
  var editAnnouncementModal = document.getElementById('editAnnouncementModal');
  if (editAnnouncementModal) {
    editAnnouncementModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var announcementId = button ? button.getAttribute('data-announcement-id') : null;
      var title = button ? button.getAttribute('data-title') : '';
      var content = button ? button.getAttribute('data-content') : '';
      var status = button ? button.getAttribute('data-status') : 'draft';
      var postDate = button ? button.getAttribute('data-post-date') : '';
      
      var form = document.getElementById('edit-announcement-form');
      
      // Set the form action
      if (form && announcementId) {
        form.action = '/announcements/' + announcementId;
      }
      
      // Populate all form fields
      if (document.getElementById('edit_announcement_title')) {
        document.getElementById('edit_announcement_title').value = title;
      }
      
      if (document.getElementById('edit_announcement_content')) {
        document.getElementById('edit_announcement_content').value = content;
      }
      
      if (document.getElementById('edit_announcement_status')) {
        var statusSelect = document.getElementById('edit_announcement_status');
        for (var i = 0; i < statusSelect.options.length; i++) {
          if (statusSelect.options[i].value === status) {
            statusSelect.options[i].selected = true;
            break;
          }
        }
      }
      
      if (document.getElementById('edit_announcement_post_date')) {
        document.getElementById('edit_announcement_post_date').value = postDate;
      }
    });
  }
});
</script>
