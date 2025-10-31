<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">
<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verification Failed - Eyecare Management System</title>

    <!-- Favicon -->
    @if(file_exists(public_path('assets/images/brand-logos/favicon.ico')))
        <link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">
    @endif

    <!-- Main Theme Js -->
    <script src="{{ asset('assets/js/authentication-main.js') }}"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Style Css -->
    <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
            <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
                <div class="my-5 d-flex justify-content-center">
                    @if(file_exists(public_path('assets/images/brand-logos/desktop-logo.png')))
                        <a href="{{ route('login') }}">
                            <img src="{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
                            <img src="{{ asset('assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
                        </a>
                    @else
                        <h2 class="fw-bold"><span style="color: #667eea;">👓</span> Eyecare Management</h2>
                    @endif
                </div>
                <div class="card custom-card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="avatar avatar-lg avatar-rounded bg-danger-transparent mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                <i class="ri-error-warning-fill fs-48 text-danger"></i>
                            </div>
                            <p class="h4 fw-semibold mb-2 text-danger">Verification Failed</p>
                            <p class="mb-3 text-muted op-7 fw-normal">The verification link is invalid or has expired.</p>
                            
                            <div class="alert alert-warning border-warning mb-4 text-start" role="alert">
                                <h6 class="alert-heading mb-2"><i class="ri-time-line me-2"></i>Link Expired</h6>
                                <p class="mb-0" style="font-size: 14px;">Email verification links expire after 60 minutes for security reasons. Please request a new verification email.</p>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-lg btn-primary">
                                <i class="ri-login-box-line me-1 align-middle"></i> Go to Login
                            </a>
                        </div>
                        <div class="text-center mt-4">
                            <p class="text-muted mb-0" style="font-size: 13px;">
                                Need help? Contact support if you continue to experience issues.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
