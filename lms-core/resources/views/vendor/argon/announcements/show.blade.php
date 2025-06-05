@extends('layouts.app')
@section('title', 'Announcement Details')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-md-8 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center" style="background: linear-gradient(90deg, #d44636 0%, #ff7043 100%); color: #fff; border-top-left-radius: 1rem; border-top-right-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-bullhorn fa-lg me-2"></i>
                        <h4 class="mb-0">{{ $announcement->title }}</h4>
                    </div>
                    <a href="{{ route('announcements.index') }}" class="btn btn-light btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                        <span class="badge bg-{{ $announcement->status == 'published' ? 'success' : 'secondary' }} px-3 py-2 fs-6">{{ ucfirst($announcement->status) }}</span>
                        <span class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ $announcement->created_at->format('F j, Y \a\t H:i') }}</span>
                    </div>
                    <hr>
                    <div class="announcement-content mb-4 fs-5" style="white-space: pre-line;">{!! nl2br(e($announcement->content)) !!}</div>
                    @if(auth()->user()->role === 'Admin')
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editAnnouncementModal" 
                                data-announcement-id="{{ $announcement->post_id }}" 
                                data-title="{{ $announcement->title }}" 
                                data-content="{{ $announcement->content }}" 
                                data-status="{{ $announcement->status }}" 
                                data-post-date="{{ $announcement->post_date }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Delete this announcement?')"><i class="fas fa-trash"></i> Delete</button>
                            </form>
                        </div>
                        @include('vendor.argon.announcements._edit_modal')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


