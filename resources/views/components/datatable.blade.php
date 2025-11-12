@props([
    'id' => 'datatable',
    'columns' => [],
    'ajax' => null,
    'serverSide' => false,
    'responsive' => true,
    'pageLength' => 25,
    'order' => [[0, 'desc']],
    'buttons' => false,
    'export' => false,
    'search' => true,
    'paging' => true,
    'info' => true,
    'scrollX' => false,
    'scrollY' => false,
])

@push('styles')
@if(file_exists(public_path('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
@endif
@if($responsive && file_exists(public_path('assets/libs/datatables.net-responsive/css/responsive.bootstrap.min.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-responsive/css/responsive.bootstrap.min.css') }}">
@endif
@if($buttons && file_exists(public_path('assets/libs/datatables.net-buttons/css/buttons.bootstrap5.min.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-buttons/css/buttons.bootstrap5.min.css') }}">
@endif
@endpush

<div class="table-responsive">
    <table id="{{ $id }}" class="table table-bordered text-nowrap w-100">
        <thead>
            <tr>
                {{ $header ?? '' }}
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
        @if(isset($footer))
        <tfoot>
            <tr>
                {{ $footer }}
            </tr>
        </tfoot>
        @endif
    </table>
</div>

@push('scripts')
@if(file_exists(public_path('assets/libs/jquery/jquery.min.js')))
<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
@elseif(file_exists(public_path('assets/libs/jquery/jquery-3.7.1.min.js')))
<script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}"></script>
@endif

@if(file_exists(public_path('assets/libs/datatables.net/js/jquery.dataTables.min.js')))
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
@endif

@if(file_exists(public_path('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js')))
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
@endif

@if($responsive && file_exists(public_path('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')))
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
@endif

@if($buttons && file_exists(public_path('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')))
<script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
@endif

@if($export && file_exists(public_path('assets/libs/datatables.net-buttons/js/buttons.html5.min.js')))
<script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
@endif

<script>
$(document).ready(function() {
    var config = {
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
        },
        pageLength: {{ $pageLength }},
        order: {!! json_encode($order) !!},
        @if($responsive)
        responsive: true,
        @endif
        @if($scrollX)
        scrollX: true,
        @endif
        @if($scrollY)
        scrollY: '{{ $scrollY }}',
        scrollCollapse: true,
        @endif
        @if(!$paging)
        paging: false,
        @endif
        @if(!$info)
        info: false,
        @endif
        @if(!$search)
        searching: false,
        @endif
        @if($ajax)
        ajax: '{{ $ajax }}',
        processing: true,
        serverSide: {{ $serverSide ? 'true' : 'false' }},
        @endif
        @if($buttons && $export)
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        @endif
    };

    $('#{{ $id }}').DataTable(config);
});
</script>
@endpush

