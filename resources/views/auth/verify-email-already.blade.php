<!DOCTYPE html>
<html lang="en" dir="ltr" data-theme-mode="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Already Verified - Admin Panel</title>
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
                            <div class="avatar avatar-lg avatar-rounded bg-info-transparent mx-auto d-flex align-items-center justify-content-center">
                                <i class="bx bx-info-circle fs-32 text-info"></i>
                            </div>
                        </div>
                        <h5 class="fw-semibold mb-2">Email Already Verified</h5>
                        <p class="text-muted mb-4">Your email address has already been verified. You're all set! You can now log in and start using the system.</p>
                        
                        <div class="alert alert-success mb-4">
                            <div class="d-flex align-items-start">
                                <i class="bx bx-check-circle fs-18 me-2 mt-1"></i>
                                <div class="text-start">
                                    <h6 class="fw-semibold mb-1">Account Ready</h6>
                                    <p class="mb-0 fs-12">Your account is verified and ready to use. Log in to access all features.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <a href="{{ route('login') }}" class="btn btn-lg btn-primary">
                                Go to Login
                            </a>
                        </div>
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
