@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info d-flex align-items-center gap-3 shadow-sm" style="background: linear-gradient(90deg, #f0f9ff 0%, #e0e7ef 100%); border-radius: 1rem;">
                <i class="fas fa-user-shield fa-2x text-primary"></i>
                <div>
                    <h4 class="mb-0">Welcome back, <b>{{ Auth::user()->name }}</b>!</h4>
                    <div class="small text-muted">Here’s a quick overview of your library system.</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Stat Cards -->
    <div class="row mb-2 g-3">
        <div class="col-6 col-lg-3">
            <div class="card text-center shadow-sm border-0 stat-card-admin" style="background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);">
                <div class="card-body">
                    <i class="fas fa-book fa-lg mb-2 text-primary"></i>
                    <h3 class="mb-0">{{ $stats['totalBooks'] ?? '-' }}</h3>
                    <div class="small text-muted">Books</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card text-center shadow-sm border-0 stat-card-admin" style="background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);">
                <div class="card-body">
                    <i class="fas fa-users fa-lg mb-2 text-success"></i>
                    <h3 class="mb-0">{{ $stats['totalMembers'] ?? '-' }}</h3>
                    <div class="small text-muted">Members</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card text-center shadow-sm border-0 stat-card-admin" style="background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);">
                <div class="card-body">
                    <i class="fas fa-book-reader fa-lg mb-2 text-info"></i>
                    <h3 class="mb-0">{{ $stats['borrowedBooks'] ?? '-' }}</h3>
                    <div class="small text-muted">Borrowed</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card text-center shadow-sm border-0 stat-card-admin" style="background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);">
                <div class="card-body">
                    <i class="fas fa-exclamation-triangle fa-lg mb-2 text-danger"></i>
                    <h3 class="mb-0">{{ $stats['overdueBorrows'] ?? '-' }}</h3>
                    <div class="small text-muted">Overdue</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Recent Activity Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header pb-0 bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold"><i class="fas fa-clock me-2 text-primary"></i>Borrow Requests Needing Approval</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createBookModal"><i class="fas fa-plus me-1"></i> Add Book</a>
                        <a href="/members" class="btn btn-success btn-sm"><i class="fas fa-users me-1"></i> Manage Members</a>
                        <a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal"><i class="fas fa-bullhorn me-1"></i> Post Announcement</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Member</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Book</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Request Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($pendingActions['pendingBorrows'] ?? []) as $borrow)
                                <tr style="cursor: pointer;" 
                                    class="hover-row borrow-row"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#viewBorrowModal"
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
                                    onmouseover="this.style.backgroundColor='#f8f9fa'"
                                    onmouseout="this.style.backgroundColor=''"
                                >
                                    <td>
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div class="avatar-sm me-3 bg-{{ substr(strtolower($borrow->member->name ?? 'User'), 0, 1) < 'm' ? 'primary' : 'info' }} rounded-circle d-flex align-items-center justify-content-center">
                                                <span class="text-white font-weight-bold">{{ strtoupper(substr($borrow->member->name ?? 'U', 0, 2)) }}</span>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $borrow->member->name ?? 'Unknown Member' }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $borrow->member->email ?? '' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column justify-content-center px-2 py-1">
                                            <h6 class="mb-0 text-sm">{{ $borrow->buku->title ?? 'Unknown Book' }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $borrow->buku->author ?? '' }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-1">
                                            <span class="text-secondary text-sm">{{ $borrow->created_at->format('Y-m-d') }}</span>
                                            <p class="text-xs text-muted mb-0">{{ $borrow->created_at->diffForHumans() }}</p>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex px-2 py-1 gap-2" onclick="event.stopPropagation();">
                                            <form class="borrow-action-form" method="POST" action="{{ route('borrow.approve', $borrow->loan_id) }}" data-action="approve" data-loan-id="{{ $borrow->loan_id }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success px-3">
                                                    <i class="fas fa-check me-1"></i> Approve
                                                </button>
                                            </form>
                                            <form class="borrow-action-form" method="POST" action="{{ route('borrow.reject', $borrow->loan_id) }}" data-action="reject" data-loan-id="{{ $borrow->loan_id }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger px-3">
                                                    <i class="fas fa-times me-1"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state text-center py-5">
                                            <i class="fas fa-check-circle fa-4x text-secondary mb-3"></i>
                                            <h4>No Pending Requests</h4>
                                            <p class="text-muted mb-3">All borrow requests have been processed.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .stat-card-admin .fa-lg {
        font-size: 2rem;
    }
    
    .hover-row {
        transition: background-color 0.2s ease;
    }
    
    .hover-row:hover {
        background-color: #f8f9fa !important;
    }
    .stat-card-admin h3 {
        font-size: 1.6rem;
        font-weight: 700;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize toasts if present
        var toastElList = [].slice.call(document.querySelectorAll('.toast'));
        toastElList.map(function(toastEl) {
            return new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 5000
            });
        });
        
        // Handle borrow action forms (approve/reject)
        document.querySelectorAll('.borrow-action-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                const isApprove = submitBtn.classList.contains('btn-success');
                const row = form.closest('tr');
                
                // Disable button and show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
                
                // Prepare the form data
                const formData = new FormData(form);
                
                // Send the request
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Show success feedback
                    if(data.success) {
                        // Create and show toast notification
                        showNotification(
                            isApprove ? 'Borrow Request Approved' : 'Borrow Request Rejected',
                            data.message || `The borrow request has been ${isApprove ? 'approved' : 'rejected'} successfully.`,
                            isApprove ? 'success' : 'danger'
                        );
                        
                        // Animate row removal
                        row.style.backgroundColor = isApprove ? '#d1e7dd' : '#f8d7da';
                        setTimeout(() => {
                            row.style.transition = 'opacity 0.5s ease-out';
                            row.style.opacity = '0';
                            setTimeout(() => {
                                row.remove();
                                
                                // Check if table is now empty
                                const tbody = document.querySelector('.table tbody');
                                if (tbody.children.length === 0) {
                                    const emptyRow = document.createElement('tr');
                                    emptyRow.innerHTML = `
                                        <td colspan="4">
                                            <div class="empty-state text-center py-5">
                                                <i class="fas fa-check-circle fa-4x text-secondary mb-3"></i>
                                                <h4>No Pending Requests</h4>
                                                <p class="text-muted mb-3">All borrow requests have been processed.</p>
                                            </div>
                                        </td>
                                    `;
                                    tbody.appendChild(emptyRow);
                                }
                            }, 500);
                        }, 300);
                    } else {
                        // Show error
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                        showNotification('Error', data.message || 'There was an error processing the request.', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    showNotification('Error', 'There was an error processing the request.', 'danger');
                });
            });
        });
        
        // Handle view modal data population when it's shown
        document.getElementById('viewBorrowModal').addEventListener('show.bs.modal', function(event) {
            // Get the button or row that triggered the modal
            const trigger = event.relatedTarget;
            
            // Get data attributes from the trigger element
            const borrowId = trigger.getAttribute('data-borrow-id');
            const bookTitle = trigger.getAttribute('data-book-title');
            const bookAuthor = trigger.getAttribute('data-book-author');
            const memberName = trigger.getAttribute('data-member-name');
            const memberEmail = trigger.getAttribute('data-member-email');
            const borrowDate = trigger.getAttribute('data-borrow-date');
            const dueDate = trigger.getAttribute('data-due-date');
            const returnDate = trigger.getAttribute('data-return-date');
            const status = trigger.getAttribute('data-status');
            
            // Set modal data
            const modal = this;
            modal.querySelector('#borrow-id').textContent = borrowId || 'N/A';
            modal.querySelector('#book-title').textContent = bookTitle || 'Unknown Book';
            modal.querySelector('#book-author').textContent = bookAuthor || 'N/A';
            modal.querySelector('#member-name').textContent = memberName || 'Unknown Member';
            modal.querySelector('#member-email').textContent = memberEmail || 'N/A';
            modal.querySelector('#borrow-date').textContent = borrowDate || 'N/A';
            modal.querySelector('#due-date').textContent = dueDate || 'N/A';
            modal.querySelector('#return-date').textContent = returnDate || 'N/A';
            modal.querySelector('#status').textContent = status ? status.charAt(0).toUpperCase() + status.slice(1) : 'N/A';
            
            // Set form action URLs with the loan ID
            const approveForm = modal.querySelector('form[action*="approve"]');
            const rejectForm = modal.querySelector('form[action*="reject"]');
            const returnForm = modal.querySelector('form[action*="return"]');
            
            if (approveForm) {
                approveForm.action = approveForm.action.replace(/\/\d+$/, '/' + borrowId);
            }
            if (rejectForm) {
                rejectForm.action = rejectForm.action.replace(/\/\d+$/, '/' + borrowId);
            }
            if (returnForm) {
                returnForm.action = returnForm.action.replace(/\/\d+$/, '/' + borrowId);
            }
            
            // Show/hide buttons based on status
            const approveBtn = modal.querySelector('.approve-btn');
            const rejectBtn = modal.querySelector('.reject-btn');
            const returnBtn = modal.querySelector('.return-btn');
            
            if (approveBtn) approveBtn.style.display = status === 'pending' ? 'inline-block' : 'none';
            if (rejectBtn) rejectBtn.style.display = status === 'pending' ? 'inline-block' : 'none';
            if (returnBtn) returnBtn.style.display = (status === 'approved' || status === 'overdue') ? 'inline-block' : 'none';
        });
        
        // Handle borrow action forms (approve/reject)
        document.querySelectorAll('.borrow-action-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Prevent the modal from opening when submitting the form
                const row = this.closest('tr');
                const modalTrigger = row.getAttribute('data-bs-toggle');
                if (modalTrigger) {
                    row.setAttribute('data-bs-toggle-temp', modalTrigger);
                    row.removeAttribute('data-bs-toggle');
                }
                
                const formEl = this;
                const action = formEl.getAttribute('data-action');
                const loanId = formEl.getAttribute('data-loan-id');
                const url = formEl.action;
                const submitBtn = formEl.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                
                // Disable button and show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ${action === 'approve' ? 'Approving...' : 'Rejecting...'}`;
                
                // Create form data for the request
                const formData = new FormData(formEl);
                
                // Add the Accept header to ensure we get a JSON response
                const fetchOptions = {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest' // This is key for Laravel to detect AJAX
                    },
                    body: formData
                };
                
                // Send AJAX request
                fetch(url, fetchOptions)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success notification
                        showNotification(
                            action === 'approve' ? 'Borrow Request Approved' : 'Borrow Request Rejected', 
                            data.message || `The borrow request has been ${action === 'approve' ? 'approved' : 'rejected'} successfully.`, 
                            action === 'approve' ? 'success' : 'danger'
                        );
                        
                        // Remove the row with animation
                        const row = btn.closest('tr');
                        row.style.transition = 'opacity 0.5s';
                        row.style.opacity = '0';
                        
                        setTimeout(() => {
                            row.remove();
                            
                            // Update pending count
                            const pendingCountElement = document.getElementById('pendingBorrowsCount');
                            if (pendingCountElement) {
                                const currentCount = parseInt(pendingCountElement.textContent);
                                if (!isNaN(currentCount) && currentCount > 0) {
                                    pendingCountElement.textContent = currentCount - 1;
                                }
                            }
                            
                            // Show empty message if no more rows
                            const tbody = document.querySelector('#pendingBorrowsTable tbody');
                            if (tbody && tbody.querySelectorAll('tr').length === 0) {
                                tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4">No pending borrow requests</td></tr>`;
                            }
                        }, 500);
                    } else {
                        // Show error notification
                        showNotification(
                            'Error', 
                            data.message || `There was an error ${action === 'approve' ? 'approving' : 'rejecting'} the request.`, 
                            'danger'
                        );
                        // Reset button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                        
                        // Restore the modal trigger
                        const row = formEl.closest('tr');
                        const modalTriggerTemp = row.getAttribute('data-bs-toggle-temp');
                        if (modalTriggerTemp) {
                            row.setAttribute('data-bs-toggle', modalTriggerTemp);
                            row.removeAttribute('data-bs-toggle-temp');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Show error notification
                    showNotification(
                        'Error', 
                        `There was an error ${action === 'approve' ? 'approving' : 'rejecting'} the request.`, 
                        'danger'
                    );
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    
                    // Restore the modal trigger
                    const row = formEl.closest('tr');
                    const modalTriggerTemp = row.getAttribute('data-bs-toggle-temp');
                    if (modalTriggerTemp) {
                        row.setAttribute('data-bs-toggle', modalTriggerTemp);
                        row.removeAttribute('data-bs-toggle-temp');
                    }
                });
            });
        });
        
        // Helper function to show notifications
        function showNotification(title, message, type) {
            // Create toast container if it doesn't exist
            let toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                document.body.appendChild(toastContainer);
            }
            
            // Create toast element
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.className = `toast align-items-center border-0 border-${type} bg-${type === 'danger' ? 'light' : 'light'} text-${type === 'danger' ? 'danger' : type}`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.setAttribute('id', toastId);
            
            // Toast content
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}</strong><br>
                        <span class="text-secondary small">${message}</span>
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            // Add to container
            toastContainer.appendChild(toast);
            
            // Initialize and show
            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 5000
            });
            bsToast.show();
            
            // Remove from DOM after hiding
            toast.addEventListener('hidden.bs.toast', function() {
                toast.remove();
            });
        }
    });
</script>
@endsection

@include('vendor.argon.books._create_modal', ['categories' => $categories])
@include('vendor.argon.announcements._create_modal')
@include('vendor.argon.borrow._edit_modal', ['books' => \App\Models\Buku::all(), 'members' => \App\Models\Member::all()])
@include('vendor.argon.borrow._view_modal')
