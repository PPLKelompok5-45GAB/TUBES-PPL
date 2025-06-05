@extends('layouts.argon')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h6>All Member Bookmarks</h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            @if($bookmarks->count() > 0)
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Member</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Book</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Notes</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookmarks as $bookmark)
                                            <tr>
                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $bookmark->member->name ?? 'Unknown' }}</p>
                                                    <p class="text-xs text-secondary mb-0">ID: {{ $bookmark->member_id }}</p>
                                                </td>
                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $bookmark->buku->title ?? 'Unknown' }}</p>
                                                    <p class="text-xs text-secondary mb-0">By: {{ $bookmark->buku->author ?? 'Unknown' }}</p>
                                                </td>
                                                <td class="ps-4">
                                                    <p class="text-xs mb-0">{{ $bookmark->notes ?? 'No notes' }}</p>
                                                </td>
                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $bookmark->created_at->format('Y-m-d') }}</p>
                                                </td>
                                                <td class="ps-4">
                                                    @if($bookmark->buku && $bookmark->buku->available_qty > 0)
                                                        <span class="badge badge-sm bg-gradient-success">Available</span>
                                                    @else
                                                        <span class="badge badge-sm bg-gradient-danger">Unavailable</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center py-4">
                                    <p>No bookmarks found.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $bookmarks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
