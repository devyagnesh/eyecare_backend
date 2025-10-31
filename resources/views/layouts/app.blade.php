<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>

    <!-- Local Theme Assets -->
    @if(file_exists(public_path('assets/css/main.css')))
        <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    @endif
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    @auth
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-900">Admin Panel</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    @endauth

    <main>
        @yield('content')
    </main>

    <!-- jQuery (Local) -->
    @if(file_exists(public_path('assets/vendor/jquery/jquery.min.js')))
        <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    @endif

    <!-- jQuery Validation (Local) -->
    @if(file_exists(public_path('assets/vendor/jquery-validation/jquery.validate.min.js')))
        <script src="{{ asset('assets/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    @endif

    <!-- Local Theme JS -->
    @if(file_exists(public_path('assets/js/main.js')))
        <script src="{{ asset('assets/js/main.js') }}"></script>
    @endif

    @stack('scripts')
</body>
</html>
