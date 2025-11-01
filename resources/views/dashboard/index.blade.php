@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Users Card -->
        <div class="card">
            <div class="flex items-center justify-between p-4 sm:p-5">
                <div class="flex items-center space-x-4">
                    <div class="flex size-12 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs+ text-slate-400 dark:text-navy-300">Total Users</p>
                        <p class="text-xl font-semibold text-slate-700 dark:text-navy-100">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Sessions Card -->
        <div class="card">
            <div class="flex items-center justify-between p-4 sm:p-5">
                <div class="flex items-center space-x-4">
                    <div class="flex size-12 items-center justify-center rounded-lg bg-success/10 text-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs+ text-slate-400 dark:text-navy-300">Active Sessions</p>
                        <p class="text-xl font-semibold text-slate-700 dark:text-navy-100">1</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Last Login Card -->
        <div class="card">
            <div class="flex items-center justify-between p-4 sm:p-5">
                <div class="flex items-center space-x-4">
                    <div class="flex size-12 items-center justify-center rounded-lg bg-warning/10 text-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs+ text-slate-400 dark:text-navy-300">Last Login</p>
                        <p class="text-sm font-semibold text-slate-700 dark:text-navy-100">{{ Auth::user()->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Card -->
        <div class="card">
            <div class="flex items-center justify-between p-4 sm:p-5">
                <div class="flex items-center space-x-4">
                    <div class="flex size-12 items-center justify-center rounded-lg bg-info/10 text-info">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs+ text-slate-400 dark:text-navy-300">Email</p>
                        <p class="text-sm font-semibold text-slate-700 dark:text-navy-100">{{ Str::limit(Auth::user()->email, 20) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="card">
        <div class="flex flex-col items-start justify-between border-b border-slate-200 p-4 dark:border-navy-500 sm:flex-row sm:items-center sm:p-5">
            <h2 class="text-lg font-medium tracking-wide text-slate-700 dark:text-navy-100">Welcome Back, {{ Auth::user()->name }}!</h2>
        </div>
        <div class="p-4 sm:p-5">
            <p class="mb-4 text-slate-600 dark:text-navy-300">
                You have successfully logged into the admin panel. This is your dashboard where you can manage various aspects of the application.
            </p>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Account Information -->
                <div>
                    <h6 class="mb-3 font-semibold text-slate-700 dark:text-navy-100">Account Information</h6>
                    <ul class="space-y-2">
                        <li class="flex items-center justify-between">
                            <span class="text-sm text-slate-400 dark:text-navy-300">Name:</span>
                            <span class="text-sm font-semibold text-slate-700 dark:text-navy-100">{{ Auth::user()->name }}</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-sm text-slate-400 dark:text-navy-300">Email:</span>
                            <span class="text-sm font-semibold text-slate-700 dark:text-navy-100">{{ Auth::user()->email }}</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-sm text-slate-400 dark:text-navy-300">Member since:</span>
                            <span class="text-sm font-semibold text-slate-700 dark:text-navy-100">{{ Auth::user()->created_at->format('F Y') }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Quick Actions -->
                <div>
                    <h6 class="mb-3 font-semibold text-slate-700 dark:text-navy-100">Quick Actions</h6>
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('admin.users.index') }}" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Manage Users
                        </a>
                        <a href="{{ route('admin.roles.index') }}" class="btn border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Manage Roles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
