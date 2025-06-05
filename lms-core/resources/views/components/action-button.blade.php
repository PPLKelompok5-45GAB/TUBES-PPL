@props([
    'route' => '#',
    'type' => 'link', 
    'color' => 'primary', 
    'size' => 'sm',
    'icon' => null,
    'modalTarget' => null,
    'formAction' => null,
    'formMethod' => 'POST',
    'confirmMessage' => null,
    'dusk' => null
])

@php
    $classes = 'btn btn-' . $color . ' btn-' . $size;
    if ($icon) {
        $iconClass = 'fas fa-' . $icon . ' me-1';
    }
    $attributes = $attributes->merge(['class' => $classes]);
    
    if ($dusk) {
        $attributes = $attributes->merge(['dusk' => $dusk]);
    }
@endphp

@if($type === 'link')
    <a href="{{ $route }}" {{ $attributes }}>
        @if($icon)<i class="{{ $iconClass }}"></i>@endif
        {{ $slot }}
    </a>
@elseif($type === 'modal')
    <button type="button" {{ $attributes }} data-bs-toggle="modal" data-bs-target="#{{ $modalTarget }}">
        @if($icon)<i class="{{ $iconClass }}"></i>@endif
        {{ $slot }}
    </button>
@elseif($type === 'form')
    <form action="{{ $formAction }}" method="{{ $formMethod }}" class="d-inline">
        @csrf
        @if($formMethod !== 'POST' && $formMethod !== 'GET')
            @method($formMethod)
        @endif
        <button type="submit" {{ $attributes }} @if($confirmMessage) onclick="return confirm('{{ $confirmMessage }}')" @endif>
            @if($icon)<i class="{{ $iconClass }}"></i>@endif
            {{ $slot }}
        </button>
    </form>
@else
    <button type="button" {{ $attributes }}>
        @if($icon)<i class="{{ $iconClass }}"></i>@endif
        {{ $slot }}
    </button>
@endif
