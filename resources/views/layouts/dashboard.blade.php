<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" data-toggled="close">
<head>
    @include('layouts.partials._head')
</head>

<body data-flash-success="{{ session('success') }}" data-flash-error="{{ session('error') }}" data-flash-warning="{{ session('warning') }}" data-flash-info="{{ session('info') }}" @if($errors->any()) data-validation-errors='@json($errors->all())' @endif>
    @include('layouts.partials._switcher')

    <div class="page">
        @include('layouts.partials._header')
        @include('layouts.partials._sidebar')

        <!-- Start::app-content -->
        <div class="main-content app-content">
            <div class="container-fluid">
                <!-- Start::page-header -->
                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                    <div>
                        <p class="fw-semibold fs-18 mb-0">@yield('title', 'Dashboard')</p>
                        <span class="fs-semibold text-muted">@yield('subtitle', 'Welcome to the admin panel')</span>
                    </div>
                </div>
                <!-- End::page-header -->

                <!-- Page Content -->
                @yield('content')
            </div>
        </div>
        <!-- End::app-content -->

        @include('layouts.partials._footer')
    </div>

    <!-- Scroll To Top -->
    <div class="scrollToTop">
        <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
    </div>
    <div id="responsive-overlay"></div>
    <!-- Scroll To Top -->

    @include('layouts.partials._scripts')
</body>
</html>
