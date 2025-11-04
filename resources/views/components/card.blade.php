@props([
    'title' => null,
    'subtitle' => null,
])

<div class="card {{ $attributes->get('class') }}">
    @if($title || isset($headerActions))
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                @if($title)
                    <div class="card-title">{{ $title }}</div>
                @endif
                @if($subtitle)
                    <div class="card-subtitle text-muted">{{ $subtitle }}</div>
                @endif
            </div>
            @isset($headerActions)
                <div>{{ $headerActions }}</div>
            @endisset
        </div>
    </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
</div>

