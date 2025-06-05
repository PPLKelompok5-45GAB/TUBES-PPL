@extends('layouts.app')

@section('title', 'Members')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <h6 class="mb-0">Member Management</h6>
                        <form method="GET" class="d-flex align-items-center ms-3" action="{{ route('members.index') }}">
                            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search name, email, phone..." value="{{ request('search') }}" style="width: 220px;">
                            <select name="status" class="form-select form-select-sm me-2" style="width: 160px;">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Search</button>
                            @if(request('search') || request('status'))
                                <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Reset</a>
                            @endif
                        </form>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#createMemberModal">
                        <i class="fas fa-plus me-1"></i> Add Member
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Member</th>
                                    <th>Contact Information</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $member)
                                <tr>
                                    <td>{{ $loop->iteration + ($members->currentPage() - 1) * $members->perPage() }}</td>
                                    <td>
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div class="avatar-sm me-3 bg-{{ substr(strtolower($member->name), 0, 1) < 'm' ? 'primary' : 'info' }} rounded-circle d-flex align-items-center justify-content-center">
                                                <span class="text-white font-weight-bold">{{ strtoupper(substr($member->name, 0, 2)) }}</span>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $member->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column justify-content-center px-2 py-1">
                                            <p class="text-sm mb-0">{{ $member->email }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $member->phone }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('members.update', $member) }}" class="d-flex align-items-center gap-2" style="margin-bottom:0;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="update_status_only" value="1">
                                            <select name="status" class="form-select form-select-sm" style="width:auto;min-width:110px;" onchange="this.form.submit()">
                                                <option value="active" {{ $member->status == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="suspended" {{ $member->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                                <option value="inactive" {{ $member->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <x-action-button 
                                                route="{{ route('members.show', $member) }}" 
                                                color="info" 
                                                icon="eye"
                                            >View</x-action-button>
                                            
                                            <x-action-button 
                                                type="button" 
                                                color="warning" 
                                                icon="edit"
                                                class="edit-member-btn"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editMemberModal"
                                                data-member-id="{{ $member->member_id }}"
                                                data-name="{{ $member->name }}"
                                                data-email="{{ $member->email }}"
                                                data-phone="{{ $member->phone }}"
                                                data-address="{{ $member->address }}"
                                                data-status="{{ $member->status }}"
                                            >Edit</x-action-button>
                                            
                                            <x-action-button 
                                                type="modal" 
                                                color="danger" 
                                                icon="trash"
                                                modal-target="deleteMemberModal{{ $member->member_id }}"
                                            >Delete</x-action-button>
                                        </div>
                                        
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteMemberModal{{ $member->member_id }}" tabindex="-1" aria-labelledby="deleteMemberModalLabel{{ $member->member_id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title" id="deleteMemberModalLabel{{ $member->member_id }}">Confirm Deletion</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert alert-danger">
                                                            <i class="fas fa-exclamation-circle me-2"></i> <strong>Warning:</strong> You are about to permanently delete this member.
                                                        </div>
                                                        <div class="d-flex flex-column gap-2 mb-3">
                                                            <div><strong>Name:</strong> {{ $member->name }}</div>
                                                            <div><strong>Email:</strong> {{ $member->email }}</div>
                                                            <div><strong>Status:</strong> {{ ucfirst($member->status) }}</div>
                                                        </div>
                                                        <p>This action <strong>cannot be undone</strong>. Are you sure you want to proceed?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('members.destroy', $member) }}" method="POST" style="display:inline-block; margin: 0;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Confirm Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-users fa-4x text-secondary mb-3"></i>
                                            <h4>No Members Found</h4>
                                            <p class="text-muted">
                                                @if(request('search') || request('status'))
                                                    No members match your current filters. Try adjusting your search criteria.
                                                @else
                                                    Start by adding your first member.
                                                @endif
                                            </p>
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createMemberModal">
                                                <i class="fas fa-plus me-1"></i> Add Member
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $members->links('vendor.pagination.argon') }}
                    </div>
            </div>
        </div>
    </div>
</div>
@include('vendor.argon.members._edit_modal')
@include('vendor.argon.members._create_modal')

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
      var status = button ? button.getAttribute('data-status') : '';
      var form = document.getElementById('edit-member-form');
      if (form && memberId) {
        form.action = '/members/' + memberId;
      }
      if (form) {
        if (form.querySelector('[name=name]')) form.querySelector('[name=name]').value = name;
        if (form.querySelector('[name=email]')) form.querySelector('[name=email]').value = email;
        if (form.querySelector('[name=phone]')) form.querySelector('[name=phone]').value = phone;
        if (form.querySelector('[name=status]')) form.querySelector('[name=status]').value = status;
      }
    });
  }
});
</script>
@endpush
