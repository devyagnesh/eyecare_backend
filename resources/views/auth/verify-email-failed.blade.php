<!DOCTYPE html>
<html lang="en" dir="ltr" data-theme-mode="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verification Failed - Admin Panel</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Style Css -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
                <div class="my-5 d-flex justify-content-center">
                    <a href="{{ route('login') }}">
                        <img src="{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
                        <img src="{{ asset('assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
                    </a>
                </div>
                <div class="card custom-card">
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <div class="avatar avatar-lg avatar-rounded bg-danger-transparent mx-auto d-flex align-items-center justify-content-center">
                                <i class="bx bx-x-circle fs-32 text-danger"></i>
                            </div>
                        </div>
                        <h5 class="fw-semibold mb-2">Verification Failed</h5>
                        <p class="text-muted mb-4">The email verification link is invalid or has expired. Please request a new verification email.</p>
                        
                        <div class="alert alert-danger mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bx bx-error-circle fs-18 me-2 mt-1"></i>
                                <div class="text-start">
                                    <h6 class="fw-semibold mb-1">Verification Error</h6>
                                    <p class="mb-0 fs-12">The verification link you used is no longer valid. This could happen if the link has expired or has already been used.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-lg btn-primary">
                                Go to Login
                            </a>
                            @auth
                            <form action="{{ route('verification.resend') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-lg btn-outline-secondary w-100">
                                    Resend Verification Email
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-lg btn-outline-secondary">
                                Login to Resend Verification Email
                            </a>
                            @endauth
                        </div>
                        
                        @if(session('success'))
                        <div class="alert alert-success mt-3 mb-0">
                            <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                        </div>
                        @endif
                        
                        @if(session('error'))
                        <div class="alert alert-danger mt-3 mb-0">
                            <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- Main Theme JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
