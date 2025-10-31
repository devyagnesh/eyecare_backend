@extends('layouts.dashboard')

@section('title', 'View Role')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    Role Details: {{ $role->name }}
                </div>
                <div>
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning me-2">
                        <i class="ri-pencil-line me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-sm btn-secondary">
                        <i class="ri-arrow-left-line me-1"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">Role Name</label>
                            <p class="fw-semibold mb-0">{{ $role->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">Slug</label>
                            <p class="mb-0"><span class="badge bg-secondary">{{ $role->slug }}</span></p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="text-muted">Description</label>
                            <p class="fw-semibold mb-0">{{ $role->description ?: 'No description' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">Status</label>
                            <p class="mb-0">
                                @if($role->is_active)
                                    <span class="badge bg-success-transparent">Active</span>
                                @else
                                    <span class="badge bg-danger-transparent">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted">Users Count</label>
                            <p class="mb-0"><span class="badge bg-info">{{ $role->users->count() }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">Assigned Permissions ({{ $role->permissions->count() }})</h6>
                    <div class="row">
                        @foreach($role->permissions->groupBy('module') as $module => $permissions)
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
                    @if($role->permissions->count() === 0)
                    <p class="text-muted">No permissions assigned to this role.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
