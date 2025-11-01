<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    
    <title>Verification Failed - Admin Panel</title>
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
                        <div class="mx-auto mb-4 flex size-20 items-center justify-center rounded-full bg-error/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold text-slate-600 dark:text-navy-100">Verification Failed</h2>
                        <p class="mt-1 text-sm text-slate-400 dark:text-navy-300">The verification link is invalid or has expired.</p>
                    </div>
                </div>
                <div class="card mt-5 rounded-lg p-5 lg:p-7">
                    <div class="alert flex rounded-lg bg-warning/10 px-4 py-3 text-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-left">
                            <h6 class="font-medium mb-2">Link Expired</h6>
                            <p class="text-sm">Email verification links expire after 60 minutes for security reasons. Please request a new verification email.</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('login') }}" class="btn w-full bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                            Go to Login
                        </a>
                    </div>
                    <div class="mt-4 text-center text-xs text-slate-400 dark:text-navy-300">
                        <p>Need help? Contact support if you continue to experience issues.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Javascript Assets -->
    <script src="{{ asset('assets/js/app.js') }}" defer></script>

    <!-- Global Preloader and x-cloak Handler -->
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
    </script>
</body>
</html>
