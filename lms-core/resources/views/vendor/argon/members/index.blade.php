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
                        <form method="GET" class="d-flex align-items-center ms-3" action="">
                            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search name, email, phone, address..." value="{{ request('search') }}" style="width: 220px;">
                            <select name="status" class="form-select form-select-sm me-2" style="width: 160px;">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
                            @if(request('search') || request('status'))
                                <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm ms-2">Reset</a>
                            @endif
                        </form>
                    </div>
                    <a href="{{ route('members.create') }}" class="btn btn-primary btn-sm mt-2 mt-md-0">Add Member</a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $member)
                                <tr>
                                    <td>{{ $loop->iteration + ($members->currentPage() - 1) * $members->perPage() }}</td>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->phone }}</td>
                                    <td>
                                        <span class="badge bg-{{ $member->status === 'active' ? 'success' : ($member->status === 'suspended' ? 'danger' : 'secondary') }}">{{ ucfirst($member->status) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('members.show', $member->member_id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('members.edit', $member->member_id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('members.destroy', $member->member_id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this member?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No members found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{-- Fix: Use default pagination view --}}
                        {{ $members->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
