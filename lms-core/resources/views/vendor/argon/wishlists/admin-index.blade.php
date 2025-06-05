@extends('layouts.argon')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h6>All Member Wishlists</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <form action="{{ route('admin.wishlists') }}" method="GET" class="d-flex gap-2 flex-wrap">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by title or member" value="{{ request('search') }}">
                                    </div>
                                    <select name="availability" class="form-select form-select-sm" style="max-width: 150px;">
                                        <option value="">All Status</option>
                                        <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                    @if(request('search') || request('availability'))
                                        <a href="{{ route('admin.wishlists') }}" class="btn btn-secondary btn-sm">Clear</a>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            @if($wishlists->count() > 0)
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Member</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Book</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Added On</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($wishlists as $wishlist)
                                            <tr>
                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $wishlist->member->name ?? 'Unknown' }}</p>
                                                    <p class="text-xs text-secondary mb-0">ID: {{ $wishlist->member_id }}</p>
                                                </td>
                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $wishlist->buku->title ?? 'Unknown' }}</p>
                                                    <p class="text-xs text-secondary mb-0">By: {{ $wishlist->buku->author ?? 'Unknown' }}</p>
                                                </td>
                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $wishlist->created_at->format('Y-m-d') }}</p>
                                                </td>
                                                <td class="ps-4">
                                                    @if($wishlist->buku && $wishlist->buku->available_qty > 0)
                                                        <span class="badge badge-sm bg-gradient-success">Available</span>
                                                    @else
                                                        <span class="badge badge-sm bg-gradient-danger">Unavailable</span>
                                                    @endif
                                                </td>
                                                <td class="ps-4">
                                                    @if($wishlist->buku && $wishlist->buku->available_qty > 0)
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#confirmBorrowModal{{ $wishlist->wishlist_id }}">
                                                            Create Borrow
                                                        </button>
                                                        
                                                        <!-- Confirmation Modal -->
                                                        <div class="modal fade" id="confirmBorrowModal{{ $wishlist->wishlist_id }}" tabindex="-1" aria-labelledby="confirmBorrowModalLabel{{ $wishlist->wishlist_id }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="confirmBorrowModalLabel{{ $wishlist->wishlist_id }}">Confirm Borrow Request</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="alert alert-info">
                                                                            <i class="fas fa-info-circle me-2"></i> You are about to create a borrow request for:
                                                                        </div>
                                                                        <div class="d-flex flex-column gap-2 mb-3">
                                                                            <div><strong>Book:</strong> {{ $wishlist->buku->title ?? 'Unknown' }}</div>
                                                                            <div><strong>Member:</strong> {{ $wishlist->member->name ?? 'Unknown' }}</div>
                                                                            <div><strong>Date:</strong> {{ now()->format('Y-m-d') }}</div>
                                                                        </div>
                                                                        <p>Are you sure you want to proceed?</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                        <form action="{{ route('borrow.store') }}" method="POST" class="d-inline m-0">
                                                                            @csrf
                                                                            <input type="hidden" name="book_id" value="{{ $wishlist->book_id }}">
                                                                            <input type="hidden" name="member_id" value="{{ $wishlist->member_id }}">
                                                                            <input type="hidden" name="borrow_date" value="{{ now()->format('Y-m-d') }}">
                                                                            <button type="submit" class="btn btn-primary">Confirm Borrow</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <button class="btn btn-sm btn-secondary" disabled>Unavailable</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-heart fa-4x text-secondary mb-3"></i>
                                        <h4>No Wishlist Items Found</h4>
                                        @if(request('search') || request('availability'))
                                            <p class="text-muted mb-3">No items match your current filters. Try adjusting your search criteria.</p>
                                            <a href="{{ route('admin.wishlists') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-undo me-1"></i> Clear Filters
                                            </a>
                                        @else
                                            <p class="text-muted mb-3">Members haven't added any books to their wishlists yet.</p>
                                            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-book me-1"></i> Browse Books
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $wishlists->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
