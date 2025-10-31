@extends('layouts.dashboard')

@section('title', 'View Permission')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    Permission Details: {{ $permission->name }}
                </div>
                <div>
                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-sm btn-warning me-2">
                        <i class="ri-pencil-line me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-sm btn-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">Permission Name</label>
                            <p class="fw-semibold mb-0">{{ $permission->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">Slug</label>
                            <p class="mb-0"><span class="badge bg-secondary">{{ $permission->slug }}</span></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">Module</label>
                            <p class="mb-0"><span class="badge bg-primary">{{ $permission->module ?: 'General' }}</span></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">Status</label>
                            <p class="mb-0">
                                @if($permission->is_active)
                                    <span class="badge bg-success-transparent">Active</span>
                                @else
                                    <span class="badge bg-danger-transparent">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="text-muted">Description</label>
                            <p class="fw-semibold mb-0">{{ $permission->description ?: 'No description' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">Assigned to Roles ({{ $permission->roles->count() }})</h6>
                    @if($permission->roles->count() > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($permission->roles as $role)
                        <a href="{{ route('admin.roles.show', $role) }}" class="badge bg-info">
                            {{ $role->name }}
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted">This permission is not assigned to any roles.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
