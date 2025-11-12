<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-width="fullwidth" data-menu-styles="light" data-toggled="close">
<head>
    @include('layouts.partials._head')
    
    <!-- Preloader CSS -->
    @if(file_exists(public_path('assets/css/preloader.css')))
    <link rel="stylesheet" href="{{ asset('assets/css/preloader.css') }}?v={{ filemtime(public_path('assets/css/preloader.css')) }}">
    @endif

    <!-- AJAX Loader CSS -->
    @if(file_exists(public_path('assets/css/ajax-loader.css')))
    <link rel="stylesheet" href="{{ asset('assets/css/ajax-loader.css') }}?v={{ filemtime(public_path('assets/css/ajax-loader.css')) }}">
    @endif
</head>

<body>
    @include('layouts.partials._preloader')
    
    @include('layouts.partials._switcher')

    <div class="page">
        @include('layouts.partials._header')
        @include('layouts.partials._sidebar')

        <!-- Start::app-content -->
        <div class="main-content app-content">
            <div class="container-fluid">
                <!-- Start::page-header -->
                @hasSection('page-header')
                    @yield('page-header')
                @else
                <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h1 class="page-title fw-medium fs-18 mb-2">@yield('title', 'Dashboard')</h1>
                        <div>
                            <nav>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">@yield('title', 'Dashboard')</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                @endif
                <!-- End::page-header -->

                <!-- Page Content -->
                @yield('content')
            </div>
        </div>
        <!-- End::app-content -->

        @include('layouts.partials._footer')
    </div>

    <!-- Flash Messages for Toast System -->
    @if(session('success'))
    <script>
        window.flashSuccess = @json(session('success'));
    </script>
    @endif

    @if(session('error'))
    <script>
        window.flashError = @json(session('error'));
    </script>
    @endif

    @if(session('warning'))
    <script>
        window.flashWarning = @json(session('warning'));
    </script>
    @endif

    @if(session('info'))
    <script>
        window.flashInfo = @json(session('info'));
    </script>
    @endif

    @if($errors->any())
    <script>
        window.flashError = @json($errors->all()[0]);
    </script>
    @endif

    <!-- Scroll To Top -->
    <div class="scrollToTop">
        <span class="arrow lh-1"><i class="ti ti-arrow-big-up fs-16"></i></span>
    </div>
    <div id="responsive-overlay"></div>
    <!-- Scroll To Top -->

    <!-- Responsive Search Modal -->
    <div class="modal fade" id="header-responsive-search" tabindex="-1" aria-labelledby="header-responsive-search" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" class="form-control border-end-0" placeholder="Search Anything ..." aria-label="Search Anything ..." aria-describedby="button-addon2">
                        <button class="btn btn-primary" type="button" id="button-addon2"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.partials._scripts')
</body>
</html>

