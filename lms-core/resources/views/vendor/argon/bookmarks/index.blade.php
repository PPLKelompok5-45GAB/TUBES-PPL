@extends('layouts.app')
@section('title', 'Bookmarks')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Bookmarked Books</h6>
                </div>
                <div class="card-body">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Current Page</th>
                                <th>Personal Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookmarks as $bookmark)
                            <tr>
                                <td>{{ $bookmark->buku->title ?? '-' }}</td>
                                <td>{{ $bookmark->page_number ? $bookmark->page_number : '-' }}</td>
                                <td>
                                    @if($bookmark->notes)
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $bookmark->notes }}">{{ $bookmark->notes }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="btn btn-info btn-sm edit-bookmark-btn" data-bs-toggle="modal" data-bs-target="#editBookmarkModal"
                                       data-bookmark-id="{{ $bookmark->bookmark_id }}"
                                       data-book-id="{{ $bookmark->book_id }}"
                                       data-member-id="{{ $bookmark->member_id }}"
                                       data-page-number="{{ $bookmark->page_number }}"
                                       data-notes="{{ $bookmark->notes }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="{{ route('books.show', $bookmark->book_id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-book me-1"></i> Browse Book
                                    </a>
                                    <form action="{{ route('bookmarks.destroy', $bookmark) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Remove bookmark?')">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">{{ $bookmarks->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Edit Bookmark Modal -->
<div class="modal fade" id="editBookmarkModal" tabindex="-1" aria-labelledby="editBookmarkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="edit-bookmark-form" method="POST" action="">
                @csrf @method('PUT')
                <input type="hidden" name="book_id" value="">
                <input type="hidden" name="member_id" value="">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="editBookmarkModalLabel">Update Bookmark</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Track your reading progress and add personal notes
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="page_number" class="form-control-label">Current Page</label>
                        <input type="number" class="form-control" id="edit_page_number" name="page_number" 
                               placeholder="Enter current page number" 
                               min="1">
                        <small class="form-text text-muted">Track which page you're currently reading</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes" class="form-control-label">Personal Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="4" 
                                  placeholder="Add your personal notes about this book..."></textarea>
                        <small class="form-text text-muted">Add thoughts, quotes, or reminders about this book</small>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save me-1"></i>
                        Update Bookmark
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  var editBookmarkModal = document.getElementById('editBookmarkModal');
  if (editBookmarkModal) {
    editBookmarkModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var bookmarkId = button ? button.getAttribute('data-bookmark-id') : null;
      var bookId = button ? button.getAttribute('data-book-id') : '';
      var memberId = button ? button.getAttribute('data-member-id') : '';
      var pageNumber = button ? button.getAttribute('data-page-number') : '';
      var notes = button ? button.getAttribute('data-notes') : '';
      
      var form = document.getElementById('edit-bookmark-form');
      if (form && bookmarkId) {
        form.action = '/bookmarks/' + bookmarkId;
      }
      
      if (form) {
        if (form.querySelector('[name=book_id]')) form.querySelector('[name=book_id]').value = bookId;
        if (form.querySelector('[name=member_id]')) form.querySelector('[name=member_id]').value = memberId;
        if (form.querySelector('#edit_page_number')) form.querySelector('#edit_page_number').value = pageNumber;
        if (form.querySelector('#edit_notes')) form.querySelector('#edit_notes').value = notes;
      }
    });
  }
});
</script>
@endpush
