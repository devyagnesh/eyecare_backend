<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" data-toggled="close">
<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin Panel</title>

    <!-- Favicon -->
    @if(file_exists(public_path('assets/images/brand-logos/favicon.ico')))
        <link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">
    @endif

    <!-- Main Theme Js (must be loaded first) -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Choices JS -->
    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Style Css -->
    <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

    <!-- Node Waves Css -->
    <link href="{{ asset('assets/libs/node-waves/waves.min.css') }}" rel="stylesheet">

    <!-- Simplebar Css -->
    <link href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">

    <!-- Color Picker CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/@simonwep/pickr/themes/nano.min.css') }}">

    @stack('styles')
</head>

<body>
    <!-- Start Switcher (hidden, required by scripts) -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="switcher-canvas" aria-labelledby="offcanvasRightLabel" style="display: none !important;">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title text-default" id="offcanvasRightLabel">Switcher</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <nav class="border-bottom border-block-end-dashed">
                <div class="nav nav-tabs nav-justified" id="switcher-main-tab" role="tablist">
                    <button class="nav-link active" id="switcher-home-tab" data-bs-toggle="tab" data-bs-target="#switcher-home" type="button" role="tab" aria-controls="switcher-home" aria-selected="true">Theme Styles</button>
                    <button class="nav-link" id="switcher-profile-tab" data-bs-toggle="tab" data-bs-target="#switcher-profile" type="button" role="tab" aria-controls="switcher-profile" aria-selected="false">Theme Colors</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active border-0" id="switcher-home" role="tabpanel" aria-labelledby="switcher-home-tab" tabindex="0">
                    <div>
                        <p class="switcher-style-head">Theme Color Mode:</p>
                        <div class="row switcher-style">
                            <div class="col-sm-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-light-theme">Light</label>
                                    <input class="form-check-input" type="radio" name="theme-style" id="switcher-light-theme" checked>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-dark-theme">Dark</label>
                                    <input class="form-check-input" type="radio" name="theme-style" id="switcher-dark-theme">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade border-0" id="switcher-profile" role="tabpanel" aria-labelledby="switcher-profile-tab" tabindex="0">
                    <div>
                        <div class="theme-colors">
                            <p class="switcher-style-head">Menu Colors:</p>
                            <div class="d-flex switcher-style pb-2">
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Light Menu" type="radio" name="menu-colors" id="switcher-menu-light" checked>
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Dark Menu" type="radio" name="menu-colors" id="switcher-menu-dark">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Color Menu" type="radio" name="menu-colors" id="switcher-menu-primary">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-gradient" data-bs-toggle="tooltip" data-bs-placement="top" title="Gradient Menu" type="radio" name="menu-colors" id="switcher-menu-gradient">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-transparent" data-bs-toggle="tooltip" data-bs-placement="top" title="Transparent Menu" type="radio" name="menu-colors" id="switcher-menu-transparent">
                                </div>
                            </div>
                        </div>
                        <div class="theme-colors">
                            <p class="switcher-style-head">Header Colors:</p>
                            <div class="d-flex switcher-style pb-2">
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-white" data-bs-toggle="tooltip" data-bs-placement="top" title="Light Header" type="radio" name="header-colors" id="switcher-header-light" checked>
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Dark Header" type="radio" name="header-colors" id="switcher-header-dark">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Color Header" type="radio" name="header-colors" id="switcher-header-primary">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-gradient" data-bs-toggle="tooltip" data-bs-placement="top" title="Gradient Header" type="radio" name="header-colors" id="switcher-header-gradient">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-transparent" data-bs-toggle="tooltip" data-bs-placement="top" title="Transparent Header" type="radio" name="header-colors" id="switcher-header-transparent">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="theme-colors">
                        <p class="switcher-style-head">Theme Primary:</p>
                        <div class="d-flex flex-wrap align-items-center switcher-style">
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-primary-1" type="radio" name="theme-primary" id="switcher-primary">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-primary-2" type="radio" name="theme-primary" id="switcher-primary1">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-primary-3" type="radio" name="theme-primary" id="switcher-primary2">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-primary-4" type="radio" name="theme-primary" id="switcher-primary3">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-primary-5" type="radio" name="theme-primary" id="switcher-primary4">
                            </div>
                            <div class="form-check switch-select ps-0 mt-1 color-primary-light">
                                <div class="theme-container-primary"></div>
                                <div class="pickr-container-primary"></div>
                            </div>
                        </div>
                    </div>
                    <div class="theme-colors">
                        <p class="switcher-style-head">Theme Background:</p>
                        <div class="d-flex flex-wrap align-items-center switcher-style">
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-bg-1" type="radio" name="theme-background" id="switcher-background" checked>
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-bg-2" type="radio" name="theme-background" id="switcher-background1">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-bg-3" type="radio" name="theme-background" id="switcher-background2">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-bg-4" type="radio" name="theme-background" id="switcher-background3">
                            </div>
                            <div class="form-check switch-select me-3">
                                <input class="form-check-input color-input color-bg-5" type="radio" name="theme-background" id="switcher-background4">
                            </div>
                            <div class="form-check switch-select ps-0 mt-1 tooltip-static-demo color-bg-transparent">
                                <div class="theme-container-background"></div>
                                <div class="pickr-container-background"></div>
                            </div>
                        </div>
                    </div>
                    <div class="menu-image mb-3">
                        <p class="switcher-style-head">Menu With Background Image:</p>
                        <div class="d-flex flex-wrap align-items-center switcher-style">
                            <div class="form-check switch-select m-2">
                                <input class="form-check-input bgimage-input bg-img1" type="radio" name="theme-background" id="switcher-bg-img" checked>
                            </div>
                            <div class="form-check switch-select m-2">
                                <input class="form-check-input bgimage-input bg-img2" type="radio" name="theme-background" id="switcher-bg-img1">
                            </div>
                            <div class="form-check switch-select m-2">
                                <input class="form-check-input bgimage-input bg-img3" type="radio" name="theme-background" id="switcher-bg-img2">
                            </div>
                            <div class="form-check switch-select m-2">
                                <input class="form-check-input bgimage-input bg-img4" type="radio" name="theme-background" id="switcher-bg-img3">
                            </div>
                            <div class="form-check switch-select m-2">
                                <input class="form-check-input bgimage-input bg-img5" type="radio" name="theme-background" id="switcher-bg-img4">
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="switcher-style-head">Loader:</p>
                        <div class="row switcher-style">
                            <div class="col-sm-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-loader-enable">Enable</label>
                                    <input class="form-check-input" type="radio" name="page-loader" id="switcher-loader-enable">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-loader-disable">Disable</label>
                                    <input class="form-check-input" type="radio" name="page-loader" id="switcher-loader-disable" checked>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid canvas-footer">
                        <a href="javascript:void(0);" id="reset-all" class="btn btn-danger m-1">Reset</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Switcher -->

    <!-- Loader -->
    <div id="loader">
        <img src="{{ asset('assets/images/media/loader.svg') }}" alt="">
    </div>

    <div class="page">
        <!-- app-header -->
        <header class="app-header">
            <div class="main-header-container container-fluid">
                <div class="header-content-left">
                    <div class="header-element">
                        <div class="horizontal-logo">
                            <a href="{{ route('dashboard') }}" class="header-logo">
                                @if(file_exists(public_path('assets/images/brand-logos/desktop-logo.png')))
                                    <img src="{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
                                    <img src="{{ asset('assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
                                    <img src="{{ asset('assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
                                @else
                                    <span class="fw-bold text-dark">Admin Panel</span>
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="header-element">
                        <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" href="javascript:void(0);"><span></span></a>
                    </div>
                </div>

                <div class="header-content-right">
                    <!-- Start::header-element -->
                    <div class="header-element header-theme-mode">
                        <!-- Start::header-link|layout-setting -->
                        <a href="javascript:void(0);" class="header-link layout-setting">
                            <span class="light-layout">
                                <i class="bx bx-moon header-link-icon"></i>
                            </span>
                            <span class="dark-layout">
                                <i class="bx bx-sun header-link-icon"></i>
                            </span>
                        </a>
                        <!-- End::header-link|layout-setting -->
                    </div>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <div class="header-element">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside" data-bs-toggle="dropdown">
                                <div class="d-flex align-items-center">
                                    <span class="avatar avatar-sm avatar-rounded me-2 bg-primary-transparent">
                                        <i class="ri-user-line"></i>
                                    </span>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-0 fw-semibold">{{ Auth::user()->name }}</h6>
                                        <span class="text-muted fs-11 d-md-none d-lg-inline">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                            </a>
                            <ul class="main-header-dropdown dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item d-flex" href="javascript:void(0);">
                                        <i class="ri-user-line me-2"></i>
                                        <span>Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex" href="javascript:void(0);">
                                        <i class="ri-settings-3-line me-2"></i>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex">
                                            <i class="ri-logout-box-line me-2"></i>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- End::header-element -->
                </div>
            </div>
        </header>
        <!-- /app-header -->

        <!-- Start::app-sidebar -->
        <aside class="app-sidebar sticky" id="sidebar">

            <!-- Start::main-sidebar-header -->
            <div class="main-sidebar-header">
                <a href="{{ route('dashboard') }}" class="header-logo">
                    @if(file_exists(public_path('assets/images/brand-logos/desktop-logo.png')))
                        <img src="{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
                        <img src="{{ asset('assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
                        <img src="{{ asset('assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
                        <img src="{{ asset('assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
                        <img src="{{ asset('assets/images/brand-logos/desktop-white.png') }}" alt="logo" class="desktop-white">
                        <img src="{{ asset('assets/images/brand-logos/toggle-white.png') }}" alt="logo" class="toggle-white">
                    @else
                        <span class="fw-bold text-dark">Admin Panel</span>
                    @endif
                </a>
            </div>
            <!-- End::main-sidebar-header -->

            <!-- Start::main-sidebar -->
            <div class="main-sidebar" id="sidebar-scroll">

                <!-- Start::nav -->
                <nav class="main-menu-container nav nav-pills flex-column sub-open">
                    <div class="slide-left" id="slide-left">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
                    </div>
                    <ul class="main-menu">
                        <!-- Start::slide__category -->
                        <li class="slide__category"><span class="category-name">Main</span></li>
                        <!-- End::slide__category -->

                        <!-- Start::slide -->
                        <li class="slide">
                            <a href="{{ route('dashboard') }}" class="side-menu__item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="bx bx-home side-menu__icon"></i>
                                <span class="side-menu__label">Dashboard</span>
                            </a>
                        </li>
                        <!-- End::slide -->

                        <!-- Start::slide__category -->
                        <li class="slide__category"><span class="category-name">User Management</span></li>
                        <!-- End::slide__category -->

                        <!-- Start::slide -->
                        <li class="slide">
                            <a href="{{ route('admin.users.index') }}" class="side-menu__item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="ri-user-line side-menu__icon"></i>
                                <span class="side-menu__label">Users</span>
                            </a>
                        </li>
                        <!-- End::slide -->

                        <!-- Start::slide -->
                        <li class="slide">
                            <a href="{{ route('admin.roles.index') }}" class="side-menu__item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                <i class="ri-shield-user-line side-menu__icon"></i>
                                <span class="side-menu__label">Roles</span>
                            </a>
                        </li>
                        <!-- End::slide -->

                        <!-- Start::slide -->
                        <li class="slide">
                            <a href="{{ route('admin.permissions.index') }}" class="side-menu__item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                                <i class="ri-lock-password-line side-menu__icon"></i>
                                <span class="side-menu__label">Permissions</span>
                            </a>
                        </li>
                        <!-- End::slide -->

                        <!-- Start::slide__category -->
                        <li class="slide__category"><span class="category-name">API</span></li>
                        <!-- End::slide__category -->

                        <!-- Start::slide -->
                        <li class="slide">
                            <a href="{{ route('admin.api-documentation.index') }}" class="side-menu__item {{ request()->routeIs('admin.api-documentation.*') ? 'active' : '' }}">
                                <i class="ri-book-open-line side-menu__icon"></i>
                                <span class="side-menu__label">API Documentation</span>
                            </a>
                        </li>
                        <!-- End::slide -->
                    </ul>
                    <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
                </nav>
                <!-- End::nav -->

            </div>
            <!-- End::main-sidebar -->

        </aside>
        <!-- End::app-sidebar -->

        <!--APP-CONTENT START-->
        <div class="main-content app-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        <!--APP-CONTENT CLOSE-->

        <!-- Footer Start -->
        <footer class="footer mt-auto py-3 bg-white text-center">
            <div class="container">
                <span class="text-muted">Copyright © <span id="year"></span> <a href="javascript:void(0);" class="text-dark fw-semibold">Admin Panel</a>. All rights reserved.</span>
            </div>
        </footer>
        <!-- Footer End -->
    </div>

    <!-- Scroll To Top -->
    <div class="scrollToTop">
        <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
    </div>
    <div id="responsive-overlay"></div>

    <!-- Popper JS -->
    <script src="{{ asset('assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Defaultmenu JS -->
    <script src="{{ asset('assets/js/defaultmenu.min.js') }}"></script>

    <!-- Node Waves JS -->
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

    <!-- Sticky JS -->
    <script src="{{ asset('assets/js/sticky.js') }}"></script>

    <!-- Simplebar JS -->
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    @if(file_exists(public_path('assets/js/simplebar.js')))
        <script src="{{ asset('assets/js/simplebar.js') }}"></script>
    @endif

    <!-- Override SimpleBar to handle null/invalid elements gracefully -->
    <script>
        (function() {
            if (typeof SimpleBar !== 'undefined') {
                const OriginalSimpleBar = SimpleBar;
                window.SimpleBar = function(element, options) {
                    if (!element || typeof element !== 'object' || !element.appendChild || !element.getElementsByTagName) {
                        // Return a dummy object if element is null/invalid
                        const dummyEl = document.createElement('div');
                        return {
                            recalculate: function() {},
                            getScrollElement: function() { return dummyEl; },
                            getContentElement: function() { return dummyEl; },
                            unMount: function() {}
                        };
                    }
                    try {
                        return new OriginalSimpleBar(element, options);
                    } catch (e) {
                        console.warn('SimpleBar initialization failed:', e);
                        const dummyEl = document.createElement('div');
                        return {
                            recalculate: function() {},
                            getScrollElement: function() { return element || dummyEl; },
                            getContentElement: function() { return element || dummyEl; },
                            unMount: function() {}
                        };
                    }
                };
                // Copy static methods
                if (OriginalSimpleBar.removeObserver) {
                    window.SimpleBar.removeObserver = OriginalSimpleBar.removeObserver;
                }
            }
        })();
    </script>

    <!-- Safety check: Ensure required elements exist before scripts run -->
    <script>
        (function() {
            // Ensure switcher-canvas exists
            if (!document.getElementById('switcher-canvas')) {
                const switcher = document.createElement('div');
                switcher.className = 'offcanvas offcanvas-end';
                switcher.id = 'switcher-canvas';
                switcher.setAttribute('tabindex', '-1');
                switcher.setAttribute('aria-labelledby', 'offcanvasRightLabel');
                switcher.style.display = 'none';
                document.body.appendChild(switcher);
            }
            
            // Ensure slide-right exists
            const nav = document.querySelector('.main-menu-container');
            if (nav && !document.getElementById('slide-right')) {
                const slideRight = document.createElement('div');
                slideRight.className = 'slide-right';
                slideRight.id = 'slide-right';
                slideRight.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path></svg>';
                nav.appendChild(slideRight);
            }
        })();
    </script>

    <!-- Color Picker JS (must load before custom.js) -->
    <script src="{{ asset('assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

    <!-- Custom-switcher JS (with null element protection) -->
    <script>
        // Ensure all required elements exist before custom-switcher.min.js runs
        (function() {
            const requiredIds = [
                'switcher-light-theme', 'switcher-dark-theme',
                'switcher-menu-light', 'switcher-menu-dark', 'switcher-menu-primary', 'switcher-menu-gradient', 'switcher-menu-transparent',
                'switcher-header-light', 'switcher-header-dark', 'switcher-header-primary', 'switcher-header-gradient', 'switcher-header-transparent',
                'switcher-bg-img', 'switcher-bg-img1', 'switcher-bg-img2', 'switcher-bg-img3', 'switcher-bg-img4',
                'switcher-loader-enable', 'switcher-loader-disable'
            ];
            
            requiredIds.forEach(id => {
                if (!document.getElementById(id)) {
                    const el = document.createElement('input');
                    el.type = 'radio';
                    el.id = id;
                    el.style.display = 'none';
                    el.style.position = 'absolute';
                    el.style.width = '1px';
                    el.style.height = '1px';
                    el.style.opacity = '0';
                    el.style.pointerEvents = 'none';
                    document.body.appendChild(el);
                }
            });
        })();
    </script>
    <script src="{{ asset('assets/js/custom-switcher.min.js') }}"></script>

    <!-- Custom JS (with error handling for missing elements) -->
    <script>
        // Add missing header elements before custom.js runs
        (function() {
            const missingElements = [
                'header-shortcut-scroll',
                'header-notification-scroll',
                'header-cart-items-scroll'
            ];
            
            missingElements.forEach(id => {
                if (!document.getElementById(id)) {
                    const el = document.createElement('div');
                    el.id = id;
                    el.style.display = 'none';
                    el.style.visibility = 'hidden';
                    el.style.position = 'absolute';
                    el.style.width = '1px';
                    el.style.height = '1px';
                    document.body.appendChild(el);
                }
            });
        })();
    </script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <!-- Layout-specific JavaScript -->
    <script>
        (function() {
            // Set current year in footer
            const yearElement = document.getElementById('year');
            if (yearElement) {
                yearElement.textContent = new Date().getFullYear();
            }

            // Fix toggleTheme function to handle missing elements
            if (typeof toggleTheme === 'function') {
                const originalToggleTheme = toggleTheme;
                window.toggleTheme = function() {
                    try {
                        return originalToggleTheme.apply(this, arguments);
                    } catch (e) {
                        // Fallback: simple theme toggle without switcher elements
                        const html = document.documentElement;
                        if (html.getAttribute('data-theme-mode') === 'dark') {
                            html.setAttribute('data-theme-mode', 'light');
                            html.setAttribute('data-header-styles', 'light');
                            html.setAttribute('data-menu-styles', 'light');
                            localStorage.removeItem('ynexdarktheme');
                        } else {
                            html.setAttribute('data-theme-mode', 'dark');
                            html.setAttribute('data-header-styles', 'dark');
                            html.setAttribute('data-menu-styles', 'dark');
                            localStorage.setItem('ynexdarktheme', 'true');
                        }
                    }
                };
            }

            // Wait for both DOM and defaultmenu script
            function initSidebarToggle() {
                const sidebarToggle = document.querySelector('.sidemenu-toggle');
                if (sidebarToggle) {
                    // Store original handler if any
                    let originalHandler = null;
                    const toggleClone = sidebarToggle.cloneNode(true);
                    
                    // Remove any existing listeners by replacing the element
                    sidebarToggle.parentNode.replaceChild(toggleClone, sidebarToggle);
                    
                    // Add our click handler that will work reliably
                    toggleClone.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Try to use toggleSidemenu if available
                        if (typeof toggleSidemenu === 'function') {
                            try {
                                toggleSidemenu();
                            } catch (err) {
                                console.warn('toggleSidemenu error:', err);
                                fallbackToggle();
                            }
                        } else {
                            // Use fallback if toggleSidemenu doesn't exist
                            fallbackToggle();
                        }
                    });
                }
            }
            
            // Fallback toggle function
            function fallbackToggle() {
                const html = document.documentElement;
                const sidebar = document.getElementById('sidebar');
                const currentToggled = html.getAttribute('data-toggled');
                const verticalStyle = html.getAttribute('data-vertical-style') || 'overlay';
                
                if (window.innerWidth >= 992) {
                    // Desktop behavior
                    if (verticalStyle === 'overlay') {
                        if (currentToggled === 'icon-overlay-close') {
                            html.removeAttribute('data-toggled');
                        } else {
                            html.setAttribute('data-toggled', 'icon-overlay-close');
                        }
                    } else {
                        // Default behavior for other styles
                        if (currentToggled && currentToggled.includes('close')) {
                            html.removeAttribute('data-toggled');
                        } else {
                            html.setAttribute('data-toggled', 'close-menu-close');
                        }
                    }
                } else {
                    // Mobile behavior
                    if (currentToggled === 'close') {
                        html.setAttribute('data-toggled', 'open');
                        // Show overlay
                        const overlay = document.getElementById('responsive-overlay');
                        if (overlay) {
                            setTimeout(() => overlay.classList.add('active'), 100);
                        }
                    } else {
                        html.setAttribute('data-toggled', 'close');
                        const overlay = document.getElementById('responsive-overlay');
                        if (overlay) {
                            overlay.classList.remove('active');
                        }
                    }
                }
            }

            // Initialize sidebar toggle
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(initSidebarToggle, 200);
                });
            } else {
                // DOM already loaded, wait a bit for scripts
                setTimeout(initSidebarToggle, 200);
            }
            
            // Also handle responsive overlay clicks
            document.addEventListener('click', function(e) {
                const overlay = document.getElementById('responsive-overlay');
                if (overlay && e.target === overlay && overlay.classList.contains('active')) {
                    overlay.classList.remove('active');
                    const html = document.documentElement;
                    html.setAttribute('data-toggled', 'close');
                }
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
