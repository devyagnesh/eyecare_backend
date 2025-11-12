@props([
    'name' => '',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'help' => null,
    'error' => null,
    'class' => '',
    'id' => null,
])

@php
    $id = $id ?? $name;
    $value = $value ?? old($name);
    $error = $error ?? ($errors->first($name) ?? null);
@endphp

<div class="mb-3">
    @if($label)
    <label for="{{ $id }}" class="form-label">
        {{ $label }}
        @if($required)
        <span class="text-danger">*</span>
        @endif
    </label>
    @endif
    
    <input type="{{ $type }}" 
           class="form-control @error($name) is-invalid @enderror {{ $class }}"
           id="{{ $id }}"
           name="{{ $name }}"
           value="{{ $value }}"
           placeholder="{{ $placeholder }}"
           @if($required) required @endif
           @if($readonly) readonly @endif
           @if($disabled) disabled @endif
           {{ $attributes }}>
    
    @if($help)
    <div class="form-text">{{ $help }}</div>
    @endif
    
    @if($error)
    <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif
</div>

