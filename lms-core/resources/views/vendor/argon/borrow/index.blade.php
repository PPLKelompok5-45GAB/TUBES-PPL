@extends('layouts.app')

@section('title', 'Borrow Requests & History')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="container-fluid py-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header pb-0 d-flex flex-column flex-md-row justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <h6 class="mb-0">Borrow Requests & History</h6>
                                    <form method="GET" class="d-flex align-items-center ms-3" action="">
                                        <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search book, member, status..." value="{{ request('search') }}" style="width: 220px;">
                                        <select name="status" class="form-select form-select-sm me-2" style="width: 160px;">
                                            <option value="">All Statuses</option>
                                            @foreach($statuses as $key => $label)
                                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @if(request('search') || request('status'))
                                            <a href="{{ route('borrow.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Reset</a>
                                        @endif
                                    </form>
                                </div>
                                @if(auth()->user()->role === 'Admin')
                                    <a href="#" class="btn btn-primary btn-sm mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#createBorrowModal">Add Borrow Request</a>
                                @endif
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Book</th>
                                                @if(auth()->user()->role === 'Admin')
                                                    <th>Member</th>
                                                @endif
                                                <th>Borrow Date</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $isMember = auth()->user()->role === 'Member'; $memberId = $isMember ? (auth()->user()->member?->member_id ?? 0) : null; @endphp
                                            @forelse($borrows as $borrow)
                                                @if((!$isMember) || ($isMember && $borrow->member_id == $memberId))
                                                <tr>
                                                    <td>{{ $loop->iteration + ($borrows->currentPage() - 1) * $borrows->perPage() }}</td>
                                                    <td>{{ $borrow->buku->title ?? '-' }}</td>
                                                    @if(auth()->user()->role === 'Admin')
                                                        <td>{{ $borrow->member->name ?? '-' }}</td>
                                                    @endif
                                                    <td>{{ $borrow->borrow_date ? $borrow->borrow_date->format('Y-m-d') : '-' }}</td>
                                                    <td>{{ $borrow->due_date ? $borrow->due_date->format('Y-m-d') : '-' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $borrow->status === 'returned' ? 'success' : ($borrow->status === 'overdue' ? 'danger' : ($borrow->status === 'approved' ? 'warning' : ($borrow->status === 'requested' ? 'secondary' : 'dark'))) }}">{{ ucfirst($borrow->status) }}</span>
                                                    </td>
                                                    <td class="text-nowrap" style="min-width: 200px;">
                                                        <div class="d-flex flex-wrap gap-1 mb-1">
                                                            <button type="button" class="btn btn-info btn-sm view-borrow-btn" data-bs-toggle="modal" data-bs-target="#viewBorrowModal"
                                                                data-borrow-id="{{ $borrow->loan_id }}"
                                                                data-book-id="{{ $borrow->book_id }}"
                                                                data-book-title="{{ $borrow->buku->title ?? 'Unknown Book' }}"
                                                                data-book-author="{{ $borrow->buku->author ?? '' }}"
                                                                data-member-id="{{ $borrow->member_id }}"
                                                                data-member-name="{{ $borrow->member->name ?? 'Unknown Member' }}"
                                                                data-member-email="{{ $borrow->member->email ?? '' }}"
                                                                data-member-initials="{{ strtoupper(substr($borrow->member->name ?? 'U', 0, 2)) }}"
                                                                data-borrow-date="{{ $borrow->borrow_date ? $borrow->borrow_date->format('Y-m-d') : '' }}"
                                                                data-due-date="{{ $borrow->due_date ? $borrow->due_date->format('Y-m-d') : '' }}"
                                                                data-return-date="{{ $borrow->return_date ? $borrow->return_date->format('Y-m-d') : '' }}"
                                                                data-status="{{ $borrow->status }}"
                                                            >
                                                                <i class="fas fa-eye me-1"></i> View
                                                            </button>
                                                            <a href="{{ route('books.show', $borrow->book_id) }}" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-book me-1"></i> Browse Book
                                                            </a>
                                                        </div>
                                                        
                                                        @if(auth()->user()->role === 'Admin' && $borrow->status === 'requested')
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            <form action="{{ route('borrow.approve', $borrow->loan_id) }}" method="POST" class="d-inline-block">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                                            </form>
                                                            <!-- Reject Button Trigger Modal -->
                                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectBorrowModal{{ $borrow->loan_id }}">
                                                                Reject
                                                            </button>
                                                        
                                                                <!-- Reject Confirmation Modal -->
                                                                <div class="modal fade" id="rejectBorrowModal{{ $borrow->loan_id }}" tabindex="-1" aria-labelledby="rejectBorrowModalLabel{{ $borrow->loan_id }}" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header bg-danger text-white">
                                                                                <h5 class="modal-title" id="rejectBorrowModalLabel{{ $borrow->loan_id }}">Confirm Rejection</h5>
                                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="alert alert-warning">
                                                                                    <i class="fas fa-exclamation-triangle me-2"></i> You are about to reject this borrow request.
                                                                                </div>
                                                                                <div class="d-flex flex-column gap-2 mb-3">
                                                                                    <div><strong>Book:</strong> {{ $borrow->buku->title ?? 'Unknown' }}</div>
                                                                                    <div><strong>Member:</strong> {{ $borrow->member->name ?? 'Unknown' }}</div>
                                                                                    <div><strong>Request Date:</strong> {{ $borrow->borrow_date ? date('Y-m-d', strtotime($borrow->borrow_date)) : 'N/A' }}</div>
                                                                                </div>
                                                                                <p>Are you sure you want to reject this request? This action cannot be undone.</p>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                                <form action="{{ route('borrow.reject', $borrow->loan_id) }}" method="POST" style="display:inline-block; margin: 0;">
                                                                                    @csrf
                                                                                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-5">
                                                        <div class="empty-state">
                                                            <i class="fas fa-book-reader fa-4x text-secondary mb-3"></i>
                                                            <h4>No Borrow Entries Found</h4>
                                                            @if(request('search') || request('status'))
                                                                <p class="text-muted mb-3">No entries match your current filters. Try adjusting your search criteria.</p>
                                                                <a href="{{ route('borrow.index') }}" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-undo me-1"></i> Clear Filters
                                                                </a>
                                                            @else
                                                                @if(auth()->user()->role === 'Admin')
                                                                    <p class="text-muted mb-3">There are no borrow records in the system yet.</p>
                                                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createBorrowModal">
                                                                        <i class="fas fa-plus me-1"></i> Create Borrow Request
                                                                    </button>
                                                                @else
                                                                    <p class="text-muted mb-3">You haven't borrowed any books yet.</p>
                                                                    <a href="{{ route('member.books.index') }}" class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-book me-1"></i> Browse Books
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4 d-flex justify-content-center">
                                    {{ $borrows->links('vendor.pagination.argon') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('vendor.argon.borrow._view_modal')
@endsection

@push('scripts')
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
        form.action = '/borrow/' + borrowId;
      }
      if (form) {
        if (form.querySelector('[name=book_id]')) form.querySelector('[name=book_id]').value = bookId;
        if (form.querySelector('[name=member_id]')) form.querySelector('[name=member_id]').value = memberId;
        if (form.querySelector('[name=borrow_date]')) form.querySelector('[name=borrow_date]').value = borrowDate;
        if (form.querySelector('[name=due_date]')) form.querySelector('[name=due_date]').value = dueDate;
        if (form.querySelector('[name=status]')) form.querySelector('[name=status]').value = status;
      }
    });
  }
});
</script>
@endpush

@php $isMember = auth()->user()->role === 'Member'; @endphp
@if($isMember)
    @push('scripts')
    <script>
    // Hide all edit/delete/approve/reject buttons for member users
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.edit-borrow-btn, form[action*="destroy"], form[action*="approve"], form[action*="reject"]').forEach(function(el) {
            el.style.display = 'none';
        });
    });
    </script>
    @endpush
@endif

@include('vendor.argon.borrow._edit_modal')
