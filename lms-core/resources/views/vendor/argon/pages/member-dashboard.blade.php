@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-primary d-flex align-items-center gap-3 shadow-sm" style="background: linear-gradient(90deg, #e0e7ff 0%, #f1f5f9 100%); border-radius: 1rem;">
                <i class="fas fa-user-circle fa-2x text-primary"></i>
                <div>
                    <h4 class="mb-0">Welcome back, <b>{{ Auth::user()->name }}</b>!</h4>
                    <div class="small text-muted">We're glad to see you. Here's your library at a glance.</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4 g-3">
        <!-- Quick Stats -->
        <div class="col-6 col-lg-3">
            <div class="card text-center shadow-sm border-0 stat-card">
                <div class="card-body">
                    <i class="fas fa-book fa-lg mb-2 text-indigo"></i>
                    <h3 class="mb-0">{{ $stats['currentlyBorrowed'] ?? 0 }}</h3>
                    <div class="small text-muted">Books Borrowed</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card text-center shadow-sm border-0 stat-card">
                <div class="card-body">
                    <i class="fas fa-bookmark fa-lg mb-2 text-purple"></i>
                    <h3 class="mb-0">{{ $bookmarks->count() ?? 0 }}</h3>
                    <div class="small text-muted">Bookmarks</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card text-center shadow-sm border-0 stat-card">
                <div class="card-body">
                    <i class="fas fa-heart fa-lg mb-2 text-pink"></i>
                    <h3 class="mb-0">{{ $wishlistItems->count() ?? 0 }}</h3>
                    <div class="small text-muted">Wishlists</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card text-center shadow-sm border-0 stat-card">
                <div class="card-body">
                    <i class="fas fa-exclamation-triangle fa-lg mb-2 text-danger"></i>
                    <h3 class="mb-0">{{ $stats['overdue'] ?? 0 }}</h3>
                    <div class="small text-muted">Overdue</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Active Borrows & Recommendations -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-white pb-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-book me-2 text-primary"></i>Currently Borrowed</h5>
                    <a href="{{ route('borrow.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body pt-0">
                    @if($activeBorrows->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-items-center">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs">Book</th>
                                        <th class="text-uppercase text-secondary text-xxs">Due Date</th>
                                        <th class="text-uppercase text-secondary text-xxs">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeBorrows as $borrow)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <h6 class="mb-0 text-sm">{{ $borrow->buku->title ?? 'Unknown' }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $borrow->buku->author ?? 'Unknown' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-sm font-weight-bold">
                                                    {{ $borrow->due_date ? date('M d, Y', strtotime($borrow->due_date)) : 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($borrow->status === 'overdue')
                                                    <span class="badge badge-sm bg-gradient-danger">Overdue</span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-success">Active</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="mb-0">You don't have any borrowed books.</p>
                            <a href="{{ route('member.books.index') }}" class="btn btn-sm btn-primary mt-2">Browse Books</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-white pb-2">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2 text-warning"></i>Recommended For You</h5>
                </div>
                <div class="card-body pt-0">
                    @if($recommendedBooks->count() > 0)
                        <div class="row g-3">
                            @foreach($recommendedBooks as $book)
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-1">{{ $book->title }}</h6>
                                            <p class="text-xs text-secondary mb-2">{{ $book->author }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge badge-sm bg-gradient-info">{{ $book->category->category_name ?? 'Uncategorized' }}</span>
                                                <a href="{{ route('books.show', $book->book_id) }}" class="btn btn-link btn-sm p-0 m-0">View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p>No recommendations available yet. Borrow some books to get started!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Profile Summary -->
        <div class="col-md-4">
            <div class="card card-profile shadow border-0 h-100" style="background: linear-gradient(135deg, #f8fafc 0%, #e3e9f7 100%);">
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=4e73df&color=fff" class="rounded-circle mb-3" width="100" height="100" alt="Avatar">
                    <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                    <div class="text-muted mb-2">{{ Auth::user()->email }}</div>
                    <span class="badge bg-indigo mb-2">Member since {{ Auth::user()->member->membership_date ?? 'N/A' }}</span>
                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('member.books.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i> Browse Books</a>
                        <a href="{{ route('profile') }}" class="btn btn-info btn-sm"><i class="fas fa-user-edit me-1"></i> Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Recent Activity -->
        <div class="col-md-8">
            <div class="card shadow border-0 h-100">
                <div class="card-header border-0 bg-white pb-2">
                    <h5 class="mb-0"><i class="fas fa-history me-2 text-primary"></i>Recent Activity</h5>
                </div>
                <div class="card-body pt-0">
                    <ul class="timeline">
                        @if($recentlyReturned->count() > 0 || $activeBorrows->count() > 0)
                            @foreach($activeBorrows->take(2) as $borrow)
                                <li class="timeline-item mb-3">
                                    <span class="timeline-icon bg-primary"><i class="fas fa-book"></i></span>
                                    <span class="fw-semibold">{{ date('Y-m-d', strtotime($borrow->borrow_date)) }}:</span> 
                                    Borrowed "{{ $borrow->buku->title ?? 'Unknown' }}"
                                </li>
                            @endforeach
                            
                            @foreach($recentlyReturned->take(3) as $borrow)
                                <li class="timeline-item mb-3">
                                    <span class="timeline-icon bg-success"><i class="fas fa-undo"></i></span>
                                    <span class="fw-semibold">{{ date('Y-m-d', strtotime($borrow->return_date)) }}:</span> 
                                    Returned "{{ $borrow->buku->title ?? 'Unknown' }}"
                                </li>
                            @endforeach
                        @else
                            <li class="timeline-item mb-3">
                                <span class="timeline-icon bg-info"><i class="fas fa-info"></i></span>
                                <span>No recent activity to display.</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stat-card .fa-lg {
        font-size: 2rem;
    }
    .stat-card h3 {
        font-size: 1.6rem;
        font-weight: 700;
    }
    .text-indigo { color: #6366f1; }
    .text-purple { color: #a78bfa; }
    .text-pink { color: #f472b6; }
    .timeline {
        list-style: none;
        margin: 0;
        padding: 0;
        position: relative;
    }
    .timeline:before {
        content: '';
        position: absolute;
        left: 16px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }
    .timeline-item {
        position: relative;
        padding-left: 48px;
    }
    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0.3rem;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.1rem;
    }
    .bg-indigo { background: #6366f1 !important; }
    .bg-success { background: #22c55e !important; }
</style>
@endsection
