@props([
    'icon' => 'fa-info-circle',
    'title' => 'No Data Found',
    'message' => 'There are no items to display.',
    'actionUrl' => null,
    'actionIcon' => 'fa-plus',
    'actionText' => 'Add New',
    'isFiltered' => false,
    'resetUrl' => null,
    'colspan' => 4
])

<tr>
    <td colspan="{{ $colspan }}">
        <div class="empty-state text-center py-5">
            <i class="fas {{ $icon }} fa-4x text-secondary mb-3"></i>
            <h4>{{ $title }}</h4>
            
            @if($isFiltered)
                <p class="text-muted mb-3">No items match your current filters. Try adjusting your search criteria.</p>
                @if($resetUrl)
                <a href="{{ $resetUrl }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-undo me-1"></i> Clear Filters
                </a>
                @endif
            @else
                <p class="text-muted mb-3">{{ $message }}</p>
                @if($actionUrl)
                <a href="{{ $actionUrl }}" class="btn btn-primary btn-sm">
                    <i class="fas {{ $actionIcon }} me-1"></i> {{ $actionText }}
                </a>
                @endif
            @endif
        </div>
    </td>
</tr>
