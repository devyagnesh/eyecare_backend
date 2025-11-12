@props([
    'id' => 'modal',
    'title' => 'Modal Title',
    'size' => 'default', // default, sm, lg, xl, fullscreen
    'static' => false,
    'centered' => false,
    'scrollable' => false,
    'footer' => true,
    'closeButton' => true,
])

<div class="modal fade" 
     id="{{ $id }}" 
     tabindex="-1" 
     aria-labelledby="{{ $id }}Label" 
     aria-hidden="true"
     @if($static) data-bs-backdrop="static" data-bs-keyboard="false" @endif>
    <div class="modal-dialog
        @if($size === 'sm') modal-sm
        @elseif($size === 'lg') modal-lg
        @elseif($size === 'xl') modal-xl
        @elseif($size === 'fullscreen') modal-fullscreen
        @endif
        @if($centered) modal-dialog-centered @endif
        @if($scrollable) modal-dialog-scrollable @endif">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="{{ $id }}Label">{{ $title }}</h6>
                @if($closeButton)
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @if($footer)
            <div class="modal-footer">
                {{ $footer ?? '' }}
                @if(!isset($footer))
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

