@extends('layouts.dashboard')

@section('title', 'Notification Details')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Notification Details</div>
            </div>
            <div class="card-body">
                <div class="row gy-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Title</label>
                        <div class="mb-3">{{ $notification->title }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Type</label>
                        <div class="mb-3">
                            @if($notification->type === 'all')
                                <span class="badge bg-info-transparent">All Users</span>
                            @elseif($notification->type === 'user')
                                <span class="badge bg-primary-transparent">User</span>
                            @elseif($notification->type === 'store')
                                <span class="badge bg-success-transparent">Store</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Target</label>
                        <div class="mb-3">
                            @if($notification->type === 'user' && $notification->user)
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm avatar-rounded bg-primary text-white me-2">
                                        {{ substr($notification->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div>{{ $notification->user->name }}</div>
                                        <small class="text-muted">{{ $notification->user->email }}</small>
                                    </div>
                                </div>
                            @elseif($notification->type === 'store' && $notification->store)
                                {{ $notification->store->name }}
                            @else
                                <span class="text-muted">All Users</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <div class="mb-3">
                            @if($notification->status === 'completed')
                                <span class="badge bg-success-transparent">Completed</span>
                            @elseif($notification->status === 'sending')
                                <span class="badge bg-warning-transparent">Sending</span>
                            @elseif($notification->status === 'failed')
                                <span class="badge bg-danger-transparent">Failed</span>
                            @else
                                <span class="badge bg-secondary-transparent">Pending</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Message Body</label>
                        <div class="mb-3 p-3 bg-light rounded">{{ $notification->body }}</div>
                    </div>
                    @if($notification->data)
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Additional Data</label>
                        <div class="mb-3">
                            <pre class="bg-light p-3 rounded">{{ json_encode($notification->data, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Sent Count</label>
                        <div class="mb-3">
                            <span class="badge bg-success-transparent">{{ $notification->sent_count }} devices</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Failed Count</label>
                        <div class="mb-3">
                            @if($notification->failed_count > 0)
                                <span class="badge bg-danger-transparent">{{ $notification->failed_count }} devices</span>
                            @else
                                <span class="text-muted">0 devices</span>
                            @endif
                        </div>
                    </div>
                    @if($notification->error_message)
                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-danger">Error Message</label>
                        <div class="mb-3 p-3 bg-danger-transparent rounded text-danger">
                            {{ $notification->error_message }}
                        </div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Created By</label>
                        <div class="mb-3">
                            @if($notification->creator)
                                {{ $notification->creator->name }}
                            @else
                                <span class="text-muted">System</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Sent At</label>
                        <div class="mb-3">
                            @if($notification->sent_at)
                                {{ $notification->sent_at->format('M d, Y H:i:s') }}
                            @else
                                <span class="text-muted">Not sent yet</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Created At</label>
                        <div class="mb-3">{{ $notification->created_at->format('M d, Y H:i:s') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Updated At</label>
                        <div class="mb-3">{{ $notification->updated_at->format('M d, Y H:i:s') }}</div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">
                        <i class="ri-arrow-left-line"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

