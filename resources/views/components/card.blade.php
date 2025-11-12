@props([
    'title' => null,
    'headerActions' => null,
    'footer' => null,
    'class' => '',
])

<div class="card custom-card {{ $class }}">
    @if($title || $headerActions)
    <div class="card-header @if($headerActions) justify-content-between @endif">
        @if($title)
        <div class="card-title">{{ $title }}</div>
        @endif
        @if($headerActions)
        <div>{{ $headerActions }}</div>
        @endif
    </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
    
    @if($footer)
    <div class="card-footer">
        {{ $footer }}
    </div>
    @endif
</div>

