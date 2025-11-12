@props([
    'name' => '',
    'label' => null,
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'rows' => 3,
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
    
    <textarea class="form-control @error($name) is-invalid @enderror {{ $class }}"
              id="{{ $id }}"
              name="{{ $name }}"
              rows="{{ $rows }}"
              placeholder="{{ $placeholder }}"
              @if($required) required @endif
              @if($readonly) readonly @endif
              @if($disabled) disabled @endif
              {{ $attributes }}>{{ $value }}</textarea>
    
    @if($help)
    <div class="form-text">{{ $help }}</div>
    @endif
    
    @if($error)
    <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif
</div>

