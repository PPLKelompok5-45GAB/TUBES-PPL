@extends('layouts.app')
@section('title', 'Borrow Entry Details')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card shadow-lg border-0">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #f87171 0%, #fbbf24 100%); color: #222; border-top-left-radius: 1rem; border-top-right-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <h6 class="mb-0"><i class="fas fa-book-reader me-2"></i>Borrow Entry Details</h6>
                    <span class="badge bg-{{ $borrow->status === 'returned' ? 'success' : ($borrow->status === 'overdue' ? 'danger' : ($borrow->status === 'approved' ? 'warning' : ($borrow->status === 'requested' ? 'secondary' : 'info'))) }}">{{ ucfirst($borrow->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-2"><strong>Book:</strong> <span class="text-dark">{{ $borrow->buku->title ?? '-' }}</span></div>
                            <div class="mb-2"><strong>Member:</strong> <span class="text-dark">{{ $borrow->member->name ?? '-' }}</span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2"><strong>Borrow Date:</strong> <span class="text-dark">{{ $borrow->borrow_date }}</span></div>
                            <div class="mb-2"><strong>Due Date:</strong> <span class="text-dark">{{ $borrow->due_date }}</span></div>
                            <!-- <div class="mb-2"><strong>Return Date:</strong> <span class="text-dark">{{ $borrow->return_date ?? '-' }}</span></div> -->
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <a href="#"
                           class="btn btn-warning btn-md px-4 edit-borrow-btn"
                           data-bs-toggle="modal"
                           data-bs-target="#editBorrowModal"
                           data-borrow-id="{{ $borrow->loan_id }}"
                           data-book-id="{{ $borrow->book_id }}"
                           data-member-id="{{ $borrow->member_id }}"
                           data-borrow-date="{{ optional($borrow->borrow_date)->format('Y-m-d') }}"
                           data-due-date="{{ optional($borrow->due_date)->format('Y-m-d') }}"
                           data-status="{{ $borrow->status }}">
                           <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <a href="{{ route('borrow.index') }}" class="btn btn-secondary btn-md px-4"><i class="fas fa-arrow-left me-1"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('vendor.argon.borrow._edit_modal')
@endsection
