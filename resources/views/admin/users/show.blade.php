@extends('layouts.dashboard')

@section('title', 'View User')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    User Details: {{ $user->name }}
                </div>
                <div>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning me-2">
                        <i class="ri-pencil-line me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">Full Name</label>
                            <p class="fw-semibold mb-0">{{ $user->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">Email Address</label>
                            <p class="fw-semibold mb-0">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">Role</label>
                            <p class="mb-0">
                                @if($user->role)
                                    <span class="badge bg-primary">{{ $user->role->name }}</span>
                                @else
                                    <span class="badge bg-secondary">No Role Assigned</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">Member Since</label>
                            <p class="fw-semibold mb-0">{{ $user->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>

                @if($user->role && $user->role->permissions->count() > 0)
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">Permissions (via {{ $user->role->name }} role)</h6>
                    <div class="row">
                        @foreach($user->role->permissions->groupBy('module') as $module => $permissions)
                        <div class="col-md-6 mb-3">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <strong>{{ $module ?: 'General' }}</strong>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        @foreach($permissions as $permission)
                                        <li class="mb-2">
                                            <i class="ri-checkbox-circle-line text-success me-2"></i>
                                            {{ $permission->name }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="alert alert-info">
                    This user has no permissions assigned.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
