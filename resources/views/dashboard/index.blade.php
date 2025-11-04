@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('subtitle', 'Welcome back, ' . Auth::user()->name . '!')

@section('content')
<!-- Start::row -->
<div class="row">
    <!-- Stat Cards -->
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <span class="text-muted fs-12 fw-semibold d-block mb-1">Total Users</span>
                        <h3 class="mb-0 fw-semibold">{{ number_format($stats['total_users']) }}</h3>
                        @if($stats['user_growth'] > 0)
                        <span class="text-success fs-11 fw-semibold">
                            <i class="ri-arrow-up-line align-middle"></i> {{ abs($stats['user_growth']) }}% this month
                        </span>
                        @endif
                    </div>
                    <div class="avatar avatar-md bg-primary-transparent">
                        <i class="bx bx-user fs-24"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <span class="text-muted fs-12 fw-semibold d-block mb-1">Total Roles</span>
                        <h3 class="mb-0 fw-semibold">{{ number_format($stats['total_roles']) }}</h3>
                        <span class="text-muted fs-11 fw-semibold">
                            <i class="ri-checkbox-circle-line align-middle"></i> {{ $stats['active_roles'] }} active
                        </span>
                    </div>
                    <div class="avatar avatar-md bg-success-transparent">
                        <i class="bx bx-shield fs-24"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <span class="text-muted fs-12 fw-semibold d-block mb-1">Total Permissions</span>
                        <h3 class="mb-0 fw-semibold">{{ number_format($stats['total_permissions']) }}</h3>
                        <span class="text-muted fs-11 fw-semibold">
                            <i class="ri-checkbox-circle-line align-middle"></i> {{ $stats['active_permissions'] }} active
                        </span>
                    </div>
                    <div class="avatar avatar-md bg-warning-transparent">
                        <i class="bx bx-check-circle fs-24"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <span class="text-muted fs-12 fw-semibold d-block mb-1">New Users</span>
                        <h3 class="mb-0 fw-semibold">{{ number_format($stats['users_this_month']) }}</h3>
                        <span class="text-muted fs-11 fw-semibold">
                            <i class="ri-calendar-line align-middle"></i> This month
                        </span>
                    </div>
                    <div class="avatar avatar-md bg-info-transparent">
                        <i class="bx bx-user-plus fs-24"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End::row -->

<!-- Start::row -->
<div class="row">
    <!-- Quick Actions & Modules -->
    <div class="col-xl-8 col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Quick Actions & Modules</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Users Module -->
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="card border border-primary mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-md bg-primary-transparent me-3">
                                        <i class="bx bx-user fs-20"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-semibold">User Management</h6>
                                        <span class="text-muted fs-12">Manage system users</span>
                                    </div>
                                </div>
                                <p class="text-muted fs-13 mb-3">Create, edit, and manage user accounts, roles, and permissions.</p>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">
                                        <i class="bx bx-list-ul me-1"></i>View All
                                    </a>
                                    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bx bx-plus me-1"></i>Add New
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Roles Module -->
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="card border border-success mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-md bg-success-transparent me-3">
                                        <i class="bx bx-shield fs-20"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-semibold">Role Management</h6>
                                        <span class="text-muted fs-12">Manage user roles</span>
                                    </div>
                                </div>
                                <p class="text-muted fs-13 mb-3">Define and assign roles to users with specific permissions.</p>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.roles.index') }}" class="btn btn-success btn-sm">
                                        <i class="bx bx-list-ul me-1"></i>View All
                                    </a>
                                    <a href="{{ route('admin.roles.create') }}" class="btn btn-outline-success btn-sm">
                                        <i class="bx bx-plus me-1"></i>Add New
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Module -->
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="card border border-warning mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-md bg-warning-transparent me-3">
                                        <i class="bx bx-check-circle fs-20"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-semibold">Permission Management</h6>
                                        <span class="text-muted fs-12">Control access permissions</span>
                                    </div>
                                </div>
                                <p class="text-muted fs-13 mb-3">Manage fine-grained permissions for system modules and features.</p>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-warning btn-sm">
                                        <i class="bx bx-list-ul me-1"></i>View All
                                    </a>
                                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-outline-warning btn-sm">
                                        <i class="bx bx-plus me-1"></i>Add New
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- API Documentation Module -->
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="card border border-info mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-md bg-info-transparent me-3">
                                        <i class="bx bx-book fs-20"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-semibold">API Documentation</h6>
                                        <span class="text-muted fs-12">API reference guide</span>
                                    </div>
                                </div>
                                <p class="text-muted fs-13 mb-3">Access comprehensive API documentation and endpoints reference.</p>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.api-documentation.index') }}" class="btn btn-info btn-sm">
                                        <i class="bx bx-book-open me-1"></i>View Docs
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Information -->
    <div class="col-xl-4 col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Account Information</div>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar avatar-xl bg-primary-transparent mx-auto mb-3">
                        <i class="bx bx-user fs-32"></i>
                    </div>
                    <h5 class="mb-1 fw-semibold">{{ Auth::user()->name }}</h5>
                    <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                </div>

                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Role</span>
                        <span class="badge bg-primary-transparent">
                            {{ Auth::user()->role ? Auth::user()->role->name : 'No Role' }}
                        </span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Member Since</span>
                        <span class="fw-semibold">{{ Auth::user()->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Status</span>
                        <span class="badge bg-{{ Auth::user()->email_verified_at ? 'success' : 'warning' }}-transparent">
                            {{ Auth::user()->email_verified_at ? 'Verified' : 'Pending' }}
                        </span>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.users.edit', Auth::user()) }}" class="btn btn-primary w-100">
                        <i class="bx bx-edit me-1"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End::row -->

<!-- Start::row -->
<div class="row">
    <!-- Recent Users -->
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">Recent Users</div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">
                    <i class="bx bx-list-ul me-1"></i>View All
                </a>
            </div>
            <div class="card-body">
                @if($stats['recent_users']->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recent_users'] as $user)
                            <tr>
                                <td>#{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-primary-transparent me-2">
                                            <i class="bx bx-user fs-14"></i>
                                        </div>
                                        <span class="fw-semibold">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role)
                                        <span class="badge bg-primary-transparent">{{ $user->role->name }}</span>
                                    @else
                                        <span class="badge bg-secondary-transparent">No Role</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->email_verified_at ? 'success' : 'warning' }}-transparent">
                                        {{ $user->email_verified_at ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-icon btn-light">
                                        <i class="bx bx-show"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bx bx-user fs-48 text-muted mb-3 d-block"></i>
                    <p class="text-muted">No users found</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End::row -->
@endsection
