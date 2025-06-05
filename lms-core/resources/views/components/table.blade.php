@props(['title' => null, 'actions' => null, 'filters' => null, 'footerSlot' => null])

<div class="table-container">
    @if($title || $actions)
    <div class="table-actions px-3 pt-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            @if($title)
            <h6 class="table-title mb-2 mb-md-0">{{ $title }}</h6>
            @endif
            
            @if($actions)
            <div class="d-flex flex-wrap gap-2">
                {{ $actions }}
            </div>
            @endif
        </div>
    </div>
    @endif
    
    @if($filters)
    <div class="table-filter">
        {{ $filters }}
    </div>
    @endif
    
    <div class="table-responsive p-0">
        <div class="table-header-wrapper">
            <table class="table align-items-center mb-0 table-hover libralink-table">
                {{ $slot }}
            </table>
        </div>
    </div>
    
    @if($footerSlot)
    <div class="table-footer">
        {{ $footerSlot }}
    </div>
    @endif
</div>
