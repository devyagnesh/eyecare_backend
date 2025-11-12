<!-- Meta Data -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Dashboard') | Admin Panel</title>
<meta name="Description" content="Admin Dashboard">

<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">

<!-- Choices JS -->
@if(file_exists(public_path('assets/libs/choices.js/public/assets/scripts/choices.min.js')))
<script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
@endif

<!-- Main Theme Js -->
@if(file_exists(public_path('assets/js/main.js')))
<script src="{{ asset('assets/js/main.js') }}"></script>
@endif

<!-- Bootstrap Css -->
@if(file_exists(public_path('assets/libs/bootstrap/css/bootstrap.min.css')))
<link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
@endif

<!-- Style Css -->
@if(file_exists(public_path('assets/css/styles.css')))
<link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
@endif

<!-- Icons Css -->
@if(file_exists(public_path('assets/css/icons.css')))
<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
@endif

<!-- Node Waves Css -->
@if(file_exists(public_path('assets/libs/node-waves/waves.min.css')))
<link href="{{ asset('assets/libs/node-waves/waves.min.css') }}" rel="stylesheet">
@endif

<!-- Simplebar Css -->
@if(file_exists(public_path('assets/libs/simplebar/simplebar.min.css')))
<link href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">
@endif

<!-- Color Picker Css -->
@if(file_exists(public_path('assets/libs/flatpickr/flatpickr.min.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
@endif
@if(file_exists(public_path('assets/libs/@simonwep/pickr/themes/nano.min.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/@simonwep/pickr/themes/nano.min.css') }}">
@endif

<!-- Choices Css -->
@if(file_exists(public_path('assets/libs/choices.js/public/assets/styles/choices.min.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}">
@endif

<!-- FlatPickr CSS (duplicate removed - already loaded above) -->

<!-- Auto Complete CSS -->
@if(file_exists(public_path('assets/libs/@tarekraafat/autocomplete.js/css/autoComplete.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/@tarekraafat/autocomplete.js/css/autoComplete.css') }}">
@endif

@stack('styles')

