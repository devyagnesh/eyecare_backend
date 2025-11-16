@extends('layouts.dashboard')

@section('title', 'Store Details')

@section('subtitle', 'View store information')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">Store Details</div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.stores.edit', $store) }}" class="btn btn-primary btn-wave">
                        <i class="ri-pencil-line me-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary btn-wave">
                        <i class="ri-arrow-left-line me-2"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row gy-4">
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Store Name</label>
                            <p class="fw-semibold mb-0">{{ $store->name }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Email</label>
                            <p class="fw-semibold mb-0">{{ $store->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Phone Number</label>
                            <p class="fw-semibold mb-0">{{ $store->phone_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <p class="mb-0">
                                @if($store->is_active)
                                    <span class="badge bg-success-transparent">Active</span>
                                @else
                                    <span class="badge bg-danger-transparent">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="mb-3">
                            <label class="form-label text-muted">Address</label>
                            <p class="fw-semibold mb-0">{{ $store->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Owner</label>
                            <p class="mb-0">
                                @if($store->user)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm avatar-rounded bg-primary text-white me-2">
                                            {{ substr($store->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $store->user->name }}</div>
                                            <small class="text-muted">{{ $store->user->email }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">No owner assigned</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Created At</label>
                            <p class="fw-semibold mb-0">{{ $store->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @if($store->customers_count ?? 0 > 0 || $store->eye_examinations_count ?? 0 > 0 || $store->orders_count ?? 0 > 0)
                    <div class="col-xl-12">
                        <hr>
                        <h6 class="mb-3">Store Statistics</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0">{{ $store->customers_count ?? 0 }}</h3>
                                        <small class="text-muted">Customers</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0">{{ $store->eye_examinations_count ?? 0 }}</h3>
                                        <small class="text-muted">Eye Examinations</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0">{{ $store->orders_count ?? 0 }}</h3>
                                        <small class="text-muted">Orders</small>
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
</div>
@endsection

