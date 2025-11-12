@extends('layouts.dashboard')

@section('title', 'Role Details')

@section('subtitle', 'View role information')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">Role Details</div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary btn-wave">
                        <i class="ri-pencil-line me-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-wave">
                        <i class="ri-arrow-left-line me-2"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row gy-4">
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Name</label>
                            <p class="fw-semibold mb-0">{{ $role->name }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Slug</label>
                            <p class="fw-semibold mb-0"><code>{{ $role->slug }}</code></p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <p class="mb-0">
                                @if($role->is_active)
                                    <span class="badge bg-success-transparent">Active</span>
                                @else
                                    <span class="badge bg-danger-transparent">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Users Count</label>
                            <p class="fw-semibold mb-0">{{ $role->users->count() }} users</p>
                        </div>
                    </div>
                    @if($role->description)
                    <div class="col-xl-12">
                        <div class="mb-3">
                            <label class="form-label text-muted">Description</label>
                            <p class="fw-semibold mb-0">{{ $role->description }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Created At</label>
                            <p class="fw-semibold mb-0">{{ $role->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Updated At</label>
                            <p class="fw-semibold mb-0">{{ $role->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                @if($role->permissions->count() > 0)
                <div class="row mt-4">
                    <div class="col-xl-12">
                        <div class="card border">
                            <div class="card-header">
                                <div class="card-title">Assigned Permissions ({{ $role->permissions->count() }})</div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($role->permissions as $permission)
                                        <span class="badge bg-primary-transparent">{{ $permission->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if($role->users->count() > 0)
                <div class="row mt-4">
                    <div class="col-xl-12">
                        <div class="card border">
                            <div class="card-header">
                                <div class="card-title">Assigned Users ({{ $role->users->count() }})</div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($role->users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info-light">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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

