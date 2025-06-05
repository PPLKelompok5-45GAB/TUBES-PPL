@props(['status', 'customClass' => ''])

@php
$statusColors = [
    'pending' => 'warning',
    'requested' => 'warning',
    'approved' => 'info',
    'active' => 'success',
    'inactive' => 'secondary',
    'overdue' => 'danger',
    'returned' => 'success',
    'rejected' => 'danger',
    'available' => 'success',
    'unavailable' => 'danger',
    'published' => 'info',
    'draft' => 'secondary',
    'deleted' => 'dark'
];

$color = $statusColors[strtolower($status)] ?? 'secondary';
@endphp

<span class="badge bg-{{ $color }} {{ $customClass }}">
    {{ ucfirst($status) }}
</span>
