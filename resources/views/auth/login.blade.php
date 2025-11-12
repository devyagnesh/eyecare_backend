<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">
<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In | Admin Panel</title>
    <meta name="Description" content="Admin Dashboard Login">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">

    <!-- Main Theme Js -->
    <script src="{{ asset('assets/js/authentication-main.js') }}"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Style Css -->
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">

    <!-- Icons Css -->
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
                        <div class="p-5">
                            <div class="d-flex align-items-center justify-content-center mb-3">
                                <span class="auth-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" id="password"><path fill="#6446fe" d="M59,8H5A1,1,0,0,0,4,9V55a1,1,0,0,0,1,1H59a1,1,0,0,0,1-1V9A1,1,0,0,0,59,8ZM58,54H6V10H58Z" class="color1d1f47 svgShape"></path><path fill="#6446fe" d="M36,35H28a3,3,0,0,1-3-3V27a3,3,0,0,1,3-3h8a3,3,0,0,1,3,3v5A3,3,0,0,1,36,35Zm-8-9a1,1,0,0,0-1,1v5a1,1,0,0,0,1,1h8a1,1,0,0,0,1-1V27a1,1,0,0,0-1-1Z" class="color0055ff svgShape"></path><path fill="#6446fe" d="M36 26H28a1 1 0 0 1-1-1V24a5 5 0 0 1 10 0v1A1 1 0 0 1 36 26zm-7-2h6a3 3 0 0 0-6 0zM32 31a1 1 0 0 1-1-1V29a1 1 0 0 1 2 0v1A1 1 0 0 1 32 31z" class="color0055ff svgShape"></path></svg>
                                </span>
                            </div>
                            <p class="h4 fw-semibold mb-0 text-center">Sign In</p>
                            <p class="mb-3 text-muted fw-normal text-center">Welcome back!</p>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" data-ajax="true" data-force-redirect="true">
                                @csrf
                                <div class="row gy-3">
                                    <div class="col-xl-12">
                                        <label for="email" class="form-label text-default">Email</label>
                                        <div class="position-relative">
                                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter Email" required autofocus>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 mb-2">
                                        <label for="password" class="form-label text-default d-block">
                                            Password
                                            @if(Route::has('password.request'))
                                                <a href="{{ route('password.request') }}" class="float-end link-danger op-5 fw-medium fs-12">Forget password?</a>
                                            @endif
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter Password" required>
                                            <a href="javascript:void(0);" class="show-password-button text-muted" onclick="createpassword('password',this)" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></a>
                                        </div>
                                        <div class="mt-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label text-muted fw-normal fs-12" for="remember">Remember password?</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary">Sign In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- Preloader JS -->
    @if(file_exists(public_path('assets/js/preloader.js')))
    <script src="{{ asset('assets/js/preloader.js') }}?v={{ filemtime(public_path('assets/js/preloader.js')) }}"></script>
    @endif
    
    <!-- Custom JS -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>
</html>

