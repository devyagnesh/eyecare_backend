@props([
    'title' => 'Page Title',
    'breadcrumbs' => [],
])

<div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h1 class="page-title fw-medium fs-18 mb-2">{{ $title }}</h1>
        @if(count($breadcrumbs) > 0)
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    @foreach($breadcrumbs as $index => $breadcrumb)
                    <li class="breadcrumb-item @if($index === count($breadcrumbs) - 1) active @endif" 
                        @if($index === count($breadcrumbs) - 1) aria-current="page" @endif>
                        @if($index < count($breadcrumbs) - 1 && isset($breadcrumb['url']))
                        <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
                        @else
                        {{ $breadcrumb['label'] }}
                        @endif
                    </li>
                    @endforeach
                </ol>
            </nav>
        </div>
        @endif
    </div>
    @if(isset($actions))
    <div class="btn-list">
        {{ $actions }}
    </div>
    @endif
</div>

