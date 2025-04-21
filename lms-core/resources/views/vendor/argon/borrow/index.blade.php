@extends('layouts.app')

@section('title', 'Borrow Requests & History')

@section('content')
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
                            <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
                            @if(request('search') || request('status'))
                                <a href="{{ route('borrow.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Reset</a>
                            @endif
                        </form>
                    </div>
                    <a href="{{ route('borrow.create') }}" class="btn btn-primary btn-sm mt-2 mt-md-0">Add Borrow Request</a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Book</th>
                                    <th>Member</th>
                                    <th>Borrow Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($borrows as $borrow)
                                <tr>
                                    <td>{{ $loop->iteration + ($borrows->currentPage() - 1) * $borrows->perPage() }}</td>
                                    <td>{{ $borrow->buku->title ?? '-' }}</td>
                                    <td>{{ $borrow->member->name ?? '-' }}</td>
                                    <td>{{ $borrow->borrow_date ? $borrow->borrow_date->format('Y-m-d') : '-' }}</td>
                                    <td>{{ $borrow->due_date ? $borrow->due_date->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $borrow->status === 'returned' ? 'success' : ($borrow->status === 'overdue' ? 'danger' : ($borrow->status === 'approved' ? 'warning' : ($borrow->status === 'requested' ? 'secondary' : 'dark'))) }}">{{ ucfirst($borrow->status) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('borrow.show', $borrow->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('borrow.edit', $borrow->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        @if($borrow->status === 'requested')
                                            <form action="{{ route('borrow.approve', $borrow->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('borrow.reject', $borrow->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('borrow.destroy', $borrow->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this entry?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No borrow entries found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 ms-2">
                        {{ $borrows->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
