@extends('layouts.dashboard')

@section('title', 'View User')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card :title="'User Details: ' . $user->name">
            <x-slot name="headerActions">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                        <i class="bx bx-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i>Back
                    </a>
                </div>
            </x-slot>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Full Name</label>
                    <p class="fw-semibold mb-0">{{ $user->name }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Email Address</label>
                    <p class="fw-semibold mb-0">{{ $user->email }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Role</label>
                    <p class="mb-0">
                        @if($user->role)
                            <span class="badge bg-primary">{{ $user->role->name }}</span>
                        @else
                            <span class="badge bg-secondary">No Role Assigned</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Member Since</label>
                    <p class="fw-semibold mb-0">{{ $user->created_at->format('F d, Y') }}</p>
                </div>
            </div>

            @if($user->role && $user->role->permissions->count() > 0)
            <div class="border-top pt-4 mt-4">
                <h6 class="mb-3 fw-semibold">Permissions (via {{ $user->role->name }} role)</h6>
                <div class="row">
                    @foreach($user->role->permissions->groupBy('module') as $module => $permissions)
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <strong class="fs-13">{{ $module ?: 'General' }}</strong>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    @foreach($permissions as $permission)
                                    <li class="d-flex align-items-center mb-2">
                                        <i class="bx bx-check-circle text-success me-2"></i>
                                        <span>{{ $permission->name }}</span>
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
            <div class="alert alert-info mt-4">
                <i class="bx bx-info-circle me-2"></i>
                This user has no permissions assigned.
            </div>
            @endif
        </x-card>
    </div>
</div>
@endsection
