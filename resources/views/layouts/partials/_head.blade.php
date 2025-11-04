<!-- Meta Data -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Dashboard') | Admin Panel</title>
<meta name="Description" content="Admin Dashboard">

<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">

<!-- Main Theme Js -->
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- Bootstrap Css -->
<link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

<!-- Style Css -->
<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">

<!-- Icons Css -->
<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

<!-- Node Waves Css -->
<link href="{{ asset('assets/libs/node-waves/waves.min.css') }}" rel="stylesheet">

<!-- Simplebar Css -->
<link href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">

@stack('styles')

