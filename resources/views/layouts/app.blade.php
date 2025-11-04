<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials._head')
</head>

<body data-flash-success="{{ session('success') }}" data-flash-error="{{ session('error') }}" data-flash-warning="{{ session('warning') }}" data-flash-info="{{ session('info') }}" @if($errors->any()) data-validation-errors='@json($errors->all())' @endif>
    @auth
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('dashboard') }}">Admin Panel</a>
                <div class="navbar-nav ms-auto">
                    <span class="navbar-text me-3">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    @endauth

    <main class="container-fluid py-4">
        @yield('content')
    </main>

    @include('layouts.partials._scripts')
</body>
</html>
