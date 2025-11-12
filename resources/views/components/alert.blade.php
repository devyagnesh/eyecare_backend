@props([
    'type' => 'info', // primary, secondary, success, danger, warning, info, light, dark
    'dismissible' => false,
    'icon' => null,
])

@php
    $iconMap = [
        'primary' => 'ri-information-line',
        'secondary' => 'ri-information-line',
        'success' => 'ri-checkbox-circle-line',
        'danger' => 'ri-error-warning-line',
        'warning' => 'ri-alert-line',
        'info' => 'ri-information-line',
        'light' => 'ri-information-line',
        'dark' => 'ri-information-line',
    ];
    
    $defaultIcon = $icon ?? ($iconMap[$type] ?? 'ri-information-line');
@endphp

<div class="alert alert-{{ $type }} @if($dismissible) alert-dismissible fade show @endif" role="alert">
    @if($icon || $defaultIcon)
    <div class="d-flex align-items-center">
        <i class="{{ $defaultIcon }} me-2 fs-20"></i>
        <div>
            {{ $slot }}
        </div>
    </div>
    @else
    {{ $slot }}
    @endif
    
    @if($dismissible)
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>

