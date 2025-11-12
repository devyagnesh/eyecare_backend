@extends('layouts.dashboard')

@section('title', 'User Details')

@section('subtitle', 'View user information')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">User Details</div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-wave">
                        <i class="ri-pencil-line me-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-wave">
                        <i class="ri-arrow-left-line me-2"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row gy-4">
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Name</label>
                            <p class="fw-semibold mb-0">{{ $user->name }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Email</label>
                            <p class="fw-semibold mb-0">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Role</label>
                            <p class="mb-0">
                                @if($user->role)
                                    <span class="badge bg-primary-transparent">{{ $user->role->name }}</span>
                                @else
                                    <span class="badge bg-secondary-transparent">No Role</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Email Verified</label>
                            <p class="mb-0">
                                @if($user->email_verified_at)
                                    <span class="badge bg-success-transparent">Verified</span>
                                    <small class="text-muted d-block">{{ $user->email_verified_at->format('M d, Y H:i') }}</small>
                                @else
                                    <span class="badge bg-warning-transparent">Not Verified</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Created At</label>
                            <p class="fw-semibold mb-0">{{ $user->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Updated At</label>
                            <p class="fw-semibold mb-0">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                @if($user->role && $user->role->permissions->count() > 0)
                <div class="row mt-4">
                    <div class="col-xl-12">
                        <div class="card border">
                            <div class="card-header">
                                <div class="card-title">Role Permissions</div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($user->role->permissions as $permission)
                                        <span class="badge bg-info-transparent">{{ $permission->name }}</span>
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

