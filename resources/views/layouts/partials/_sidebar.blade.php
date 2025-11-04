<!-- Start::app-sidebar -->
<aside class="app-sidebar sticky" id="sidebar">
    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="{{ route('dashboard') }}" class="header-logo">
            <img src="{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset('assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
            <img src="{{ asset('assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
            <img src="{{ asset('assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
            <img src="{{ asset('assets/images/brand-logos/desktop-white.png') }}" alt="logo" class="desktop-white">
            <img src="{{ asset('assets/images/brand-logos/toggle-white.png') }}" alt="logo" class="toggle-white">
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
                <li class="slide__category"><span class="category-name">Main Menu</span></li>
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
                        <i class="bx bx-user side-menu__icon"></i>
                        <span class="side-menu__label">Users</span>
                        @if(request()->routeIs('admin.users.*'))
                        <span class="badge bg-primary-transparent rounded-pill ms-auto">{{ \App\Models\User::count() }}</span>
                        @endif
                    </a>
                </li>
                <!-- End::slide -->

                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('admin.roles.index') }}" class="side-menu__item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="bx bx-shield side-menu__icon"></i>
                        <span class="side-menu__label">Roles</span>
                        @if(request()->routeIs('admin.roles.*'))
                        <span class="badge bg-success-transparent rounded-pill ms-auto">{{ \App\Models\Role::where('is_active', true)->count() }}</span>
                        @endif
                    </a>
                </li>
                <!-- End::slide -->

                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('admin.permissions.index') }}" class="side-menu__item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                        <i class="bx bx-check-circle side-menu__icon"></i>
                        <span class="side-menu__label">Permissions</span>
                        @if(request()->routeIs('admin.permissions.*'))
                        <span class="badge bg-warning-transparent rounded-pill ms-auto">{{ \App\Models\Permission::where('is_active', true)->count() }}</span>
                        @endif
                    </a>
                </li>
                <!-- End::slide -->

                <!-- Start::slide__category -->
                <li class="slide__category"><span class="category-name">Documentation</span></li>
                <!-- End::slide__category -->

                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{ route('admin.api-documentation.index') }}" class="side-menu__item {{ request()->routeIs('admin.api-documentation.*') ? 'active' : '' }}">
                        <i class="bx bx-book side-menu__icon"></i>
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
