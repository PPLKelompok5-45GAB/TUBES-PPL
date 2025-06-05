@extends('layouts.app')
@section('title', 'Wishlists')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <h6>Wishlist Books</h6>
                    <a href="{{ route('member.books.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-book-open me-1"></i> Explore Library
                    </a>
                </div>
                <div class="card-body">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Availability Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wishlists as $wishlist)
                            <tr>
                                <td>{{ $wishlist->buku->title ?? '-' }}</td>
                                <td>
                                    @if(isset($wishlist->buku))
                                        @if($wishlist->buku->available_qty > 0)
                                            <span class="badge bg-success">Available ({{ $wishlist->buku->available_qty }})</span>
                                        @else
                                            <span class="badge bg-danger">Not Available</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Unknown</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('books.show', $wishlist->book_id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-book me-1"></i> Browse Book
                                    </a>
                                    <form action="{{ route('wishlists.destroy', $wishlist) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Remove from wishlist?')">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">{{ $wishlists->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  var editWishlistModal = document.getElementById('editWishlistModal');
  if (editWishlistModal) {
    editWishlistModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var wishlistId = button ? button.getAttribute('data-wishlist-id') : null;
      var bookId = button ? button.getAttribute('data-book-id') : '';
      var memberId = button ? button.getAttribute('data-member-id') : '';
      var form = document.getElementById('edit-wishlist-form');
      if (form && wishlistId) {
        form.action = '/wishlists/' + wishlistId;
      }
      if (form) {
        if (form.querySelector('[name=book_id]')) form.querySelector('[name=book_id]').value = bookId;
        if (form.querySelector('[name=member_id]')) form.querySelector('[name=member_id]').value = memberId;
      }
    });
  }
});
</script>
@endpush
