@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, success, danger, warning, info, light, dark
    'size' => null, // sm, lg
    'outline' => false,
    'wave' => true,
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'href' => null,
    'class' => '',
])

@php
    $classes = 'btn';
    $classes .= $outline ? ' btn-' . $variant . '-outline' : ' btn-' . $variant;
    if ($size) $classes .= ' btn-' . $size;
    if ($wave) $classes .= ' btn-wave';
    $classes .= ' ' . $class;
    
    $tag = $href ? 'a' : 'button';
    $attributes = $attributes->merge([
        'class' => $classes,
    ]);
    
    if ($href) {
        $attributes = $attributes->merge(['href' => $href]);
    } else {
        $attributes = $attributes->merge(['type' => $type]);
    }
@endphp

<{{ $tag }} {{ $attributes }}>
    @if($icon && $iconPosition === 'left')
    <i class="{{ $icon }} me-2"></i>
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
    <i class="{{ $icon }} ms-2"></i>
    @endif
</{{ $tag }}>

