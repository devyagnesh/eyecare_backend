<!-- Popper JS -->
<script src="{{ asset('assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Defaultmenu JS -->
<script src="{{ asset('assets/js/defaultmenu.min.js') }}"></script>

<!-- Node Waves JS-->
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

<!-- Sticky JS -->
<script src="{{ asset('assets/js/sticky.js') }}"></script>

<!-- Simplebar JS -->
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/simplebar.js') }}"></script>

<!-- Custom Switcher JS -->
<script src="{{ asset('assets/js/custom-switcher.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assets/js/custom.js') }}"></script>

<!-- jQuery (required for AJAX and DataTables) -->
@if(file_exists(public_path('assets/libs/jquery/jquery.min.js')))
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
@elseif(file_exists(public_path('assets/libs/jquery/jquery-3.7.1.min.js')))
    <script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}"></script>
@endif

<!-- SweetAlert2 (for beautiful notifications and confirmations) -->
@if(file_exists(public_path('assets/libs/sweetalert2/sweetalert2.min.css')))
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
@endif
@if(file_exists(public_path('assets/libs/sweetalert2/sweetalert2.all.min.js')))
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
@endif

<!-- Toast Notifications (Global toast system) -->
@if(file_exists(public_path('assets/js/toast-notifications.js')))
    <script src="{{ asset('assets/js/toast-notifications.js') }}"></script>
@endif

<!-- AJAX Utilities (for better UI/UX) -->
@if(file_exists(public_path('assets/js/ajax-utils.js')))
    <script src="{{ asset('assets/js/ajax-utils.js') }}"></script>
@endif

@stack('scripts')

