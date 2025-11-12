@props([
    'name' => '',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => 'Select an option',
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'help' => null,
    'error' => null,
    'class' => '',
    'id' => null,
    'multiple' => false,
    'choices' => false, // Use Choices.js
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
    
    <select class="form-select @error($name) is-invalid @enderror {{ $class }} @if($choices) choices-select @endif"
            id="{{ $id }}"
            name="{{ $name }}"
            @if($required) required @endif
            @if($readonly) readonly @endif
            @if($disabled) disabled @endif
            @if($multiple) multiple @endif
            {{ $attributes }}>
        @if(!$multiple)
        <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $optionValue => $optionLabel)
        <option value="{{ $optionValue }}" 
                @if(is_array($value))
                    {{ in_array($optionValue, $value) ? 'selected' : '' }}
                @else
                    {{ $optionValue == $value ? 'selected' : '' }}
                @endif>
            {{ $optionLabel }}
        </option>
        @endforeach
    </select>
    
    @if($help)
    <div class="form-text">{{ $help }}</div>
    @endif
    
    @if($error)
    <div class="invalid-feedback d-block">{{ $error }}</div>
    @endif
</div>

@if($choices)
@push('scripts')
@if(file_exists(public_path('assets/libs/choices.js/public/assets/scripts/choices.min.js')))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const element = document.getElementById('{{ $id }}');
    if (element) {
        new Choices(element, {
            searchEnabled: true,
            removeItemButton: {{ $multiple ? 'true' : 'false' }},
        });
    }
});
</script>
@endif
@endpush
@endif

