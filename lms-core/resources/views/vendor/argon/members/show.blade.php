@extends('layouts.app')
@section('title', 'Member Details')
@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12 col-md-11 col-lg-11 col-xl-10 ms-md-auto ms-lg-3 ms-xl-4">
      <div class="card shadow-lg border-0">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center" style="background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%); color: #fff; border-top-left-radius: 1rem; border-top-right-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
          <h5 class="mb-0"><i class="fas fa-user me-2"></i>Member Details</h5>
          <div>
              <button type="button" class="btn btn-warning btn-sm me-2 edit-member-btn" data-bs-toggle="modal" data-bs-target="#editMemberModal" data-member-id="{{ $member->member_id }}" data-name="{{ $member->name }}" data-email="{{ $member->email }}" data-phone="{{ $member->phone }}" data-address="{{ $member->address }}" data-status="{{ $member->status }}">
                <i class="fas fa-edit me-1"></i> Edit
              </button>
              <a href="{{ route('members.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
          </div>
        </div>
        <div class="card-body">
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="mb-2"><strong>Name:</strong> {{ $member->name }}</div>
              <div class="mb-2"><strong>Email:</strong> {{ $member->email }}</div>
              <div class="mb-2"><strong>Phone:</strong> {{ $member->phone }}</div>
              <div class="mb-2"><strong>Address:</strong> {{ $member->address }}</div>
            </div>
            <div class="col-md-6">
              <div class="mb-2 d-flex align-items-center gap-2"><strong class="me-2">Status:</strong>
                <form method="POST" action="{{ route('members.update', $member) }}" class="d-flex align-items-center gap-2" style="margin-bottom: 0;">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="update_status_only" value="1">
                  <select name="status" class="form-select form-select-sm w-auto px-3 py-1" style="min-width: 100px;">
                    <option value="active" {{ $member->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ $member->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="inactive" {{ $member->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                  </select>
                  <button type="submit" class="btn btn-success btn-md px-4 py-2 d-flex align-items-center" title="Save status change">
                    <i class="fas fa-save me-1"></i> Save
                  </button>
                </form>
              </div>
              <div class="mb-2"><strong>Membership Date:</strong> {{ $member->membership_date }}</div>
            </div>
          </div>
          <hr>
          <h6 class="mb-3"><i class="fas fa-history me-2"></i>Borrow History</h6>
          <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Book Title</th>
                  <th>Borrow Date</th>
                  <th>Return Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @forelse($member->logPinjams as $borrow)
                  <tr>
                    <td>{{ $borrow->buku->title ?? '-' }}</td>
                    <td>{{ $borrow->borrow_date }}</td>
                    <td>{{ $borrow->return_date ?? '-' }}</td>
                    <td><span class="badge bg-{{ $borrow->status == 'borrowed' ? 'info' : ($borrow->status == 'returned' ? 'success' : 'secondary') }}">{{ ucfirst($borrow->status) }}</span></td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-center text-muted">No borrow history found.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@include('vendor.argon.members._edit_modal')

@endsection

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
        // Set membership date to current value
        if (form.querySelector('[name=membership_date]')) {
          form.querySelector('[name=membership_date]').value = '{{ $member->membership_date }}';
        }
      }
    });
  }
});
</script>
@endpush
