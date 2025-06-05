@props([
    'route' => null,
    'placeholder' => 'Search...',
    'searchValue' => null,
    'filters' => [],
    'resetUrl' => null
])

<form method="GET" action="{{ $route }}" class="search-filter-container">
    <div class="d-flex flex-wrap align-items-center gap-2">
        <div class="input-group input-group-sm search-input-group" style="width: 250px;">
            <span class="input-group-text">
                <i class="fas fa-search"></i>
            </span>
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="{{ $placeholder }}" 
                value="{{ $searchValue ?? request('search') }}"
                aria-label="{{ $placeholder }}"
            >
        </div>
        
        @foreach($filters as $filter)
            @if(isset($filter['name']) && isset($filter['options']))
                <select name="{{ $filter['name'] }}" class="filter-select" style="width: {{ $filter['width'] ?? '160px' }};">
                    <option value="">{{ $filter['label'] ?? 'All' }}</option>
                    @foreach($filter['options'] as $value => $label)
                        <option value="{{ $value }}" {{ request($filter['name']) == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            @endif
        @endforeach
        
        <button type="submit" class="filter-btn filter-btn-primary">
            <i class="fas fa-filter me-1"></i> Apply
        </button>
        
        @if((request('search') || collect($filters)->some(fn($filter) => request($filter['name']))) && $resetUrl)
            <a href="{{ $resetUrl }}" class="filter-btn filter-btn-reset">
                <i class="fas fa-undo me-1"></i> Reset
            </a>
        @endif
    
    {{ $slot }}
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debounce function for search inputs
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }
    
    // Apply debounce to search inputs
    const searchInputs = document.querySelectorAll('input[name="search"]');
    searchInputs.forEach(input => {
        input.addEventListener('input', debounce(function(e) {
            if (e.target.value.length >= 3 || e.target.value.length === 0) {
                e.target.closest('form').submit();
            }
        }, 500));
    });
});
</script>
