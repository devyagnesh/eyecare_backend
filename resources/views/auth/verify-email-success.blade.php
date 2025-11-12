<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Email Verified | Admin Panel</title>
    <link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">
    <script src="{{ asset('assets/js/authentication-main.js') }}"></script>
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    
    <!-- Preloader CSS -->
    @if(file_exists(public_path('assets/css/preloader.css')))
    <link rel="stylesheet" href="{{ asset('assets/css/preloader.css') }}?v={{ filemtime(public_path('assets/css/preloader.css')) }}">
    @endif
</head>

<body class="authentication-background authenticationcover-background position-relative" id="particles-js">
    @include('layouts.partials._preloader')
    <div class="container">
        <div class="row justify-content-center authentication authentication-basic align-items-center h-100">
            <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
                <div class="mb-3 d-flex justify-content-center auth-logo">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
                    </a>
                </div>
                <div class="card custom-card my-4 border z-3 position-relative">
                    <div class="card-body p-0">
                        <div class="p-5 text-center">
                            <div class="mb-4">
                                <i class="ri-checkbox-circle-line text-success" style="font-size: 64px;"></i>
                            </div>
                            <p class="h4 fw-semibold mb-2">Email Verified Successfully!</p>
                            <p class="text-muted mb-4">Your email has been verified. You can now sign in to your account.</p>
                            <div class="d-grid">
                                <a href="{{ route('login') }}" class="btn btn-primary">Go to Sign In</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- Preloader JS -->
    @if(file_exists(public_path('assets/js/preloader.js')))
    <script src="{{ asset('assets/js/preloader.js') }}?v={{ filemtime(public_path('assets/js/preloader.js')) }}"></script>
    @endif
    
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>
</html>

