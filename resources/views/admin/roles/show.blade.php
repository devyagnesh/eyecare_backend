@extends('layouts.dashboard')

@section('title', 'View Role')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card :title="'Role Details: ' . $role->name">
            <x-slot name="headerActions">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning btn-sm">
                        <i class="bx bx-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i>Back
                    </a>
                </div>
            </x-slot>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Role Name</label>
                    <p class="fw-semibold mb-0">{{ $role->name }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Slug</label>
                    <p class="mb-0">
                        <code class="badge bg-secondary">{{ $role->slug }}</code>
                    </p>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label text-muted">Description</label>
                    <p class="fw-semibold mb-0">{{ $role->description ?: 'No description' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Status</label>
                    <p class="mb-0">
                        <span class="badge bg-{{ $role->is_active ? 'success' : 'danger' }}-transparent">
                            {{ $role->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Users Count</label>
                    <p class="mb-0">
                        <span class="badge bg-primary-transparent">{{ $role->users->count() }} users</span>
                    </p>
                </div>
            </div>

            @if($role->permissions->count() > 0)
            <div class="border-top pt-4 mt-4">
                <h6 class="mb-3 fw-semibold">Assigned Permissions</h6>
                <div class="row">
                    @foreach($role->permissions->groupBy('module') as $module => $permissions)
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
                This role has no permissions assigned.
            </div>
            @endif
        </x-card>
    </div>
</div>
@endsection
