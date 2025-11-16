@extends('layouts.dashboard')

@section('title', 'Edit Store')

@section('subtitle', 'Update store information')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Edit Store</div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.stores.update', $store) }}" method="POST" data-ajax="true" data-table-id="#stores-table">
                    @csrf
                    @method('PUT')
                    <div class="row gy-4">
                        <div class="col-xl-6">
                            <label for="name" class="form-label">Store Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $store->name) }}" placeholder="Enter store name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-xl-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $store->email) }}" placeholder="Enter email address" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-xl-6">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $store->phone_number) }}" placeholder="Enter phone number">
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-xl-6">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" placeholder="Enter store address">{{ old('address', $store->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-xl-6">
                            <label class="form-label">Owner</label>
                            <div class="form-control" readonly>
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
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $store->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Store Active
                                </label>
                            </div>
                            <small class="text-muted">Enable this to make the store active and accessible.</small>
                            @error('is_active')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Update Store</button>
                        <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

