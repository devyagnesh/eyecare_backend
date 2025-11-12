@extends('layouts.dashboard')

@section('title', 'Permission Details')

@section('subtitle', 'View permission information')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">Permission Details</div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-primary btn-wave">
                        <i class="ri-pencil-line me-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary btn-wave">
                        <i class="ri-arrow-left-line me-2"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row gy-4">
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Name</label>
                            <p class="fw-semibold mb-0">{{ $permission->name }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Slug</label>
                            <p class="fw-semibold mb-0"><code>{{ $permission->slug }}</code></p>
                        </div>
                    </div>
                    @if($permission->module)
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Module</label>
                            <p class="mb-0">
                                <span class="badge bg-info-transparent">{{ $permission->module }}</span>
                            </p>
                        </div>
                    </div>
                    @endif
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <p class="mb-0">
                                @if($permission->is_active)
                                    <span class="badge bg-success-transparent">Active</span>
                                @else
                                    <span class="badge bg-danger-transparent">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($permission->description)
                    <div class="col-xl-12">
                        <div class="mb-3">
                            <label class="form-label text-muted">Description</label>
                            <p class="fw-semibold mb-0">{{ $permission->description }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Created At</label>
                            <p class="fw-semibold mb-0">{{ $permission->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Updated At</label>
                            <p class="fw-semibold mb-0">{{ $permission->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                @if($permission->roles->count() > 0)
                <div class="row mt-4">
                    <div class="col-xl-12">
                        <div class="card border">
                            <div class="card-header">
                                <div class="card-title">Assigned Roles ({{ $permission->roles->count() }})</div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($permission->roles as $role)
                                        <span class="badge bg-primary-transparent">{{ $role->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

