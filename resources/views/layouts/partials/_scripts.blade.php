<!-- Popper JS -->
@if(file_exists(public_path('assets/libs/@popperjs/core/umd/popper.min.js')))
<script src="{{ asset('assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>
@endif

<!-- Bootstrap JS -->
@if(file_exists(public_path('assets/libs/bootstrap/js/bootstrap.bundle.min.js')))
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@endif

<!-- Defaultmenu JS -->
@if(file_exists(public_path('assets/js/defaultmenu.min.js')))
<script src="{{ asset('assets/js/defaultmenu.min.js') }}"></script>
@endif

<!-- Node Waves JS-->
@if(file_exists(public_path('assets/libs/node-waves/waves.min.js')))
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
@endif

<!-- Sticky JS -->
@if(file_exists(public_path('assets/js/sticky.js')))
<script src="{{ asset('assets/js/sticky.js') }}"></script>
@endif

<!-- Simplebar JS -->
@if(file_exists(public_path('assets/libs/simplebar/simplebar.min.js')))
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
@endif
@if(file_exists(public_path('assets/js/simplebar.js')))
<script src="{{ asset('assets/js/simplebar.js') }}"></script>
@endif

<!-- Auto Complete JS -->
@if(file_exists(public_path('assets/libs/@tarekraafat/autocomplete.js/autoComplete.min.js')))
<script src="{{ asset('assets/libs/@tarekraafat/autocomplete.js/autoComplete.min.js') }}"></script>
@endif

<!-- Color Picker JS -->
@if(file_exists(public_path('assets/libs/@simonwep/pickr/pickr.es5.min.js')))
<script src="{{ asset('assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>
@endif

<!-- Date & Time Picker JS -->
@if(file_exists(public_path('assets/libs/flatpickr/flatpickr.min.js')))
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
@endif

<!-- jQuery (MUST LOAD FIRST - required for AJAX, DataTables, and all custom scripts) -->
@if(file_exists(public_path('assets/libs/jquery/jquery.min.js')))
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
@elseif(file_exists(public_path('assets/libs/jquery/jquery-3.7.1.min.js')))
    <script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}"></script>
@elseif(file_exists(public_path('assets/libs/jquery/jquery.js')))
    <script src="{{ asset('assets/libs/jquery/jquery.js') }}"></script>
@endif

<!-- Custom JS -->
@if(file_exists(public_path('assets/js/custom.js')))
<script src="{{ asset('assets/js/custom.js') }}"></script>
@endif

<!-- Custom-Switcher JS -->
@if(file_exists(public_path('assets/js/custom-switcher.min.js')))
<script src="{{ asset('assets/js/custom-switcher.min.js') }}"></script>
@endif

<!-- SweetAlert2 (for beautiful notifications and confirmations) -->
@if(file_exists(public_path('assets/libs/sweetalert2/sweetalert2.min.css')))
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
@endif
@if(file_exists(public_path('assets/libs/sweetalert2/sweetalert2.all.min.js')))
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
@endif

<!-- Preloader JS - Must load early -->
@if(file_exists(public_path('assets/js/preloader.js')))
    <script src="{{ asset('assets/js/preloader.js') }}?v={{ filemtime(public_path('assets/js/preloader.js')) }}"></script>
@endif

<!-- Toast Notifications (Global toast system) - Must load before AJAX utils -->
@if(file_exists(public_path('assets/js/toast-notifications.js')))
    <script src="{{ asset('assets/js/toast-notifications.js') }}?v={{ filemtime(public_path('assets/js/toast-notifications.js')) }}"></script>
@endif

<!-- Main Application JS (Core AJAX system - Must load first) -->
@if(file_exists(public_path('assets/js/app.js')))
    <script src="{{ asset('assets/js/app.js') }}?v={{ filemtime(public_path('assets/js/app.js')) }}"></script>
@endif

<!-- AJAX Utilities (Legacy support - uses app.js) -->
@if(file_exists(public_path('assets/js/ajax-utils.js')))
    <script src="{{ asset('assets/js/ajax-utils.js') }}?v={{ filemtime(public_path('assets/js/ajax-utils.js')) }}"></script>
@endif

<!-- AJAX Filters (for filter forms without page refresh) -->
@if(file_exists(public_path('assets/js/ajax-filters.js')))
    <script src="{{ asset('assets/js/ajax-filters.js') }}?v={{ filemtime(public_path('assets/js/ajax-filters.js')) }}"></script>
@endif

<!-- DataTables Core (required for DataTables Bootstrap 5) -->
@if(file_exists(public_path('assets/libs/datatables.net/js/jquery.dataTables.min.js')))
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
@endif

<!-- Page-specific scripts -->
@stack('scripts')

