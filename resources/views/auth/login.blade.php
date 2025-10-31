<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">
<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Admin Panel</title>

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
                        <h2 class="fw-bold">Admin Panel</h2>
                    @endif
                </div>
                <div class="card custom-card">
                    <div class="card-body p-5">
                        <p class="h5 fw-semibold mb-2 text-center">Sign In</p>
                        <p class="mb-4 text-muted op-7 fw-normal text-center">Welcome back!</p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="login-form" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="row gy-3">
                                <div class="col-xl-12">
                                    <label for="email" class="form-label text-default">Email Address</label>
                                    <input type="email" 
                                           class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           placeholder="Enter your email" 
                                           required 
                                           autofocus>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="error-message text-danger" id="email-error"></div>
                                </div>
                                <div class="col-xl-12 mb-2">
                                    <label for="password" class="form-label text-default d-block">Password</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Enter your password" 
                                               required>
                                        <button class="btn btn-light" type="button" onclick="createpassword('password',this)" id="button-addon2">
                                            <i class="ri-eye-off-line align-middle"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="error-message text-danger" id="password-error"></div>
                                    <div class="mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="defaultCheck1" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label text-muted fw-normal" for="defaultCheck1">
                                                Remember password ?
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 d-grid mt-2">
                                    <button type="submit" class="btn btn-lg btn-primary" id="login-btn">
                                        Sign In
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Show Password JS -->
    <script src="{{ asset('assets/js/show-password.js') }}"></script>

    <!-- jQuery (if available) -->
    @if(file_exists(public_path('assets/libs/jquery/jquery.min.js')))
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    @endif

    <!-- jQuery Validation (if available) -->
    @if(file_exists(public_path('assets/libs/jquery-validation/jquery.validate.min.js')))
        <script src="{{ asset('assets/libs/jquery-validation/jquery.validate.min.js') }}"></script>
    @endif

    <!-- Login Form Validation -->
    <script>
        (function() {
            'use strict';
            
            // Wait for jQuery if it's loaded asynchronously
            function initValidation() {
                if (typeof $ !== 'undefined' && typeof $.fn.validate !== 'undefined') {
                    $('#login-form').validate({
                        rules: {
                            email: {
                                required: true,
                                email: true
                            },
                            password: {
                                required: true,
                                minlength: 6
                            }
                        },
                        messages: {
                            email: {
                                required: 'Please enter your email address',
                                email: 'Please enter a valid email address'
                            },
                            password: {
                                required: 'Please enter your password',
                                minlength: 'Password must be at least 6 characters'
                            }
                        },
                        errorPlacement: function(error, element) {
                            const errorId = element.attr('id') + '-error';
                            error.appendTo('#' + errorId);
                        },
                        submitHandler: function(form) {
                            $('#login-btn').prop('disabled', true).text('Signing in...');
                            form.submit();
                        }
                    });
                } else {
                    // Fallback vanilla JS validation
                    const form = document.getElementById('login-form');
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            let isValid = true;
                            
                            // Clear previous errors
                            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
                            
                            // Validate email
                            const email = document.getElementById('email').value.trim();
                            if (!email) {
                                document.getElementById('email-error').textContent = 'Please enter your email address';
                                isValid = false;
                            } else if (!/\S+@\S+\.\S+/.test(email)) {
                                document.getElementById('email-error').textContent = 'Please enter a valid email address';
                                isValid = false;
                            }
                            
                            // Validate password
                            const password = document.getElementById('password').value;
                            if (!password) {
                                document.getElementById('password-error').textContent = 'Please enter your password';
                                isValid = false;
                            } else if (password.length < 6) {
                                document.getElementById('password-error').textContent = 'Password must be at least 6 characters';
                                isValid = false;
                            }
                            
                            if (!isValid) {
                                e.preventDefault();
                                return false;
                            }
                            
                            document.getElementById('login-btn').disabled = true;
                            document.getElementById('login-btn').textContent = 'Signing in...';
                        });
                    }
                }
            }
            
            // Try to initialize immediately, or wait for DOM
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initValidation);
            } else {
                initValidation();
            }
            
            // Also try after a short delay in case jQuery loads asynchronously
            setTimeout(initValidation, 100);
        })();
    </script>
</body>
</html>