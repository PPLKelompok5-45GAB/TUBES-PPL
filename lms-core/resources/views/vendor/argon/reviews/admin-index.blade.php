@extends('layouts.app')
@section('title', 'Admin Reviews')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <h6 class="mb-0">Book Reviews Management</h6>
                    <div class="d-flex align-items-center gap-2">
                        <form method="GET" class="d-flex align-items-center mt-2 mt-md-0" action="{{ route('admin.reviews.index') }}">
                            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search book, member, comment..." value="{{ request('search') }}" style="width: 220px;">
                            @if(request('search'))
                                <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Reset</a>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @if($reviews->count() > 0)
                    <style>
                        .clickable-row {
                            transition: background-color 0.2s ease;
                        }
                        .clickable-row:hover {
                            background-color: #f8f9fa;
                        }
                        .clickable-row a {
                            color: inherit;
                            text-decoration: none;
                        }
                        .clickable-row {
                            cursor: pointer;
                        }
                        .action-cell {
                            position: relative;
                            z-index: 1;
                        }
                        .action-cell button,
                        .action-cell form {
                            position: relative;
                            z-index: 2;
                        }
                    </style>
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Member</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                            @php
                                $bookId = $review->buku->book_id ?? null;
                                $bookRoute = $bookId ? route('books.show', $bookId) : 'javascript:void(0);';
                                // For debugging:
                                // \Log::info('Book ID: ' . $bookId . ', Route: ' . $bookRoute);
                            @endphp
                            <tr class="clickable-row" data-href="{{ $bookRoute }}">
                                <td>
                                    @if($bookId)
                                        <a href="{{ $bookRoute }}" class="text-decoration-none text-dark">
                                            {{ $review->buku->title ?? 'No Title' }}
                                        </a>
                                    @else
                                        {{ $review->buku->title ?? '-' }}
                                    @endif
                                </td>
                                <td>{{ $review->member->name ?? '-' }}</td>
                                <td>{{ $review->rating }}</td>
                                <td>{{ $review->review_text }}</td>
                                <td>{{ \Carbon\Carbon::parse($review->review_date)->format('Y-m-d') }}</td>
                                <td class="action-cell">
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" onclick="event.stopPropagation(); if(confirm('Remove review?')) { this.closest('form').submit(); }">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            document.querySelectorAll('.clickable-row').forEach(row => {
                                row.addEventListener('click', function(e) {
                                    // Don't navigate if clicking on the remove button or its form
                                    if (!e.target.closest('.action-cell button, .action-cell form')) {
                                        const href = this.getAttribute('data-href');
                                        if (href && href !== 'javascript:void(0);') {
                                            window.location.href = href;
                                        }
                                    }
                                });
                            });
                        });
                    </script>
                    <div class="mt-4">
                        {{ $reviews->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-star fa-4x text-secondary mb-3"></i>
                            <h4>No Reviews Found</h4>
                            @if(request('search'))
                                <p class="text-muted mb-3">No reviews match your current search criteria. Try adjusting your search terms.</p>
                                <a href="{{ route('admin.reviews.index') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-undo me-1"></i> Clear Filters
                                </a>
                            @else
                                <p class="text-muted mb-3">Members haven't submitted any book reviews yet.</p>
                                <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-book me-1"></i> Browse Books
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
