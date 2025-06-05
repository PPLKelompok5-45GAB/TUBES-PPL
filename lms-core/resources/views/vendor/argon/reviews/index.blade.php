@extends('layouts.app')
@section('title', 'Reviews')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <h6 class="mb-0">Book Reviews</h6>
                    <div class="d-flex align-items-center gap-2">
                        <form method="GET" class="d-flex align-items-center mt-2 mt-md-0" action="{{ auth()->user()->role === 'Admin' ? route('admin.reviews.index') : route('reviews.index') }}">
                            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search book, member, comment..." value="{{ request('search') }}" style="width: 220px;">
                            @if(request('search'))
                                <a href="{{ auth()->user()->role === 'Admin' ? route('admin.reviews.index') : route('reviews.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Reset</a>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                            <tr>
                                <td>{{ $review->buku->title ?? '-' }}</td>
                                <td>{{ $review->rating }}</td>
                                <td>{{ $review->review_text }}</td>
                                <td>
                                    @if(auth()->user() && auth()->user()->role !== 'Admin')
                                    <a href="#" class="btn btn-warning btn-sm edit-review-btn" data-bs-toggle="modal" data-bs-target="#editReviewModal"
                                       data-review-id="{{ $review->review_id }}"
                                       data-rating="{{ $review->rating }}"
                                       data-comment="{{ $review->review_text }}"
                                       data-book-id="{{ $review->book_id }}"
                                       data-member-id="{{ $review->member_id }}">
                                        Edit
                                    </a>
                                    @endif
                                    <a href="{{ route('books.show', $review->book_id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-book me-1"></i> Browse Book
                                    </a>
                                    <form action="{{ auth()->user()->role === 'Admin' ? route('admin.reviews.destroy', $review) : route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Remove review?')">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">{{ $reviews->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('vendor.argon.reviews._edit_modal')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  var editReviewModal = document.getElementById('editReviewModal');
  if (editReviewModal) {
    editReviewModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var reviewId = button ? button.getAttribute('data-review-id') : null;
      var rating = button ? button.getAttribute('data-rating') : '';
      var comment = button ? button.getAttribute('data-comment') : '';
      var bookId = button ? button.getAttribute('data-book-id') : '';
      var memberId = button ? button.getAttribute('data-member-id') : '';
      var form = document.getElementById('edit-review-form');
      if (form && reviewId) {
        form.action = '/reviews/' + reviewId;
      }
      if (form) {
        if (form.querySelector('[name=rating]')) form.querySelector('[name=rating]').value = rating;
        if (form.querySelector('[name=comment]')) form.querySelector('[name=comment]').value = comment;
        if (form.querySelector('[name=book_id]')) form.querySelector('[name=book_id]').value = bookId;
        if (form.querySelector('[name=member_id]')) form.querySelector('[name=member_id]').value = memberId;
      }
    });
  }
});
</script>
@endpush
