@props([
    'name',
    'label' => null,
    'value' => '1',
    'checked' => false,
    'helpText' => null,
])

<div class="mb-3">
    <div class="form-check">
        <input 
            class="form-check-input @error($name) is-invalid @enderror" 
            type="checkbox" 
            id="{{ $name }}" 
            name="{{ $name }}" 
            value="{{ $value }}"
            {{ old($name, $checked) ? 'checked' : '' }}
            {{ $attributes }}
        >
        @if($label)
        <label class="form-check-label" for="{{ $name }}">
            {{ $label }}
        </label>
        @endif
        @error($name)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    
    @if($helpText && !$errors->has($name))
        <div class="form-text">{{ $helpText }}</div>
    @endif
</div>

