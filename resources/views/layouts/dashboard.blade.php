<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', 'Dashboard') - Admin Panel</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" />

    <!-- CSS Assets -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    
    <!-- Additional Styles (DataTables, etc.) -->
    @stack('styles')
    
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
        /**
         * THIS SCRIPT REQUIRED FOR PREVENT FLICKERING IN SOME BROWSERS
         */
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
        <!-- Sidebar -->
        <div class="sidebar print:hidden">
            <!-- Main Sidebar -->
            <div class="main-sidebar">
                <div class="flex h-full w-full flex-col items-center border-r border-slate-150 bg-white dark:border-navy-700 dark:bg-navy-800">
                    <!-- Application Logo -->
                    <div class="flex pt-4">
                        <a href="{{ route('dashboard') }}">
                            <img class="size-11 transition-transform duration-500 ease-in-out hover:rotate-[360deg]" src="{{ asset('assets/images/app-logo.svg') }}" alt="logo" />
                        </a>
                    </div>

                    <!-- Main Sections Links -->
                    <div class="is-scrollbar-hidden flex grow flex-col space-y-4 overflow-y-auto pt-6">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25' }}" x-tooltip.placement.right="'Dashboard'">
                            <svg class="size-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path fill="currentColor" fill-opacity=".3" d="M5 14.059c0-1.01 0-1.514.222-1.945.221-.43.632-.724 1.453-1.31l4.163-2.974c.56-.4.842-.601 1.162-.601.32 0 .601.2 1.162.601l4.163 2.974c.821.586 1.232.88 1.453 1.31.222.43.222.935.222 1.945V19c0 .943 0 1.414-.293 1.707C18.414 21 17.943 21 17 21H7c-.943 0-1.414 0-1.707-.293C5 20.414 5 19.943 5 19v-4.94Z" />
                                <path fill="currentColor" d="M3 12.387c0 .267 0 .4.084.441.084.041.19-.04.4-.204l7.288-5.669c.59-.459.885-.688 1.228-.688.343 0 .638.23 1.228.688l7.288 5.669c.21.163.316.245.4.204.084-.04.084-.174.084-.441v-.409c0-.48 0-.72-.102-.928-.101-.208-.291-.355-.67-.65l-7-5.445c-.59-.459-.885-.688-1.228-.688-.343 0-.638.23-1.228.688l-7 5.445c-.379.295-.569.442-.67.65-.102.208-.102.448-.102.928v.409Z" />
                                <path fill="currentColor" d="M11.5 15.5h1A1.5 1.5 0 0 1 14 17v3.5h-4V17a1.5 1.5 0 0 1 1.5-1.5Z" />
                            </svg>
                        </a>

                        <!-- User Management -->
                        <a href="{{ route('admin.users.index') }}" class="flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25' }}" x-tooltip.placement.right="'Users'">
                            <svg class="size-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" fill="currentColor" />
                                <path d="M12.0002 14.5C6.99016 14.5 2.95016 17.86 2.95016 22C2.95016 22.28 3.17016 22.5 3.45016 22.5H20.5502C20.8302 22.5 21.0502 22.28 21.0502 22C21.0502 17.86 17.0102 14.5 12.0002 14.5Z" fill="currentColor" fill-opacity="0.3" />
                            </svg>
                        </a>

                        <!-- Roles -->
                        <a href="{{ route('admin.roles.index') }}" class="flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('admin.roles.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25' }}" x-tooltip.placement.right="'Roles'">
                            <svg class="size-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7L12 12L22 7L12 2Z" fill="currentColor" fill-opacity="0.3" />
                                <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Permissions -->
                        <a href="{{ route('admin.permissions.index') }}" class="flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('admin.permissions.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25' }}" x-tooltip.placement.right="'Permissions'">
                            <svg class="size-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="currentColor" fill-opacity="0.3" />
                                <path d="M8 12L10.5 14.5L16 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- API Documentation -->
                        <a href="{{ route('admin.api-documentation.index') }}" class="flex size-11 items-center justify-center rounded-lg {{ request()->routeIs('admin.api-documentation.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25' }}" x-tooltip.placement.right="'API Docs'">
                            <svg class="size-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6.5 2H20V20H6.5A2.5 2.5 0 0 1 4 17.5V4.5A2.5 2.5 0 0 1 6.5 2Z" fill="currentColor" fill-opacity="0.3" />
                            </svg>
                        </a>
                    </div>

                    <!-- Bottom Links -->
                    <div class="flex flex-col items-center space-y-3 py-3">
                        <!-- Settings -->
                        <a href="#" class="flex size-11 items-center justify-center rounded-lg outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25" x-tooltip.placement.right="'Settings'">
                            <svg class="size-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-opacity="0.3" fill="currentColor" d="M2 12.947v-1.771c0-1.047.85-1.913 1.899-1.913 1.81 0 2.549-1.288 1.64-2.868a1.919 1.919 0 0 1 .699-2.607l1.729-.996c.79-.474 1.81-.192 2.279.603l.11.192c.9 1.58 2.379 1.58 3.288 0l.11-.192c.47-.795 1.49-1.077 2.279-.603l1.73.996a1.92 1.92 0 0 1 .699 2.607c-.91 1.58-.17 2.868 1.639 2.868 1.04 0 1.899.856 1.899 1.912v1.772c0 1.047-.85 1.912-1.9 1.912-1.808 0-2.548 1.288-1.638 2.869.52.915.21 2.083-.7 2.606l-1.729.997c-.79.473-1.81.191-2.279-.604l-.11-.191c-.9-1.58-2.379-1.58-3.288 0l-.11.19c-.47.796-1.49 1.078-2.279.605l-1.73-.997a1.919 1.919 0 0 1-.699-2.606c.91-1.58.17-2.869-1.639-2.869A1.911 1.911 0 0 1 2 12.947Z" />
                                <path fill="currentColor" d="M11.995 15.332c1.794 0 3.248-1.464 3.248-3.27 0-1.807-1.454-3.272-3.248-3.272-1.794 0-3.248 1.465-3.248 3.271 0 1.807 1.454 3.271 3.248 3.271Z" />
                            </svg>
                        </a>

                        <!-- Profile -->
                        <div x-data="usePopper({placement:'right-end',offset:12})" @click.outside="isShowPopper && (isShowPopper = false)" class="flex">
                            <button @click="isShowPopper = !isShowPopper" x-ref="popperRef" class="avatar size-12">
                                <div class="flex size-12 items-center justify-center rounded-full bg-primary text-white">
                                    <span class="text-lg font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <span class="absolute right-0 size-3.5 rounded-full border-2 border-white bg-success dark:border-navy-700"></span>
                            </button>

                            <div :class="isShowPopper && 'show'" class="popper-root fixed" x-ref="popperRoot">
                                <div class="popper-box w-64 rounded-lg border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
                                    <div class="flex items-center space-x-4 rounded-t-lg bg-slate-100 py-5 px-4 dark:bg-navy-800">
                                        <div class="avatar size-14">
                                            <div class="flex size-14 items-center justify-center rounded-full bg-primary text-white">
                                                <span class="text-xl font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-base font-medium text-slate-700 dark:text-navy-100">{{ Auth::user()->name }}</div>
                                            <p class="text-xs text-slate-400 dark:text-navy-300">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col pt-2 pb-5">
                                        <a href="#" class="group flex items-center space-x-3 py-2 px-4 tracking-wide outline-none transition-all hover:bg-slate-100 focus:bg-slate-100 dark:hover:bg-navy-600 dark:focus:bg-navy-600">
                                            <div class="flex size-8 items-center justify-center rounded-lg bg-warning text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h2 class="font-medium text-slate-700 transition-colors group-hover:text-primary group-focus:text-primary dark:text-navy-100 dark:group-hover:text-accent-light dark:group-focus:text-accent-light">Profile</h2>
                                                <div class="text-xs text-slate-400 line-clamp-1 dark:text-navy-300">Your profile setting</div>
                                            </div>
                                        </a>
                                        <div class="mt-3 px-4">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="btn h-9 w-full space-x-2 bg-primary text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                    </svg>
                                                    <span>Logout</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Panel -->
        <div class="sidebar-panel">
            <div class="flex h-full grow flex-col bg-white pl-[var(--main-sidebar-width)] dark:bg-navy-750">
                <!-- Sidebar Panel Header -->
                <div class="flex h-18 w-full items-center justify-between pl-4 pr-1">
                    <p class="text-base tracking-wider text-slate-800 dark:text-navy-100">Navigation</p>
                    <button @click="$store.global.isSidebarExpanded = false" class="btn size-7 rounded-full p-0 text-primary hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:text-accent-light/80 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25 xl:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </div>

                <!-- Sidebar Panel Body -->
                <div x-data="{expandedItem:null}" class="h-[calc(100%-4.5rem)] overflow-x-hidden pb-6" x-init="$el._x_simplebar = new SimpleBar($el);">
                    <div class="space-y-1 px-3">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 rounded-lg px-3 py-2.5 tracking-wide {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-700 outline-none transition-all hover:bg-slate-100 focus:bg-slate-100 dark:text-navy-100 dark:hover:bg-navy-600 dark:focus:bg-navy-600' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <div class="pt-2">
                            <div class="px-3 pb-2">
                                <p class="text-xs+ font-medium text-slate-400 dark:text-navy-300">User Management</p>
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-2 rounded-lg px-3 py-2.5 tracking-wide {{ request()->routeIs('admin.users.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-700 outline-none transition-all hover:bg-slate-100 focus:bg-slate-100 dark:text-navy-100 dark:hover:bg-navy-600 dark:focus:bg-navy-600' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>Users</span>
                            </a>
                            <a href="{{ route('admin.roles.index') }}" class="flex items-center space-x-2 rounded-lg px-3 py-2.5 tracking-wide {{ request()->routeIs('admin.roles.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-700 outline-none transition-all hover:bg-slate-100 focus:bg-slate-100 dark:text-navy-100 dark:hover:bg-navy-600 dark:focus:bg-navy-600' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Roles</span>
                            </a>
                            <a href="{{ route('admin.permissions.index') }}" class="flex items-center space-x-2 rounded-lg px-3 py-2.5 tracking-wide {{ request()->routeIs('admin.permissions.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-700 outline-none transition-all hover:bg-slate-100 focus:bg-slate-100 dark:text-navy-100 dark:hover:bg-navy-600 dark:focus:bg-navy-600' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <span>Permissions</span>
                            </a>
                        </div>

                        <div class="pt-2">
                            <div class="px-3 pb-2">
                                <p class="text-xs+ font-medium text-slate-400 dark:text-navy-300">API</p>
                            </div>
                            <a href="{{ route('admin.api-documentation.index') }}" class="flex items-center space-x-2 rounded-lg px-3 py-2.5 tracking-wide {{ request()->routeIs('admin.api-documentation.*') ? 'bg-primary/10 text-primary dark:bg-navy-600 dark:text-accent-light' : 'text-slate-700 outline-none transition-all hover:bg-slate-100 focus:bg-slate-100 dark:text-navy-100 dark:hover:bg-navy-600 dark:focus:bg-navy-600' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span>API Documentation</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- App Header Wrapper-->
        <nav class="header before:bg-white dark:before:bg-navy-750 print:hidden">
            <!-- App Header -->
            <div class="header-container relative flex w-full bg-white dark:bg-navy-750 print:hidden">
                <!-- Header Items -->
                <div class="flex w-full items-center justify-between">
                    <!-- Left: Sidebar Toggle Button -->
                    <div class="size-7">
                        <button class="menu-toggle ml-0.5 flex size-7 flex-col justify-center space-y-1.5 text-primary outline-none focus:outline-none dark:text-accent-light/80" :class="$store.global.isSidebarExpanded && 'active'" @click="$store.global.isSidebarExpanded = !$store.global.isSidebarExpanded">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>

                    <!-- Right: Header buttons -->
                    <div class="-mr-1.5 flex items-center space-x-2">
                        <!-- Theme Toggle -->
                        <button @click="$store.global.toggleDarkMode()" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                            <svg x-show="!$store.global.isDarkMode" xmlns="http://www.w3.org/2000/svg" class="size-5.5 text-slate-500 dark:text-navy-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg x-show="$store.global.isDarkMode" xmlns="http://www.w3.org/2000/svg" class="size-5.5 text-slate-500 dark:text-navy-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content Wrapper -->
        <main class="main-content w-full px-4 pb-8">
            <!-- Page Header -->
            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-3">
                    <h2 class="text-2xl font-medium tracking-wide text-slate-700 dark:text-navy-100">@yield('title', 'Dashboard')</h2>
                    <p class="mt-1 text-sm text-slate-400 dark:text-navy-300">@yield('subtitle', 'Welcome to the admin panel')</p>
                </div>
            </div>

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>

    <!-- jQuery (if available, for DataTables and form validation) -->
    @if(file_exists(public_path('assets/libs/jquery/jquery.min.js')))
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    @elseif(file_exists(public_path('assets/vendor/jquery/jquery.min.js')))
        <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    @endif


    <!-- Javascript Assets -->
    <script src="{{ asset('assets/js/app.js') }}" defer></script>

    <!-- DataTables JS (if available) -->
    @if(file_exists(public_path('assets/libs/datatables/datatables.min.js')))
        <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    @endif

    @stack('scripts')

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
