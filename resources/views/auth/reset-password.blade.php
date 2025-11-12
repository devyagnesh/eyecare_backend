<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password | Admin Panel</title>
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
                        <div class="p-5">
                            <p class="h4 fw-semibold mb-0 text-center">Reset Password</p>
                            <p class="mb-3 text-muted fw-normal text-center">Enter your new password</p>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                                <div class="row gy-3">
                                    <div class="col-xl-12">
                                        <label for="email" class="form-label text-default">Email</label>
                                        <div class="position-relative">
                                            <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{ $email ?? old('email') }}" placeholder="Enter Email" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="password" class="form-label text-default">New Password</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter New Password" required>
                                            <a href="javascript:void(0);" class="show-password-button text-muted" onclick="createpassword('password',this)"><i class="ri-eye-off-line align-middle"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="password_confirmation" class="form-label text-default">Confirm Password</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" placeholder="Confirm New Password" required>
                                            <a href="javascript:void(0);" class="show-password-button text-muted" onclick="createpassword('password_confirmation',this)"><i class="ri-eye-off-line align-middle"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary">Reset Password</button>
                                </div>
                                <div class="text-center mb-0 mt-3">
                                    <p class="text-muted mb-0">Remember your password? <a href="{{ route('login') }}" class="text-primary">Sign In</a></p>
                                </div>
                            </form>
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

