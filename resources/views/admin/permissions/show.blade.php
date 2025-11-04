@extends('layouts.dashboard')

@section('title', 'View Permission')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card :title="'Permission Details: ' . $permission->name">
            <x-slot name="headerActions">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-warning btn-sm">
                        <i class="bx bx-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i>Back
                    </a>
                </div>
            </x-slot>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Permission Name</label>
                    <p class="fw-semibold mb-0">{{ $permission->name }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Slug</label>
                    <p class="mb-0">
                        <code class="badge bg-secondary">{{ $permission->slug }}</code>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Module</label>
                    <p class="mb-0">
                        <span class="badge bg-info-transparent">{{ $permission->module ?: 'General' }}</span>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">Status</label>
                    <p class="mb-0">
                        <span class="badge bg-{{ $permission->is_active ? 'success' : 'danger' }}-transparent">
                            {{ $permission->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label text-muted">Description</label>
                    <p class="fw-semibold mb-0">{{ $permission->description ?: 'No description' }}</p>
                </div>
            </div>

            @if($permission->roles->count() > 0)
            <div class="border-top pt-4 mt-4">
                <h6 class="mb-3 fw-semibold">Assigned Roles</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($permission->roles as $role)
                    <a href="{{ route('admin.roles.show', $role) }}" class="badge bg-success-transparent">
                        {{ $role->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @else
            <div class="alert alert-info mt-4">
                <i class="bx bx-info-circle me-2"></i>
                This permission is not assigned to any roles.
            </div>
            @endif
        </x-card>
    </div>
</div>
@endsection
