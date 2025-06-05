@extends('layouts.app')
@section('title', 'Announcements')
@section('content')
<div class="container-fluid py-4">
    <!-- Page Header with Announcements Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-danger bg-gradient shadow-sm border-0" style="background-color: #ff6347 !important;">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-md-7">
                            <h2 class="text-white mb-0 font-weight-bolder">Announcements</h2>
                            <p class="text-white opacity-8 mb-0 text-sm">Important library updates and events</p>
                        </div>
                        <div class="col-lg-4 col-md-5 mt-md-0 mt-3 text-md-end">
                            @if(auth()->user()->role === 'Admin')
                                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
                                    <i class="fas fa-plus me-2"></i>Add Announcement
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="announcement-feed row row-cols-1 row-cols-md-2 g-4">
                        @forelse($announcements as $announcement)
                            <div class="col">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex mb-3">
                                            <div class="announcement-icon bg-{{ $announcement->status == 'published' ? 'danger' : 'secondary' }} text-white d-flex align-items-center justify-content-center rounded-circle me-3" style="width:48px; height:48px;">
                                                <i class="fas fa-bullhorn"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-1">{{ $announcement->title }}</h5>
                                                <div class="text-muted small">{{ date('M j, Y', strtotime($announcement->created_at)) }}</div>
                                                @if(auth()->user()->role === 'Admin')
                                                    <span class="badge bg-{{ $announcement->status == 'published' ? 'success' : 'secondary' }} text-uppercase mt-1">
                                                        {{ $announcement->status }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <p class="text-muted mb-3">
                                            {{ Str::limit($announcement->content, 150) }}
                                        </p>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('announcements.show', $announcement) }}" class="text-decoration-none text-primary">
                                                View details
                                            </a>
                                            
                                            @if(auth()->user()->role === 'Admin')
                                                <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-inline ms-auto">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this announcement?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-bullhorn fa-4x text-secondary mb-3"></i>
                                    <h4>No Announcements Found</h4>
                                    <p class="text-muted mb-3">There are no announcements available at this time.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $announcements->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'Admin')
    @include('vendor.argon.announcements._create_modal')
    @include('vendor.argon.announcements._edit_modal')
@endif

<style>
    /* Announcement styles */
    .announcement-feed { max-width: 1200px; margin: auto; }
    .announcement-icon { min-width: 48px; min-height: 48px; font-size: 1.25rem; }
    
    /* Badge styles to match screenshot */
    .badge.bg-success { background-color: #2ecc71 !important; }
    .badge.bg-secondary { background-color: #95a5a6 !important; }
    
    /* Card hover effect */
    .card { transition: transform 0.2s, box-shadow 0.2s; }
    .card:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-target="#editAnnouncementModal"]').forEach(function(btn) {
        btn.addEventListener('click', function () {
            var announcementId = this.getAttribute('data-id');
            var modal = document.getElementById('editAnnouncementModal');
            var form = modal.querySelector('form');
            var fieldsContainer = modal.querySelector('#edit-announcement-form-fields');
            fieldsContainer.innerHTML = '<div class="text-center py-4">Loading...</div>';
            fetch(`/announcements/${announcementId}/edit?modal=1`)
                .then(response => response.text())
                .then(html => {
                    fieldsContainer.innerHTML = html;
                    form.action = `/announcements/${announcementId}`;
                })
                .catch(() => {
                    fieldsContainer.innerHTML = '<div class="text-danger">Failed to load announcement data.</div>';
                });
        });
    });
});
</script>
@endpush
@endsection
