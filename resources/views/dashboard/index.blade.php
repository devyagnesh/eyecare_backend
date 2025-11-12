@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('subtitle', 'Welcome back, ' . Auth::user()->name . '!')

@section('content')
<!-- Start:: row-1 -->
<div class="row">
    <div class="col-xl-3">
        <div class="card custom-card main-card-item primary">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3 flex-wrap">
                    <div>
                        <span class="d-block mb-3 fw-medium">Total Users</span>
                        <h3 class="fw-semibold lh-1 mb-0">{{ $stats['total_users'] }}</h3>
                    </div>
                    <div class="text-end">
                        <div class="mb-4">
                            <span class="avatar avatar-md bg-primary svg-white avatar-rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><circle cx="128" cy="96" r="64" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M31,216a112,112,0,0,1,194,0" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.users.index') }}" class="text-muted text-decoration-underline fw-medium fs-13">View all users</a>
                    @if(isset($stats['user_growth']) && $stats['user_growth'] > 0)
                        <span class="text-success fw-semibold"><i class="ti ti-arrow-narrow-up"></i>{{ abs($stats['user_growth']) }}%</span>
                    @elseif(isset($stats['user_growth']) && $stats['user_growth'] < 0)
                        <span class="text-danger fw-semibold"><i class="ti ti-arrow-narrow-down"></i>{{ abs($stats['user_growth']) }}%</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card custom-card main-card-item">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3 flex-wrap">
                    <div>
                        <span class="d-block mb-3 fw-medium">Total Roles</span>
                        <h3 class="fw-semibold lh-1 mb-0">{{ $stats['total_roles'] }}</h3>
                    </div>
                    <div class="text-end">
                        <div class="mb-4">
                            <span class="avatar avatar-md bg-secondary svg-white avatar-rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><circle cx="128" cy="128" r="32" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.roles.index') }}" class="text-muted text-decoration-underline fw-medium fs-13">View all roles</a>
                    <span class="badge bg-info-transparent">{{ $stats['active_roles'] }} active</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card custom-card main-card-item">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3 flex-wrap">
                    <div>
                        <span class="d-block mb-3 fw-medium">Total Permissions</span>
                        <h3 class="fw-semibold lh-1 mb-0">{{ $stats['total_permissions'] }}</h3>
                    </div>
                    <div class="text-end">
                        <div class="mb-4">
                            <span class="avatar avatar-md bg-success svg-white avatar-rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><polyline points="172 104 113.3 160 84 132" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><circle cx="128" cy="128" r="96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.permissions.index') }}" class="text-muted text-decoration-underline fw-medium fs-13">View all permissions</a>
                    <span class="badge bg-success-transparent">{{ $stats['active_permissions'] }} active</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card custom-card main-card-item">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3 flex-wrap">
                    <div>
                        <span class="d-block mb-3 fw-medium">New Users This Month</span>
                        <h3 class="fw-semibold lh-1 mb-0">{{ $stats['users_this_month'] }}</h3>
                    </div>
                    <div class="text-end">
                        <div class="mb-4">
                            <span class="avatar avatar-md bg-info svg-white avatar-rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><line x1="128" y1="32" x2="128" y2="64" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="195.9" y1="60.1" x2="173.3" y2="82.7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="224" y1="128" x2="192" y2="128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="195.9" y1="195.9" x2="173.3" y2="173.3" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="128" y1="224" x2="128" y2="192" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="60.1" y1="195.9" x2="82.7" y2="173.3" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="32" y1="128" x2="64" y2="128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="60.1" y1="60.1" x2="82.7" y2="82.7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-muted fs-13">Last month: {{ $stats['users_last_month'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End:: row-1 -->

<!-- Start:: row-2 -->
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Recent Users</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['recent_users'] as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm avatar-rounded bg-primary text-white me-2">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        {{ $user->name }}
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
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info-light">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No users found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End:: row-2 -->
@endsection

