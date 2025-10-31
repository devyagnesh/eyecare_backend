@extends('layouts.dashboard')

@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/swiper/swiper-bundle.min.css') }}">
@endpush

@section('content')
<!-- Start::row-1 -->
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    Dashboard Overview
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="avatar avatar-md">
                                            <i class="ri-user-line fs-18"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted">Total Users</p>
                                        <h4 class="mb-0 mt-1 fw-semibold">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="avatar avatar-md bg-success">
                                            <i class="ri-checkbox-circle-line fs-18"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted">Active Sessions</p>
                                        <h4 class="mb-0 mt-1 fw-semibold">1</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="avatar avatar-md bg-warning">
                                            <i class="ri-time-line fs-18"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted">Last Login</p>
                                        <h4 class="mb-0 mt-1 fw-semibold fs-12">{{ Auth::user()->created_at->format('M d, Y') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-6">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="avatar avatar-md bg-info">
                                            <i class="ri-mail-line fs-18"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted">Email</p>
                                        <h4 class="mb-0 mt-1 fw-semibold fs-12">{{ Str::limit(Auth::user()->email, 20) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End::row-1 -->

<!-- Start::row-2 -->
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    Welcome Back, {{ Auth::user()->name }}!
                </div>
            </div>
            <div class="card-body">
                <p class="mb-3">
                    You have successfully logged into the admin panel. This is your dashboard where you can manage 
                    various aspects of the application.
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Account Information</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <span class="text-muted">Name:</span>
                                <span class="fw-semibold ms-2">{{ Auth::user()->name }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="text-muted">Email:</span>
                                <span class="fw-semibold ms-2">{{ Auth::user()->email }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="text-muted">Member since:</span>
                                <span class="fw-semibold ms-2">{{ Auth::user()->created_at->format('F Y') }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Quick Actions</h6>
                        <div class="d-flex flex-column gap-2">
                            <a href="javascript:void(0);" class="btn btn-primary btn-sm">
                                <i class="ri-user-line me-1"></i> Manage Users
                            </a>
                            <a href="javascript:void(0);" class="btn btn-outline-primary btn-sm">
                                <i class="ri-settings-3-line me-1"></i> Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End::row-2 -->
@endsection