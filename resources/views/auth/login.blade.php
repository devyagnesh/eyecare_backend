<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Login - Admin Panel</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" />

    <!-- CSS Assets -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    
    <style>
        /* Hide x-cloak elements by default (Alpine.js does this, but we need fallback) */
        [x-cloak] { 
            display: none !important; 
        }
        
        /* Ensure preloader can be hidden */
        .app-preloader {
            transition: opacity 0.3s ease-out;
        }
        
        /* Show root when x-cloak is removed */
        #root:not([x-cloak]) {
            display: flex !important;
        }
    </style>
    
    <script>
        localStorage.getItem("_x_darkMode_on") === "true" &&
            document.documentElement.classList.add("dark");
    </script>
</head>
<body x-data class="is-header-blur" x-bind="$store.global.documentBody">
    <!-- App preloader-->
    <div class="app-preloader fixed z-50 grid h-full w-full place-content-center bg-slate-50 dark:bg-navy-900">
        <div class="app-preloader-inner relative inline-block size-48"></div>
    </div>

    <!-- Page Wrapper -->
    <div id="root" class="min-h-100vh flex grow bg-slate-50 dark:bg-navy-900" x-cloak>
        <main class="grid w-full grow grid-cols-1 place-items-center">
            <div class="w-full max-w-[26rem] p-4 sm:px-5">
                <div class="text-center">
                    <img class="mx-auto size-16" src="{{ asset('assets/images/app-logo.svg') }}" alt="logo" />
                    <div class="mt-4">
                        <h2 class="text-2xl font-semibold text-slate-600 dark:text-navy-100">Welcome Back</h2>
                        <p class="text-slate-400 dark:text-navy-300">Please sign in to continue</p>
                    </div>
                </div>
                <div class="card mt-5 rounded-lg p-5 lg:p-7">
                    @if ($errors->any())
                        <div class="alert flex rounded-lg bg-error/10 text-error px-4 py-3 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="login-form" method="POST" action="{{ route('login') }}">
                        @csrf
                        <label class="block">
                            <span>Email:</span>
                            <span class="relative mt-1.5 flex">
                                <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 placeholder:text-slate-400/70 hover:z-10 hover:border-slate-400 focus:z-10 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent {{ $errors->has('email') ? 'border-error' : '' }}" placeholder="Enter Email" type="email" name="email" value="{{ old('email') }}" required autofocus />
                                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span>
                            </span>
                            @error('email')
                                <span class="mt-1.5 text-xs+ text-error">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="mt-4 block">
                            <span>Password:</span>
                            <span class="relative mt-1.5 flex">
                                <input class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-9 pr-9 placeholder:text-slate-400/70 hover:z-10 hover:border-slate-400 focus:z-10 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent {{ $errors->has('password') ? 'border-error' : '' }}" placeholder="Enter Password" type="password" name="password" id="password" required />
                                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <button type="button" onclick="togglePassword('password', this)" class="absolute right-0 flex h-full w-10 items-center justify-center text-slate-400 hover:text-slate-600 dark:text-navy-300 dark:hover:text-navy-100">
                                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0L18.71 18.71M6.29 6.29L3 3" />
                                    </svg>
                                    <svg id="eye-off-icon" xmlns="http://www.w3.org/2000/svg" class="size-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </span>
                            @error('password')
                                <span class="mt-1.5 text-xs+ text-error">{{ $message }}</span>
                            @enderror
                        </label>
                        <div class="mt-4 flex items-center justify-between space-x-2">
                            <label class="inline-flex items-center space-x-2">
                                <input class="form-checkbox is-basic size-5 rounded border-slate-400/70 checked:border-primary checked:bg-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:border-accent dark:checked:bg-accent dark:hover:border-accent dark:focus:border-accent" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                                <span class="line-clamp-1">Remember me</span>
                            </label>
                        </div>
                        <button type="submit" class="btn mt-5 w-full bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                            Sign In
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Javascript Assets -->
    <script src="{{ asset('assets/js/app.js') }}" defer></script>

    <script>
        // Function to hide preloader
        function hidePreloader() {
            const preloader = document.querySelector('.app-preloader');
            if (preloader && preloader.style.display !== 'none') {
                preloader.style.opacity = '0';
                preloader.style.transition = 'opacity 0.3s ease-out';
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 300);
            }
        }

        // Function to show content
        function showContent() {
            const root = document.getElementById('root');
            if (root) {
                root.removeAttribute('x-cloak');
                root.style.display = 'flex';
            }
        }

        // Show content and hide preloader when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                showContent();
                setTimeout(hidePreloader, 200);
            });
        } else {
            // DOM already loaded
            showContent();
            setTimeout(hidePreloader, 200);
        }

        // Also hide preloader when page is fully loaded
        window.addEventListener('load', function() {
            setTimeout(hidePreloader, 100);
        });
        
        // Fallback: Force show content after 1 second if still hidden
        setTimeout(function() {
            showContent();
            hidePreloader();
        }, 1000);

        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
