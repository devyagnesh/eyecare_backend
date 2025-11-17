@extends('layouts.dashboard')

@section('title', 'Push Notifications')

@push('styles')
@if(file_exists(public_path('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
@endif
@endpush

@section('page-header')
<div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h1 class="page-title fw-medium fs-18 mb-2">Push Notifications</h1>
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Notifications</li>
                </ol>
            </nav>
        </div>
    </div>
    <div>
        <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
            <i class="ri-notification-line"></i> Send Notification
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    Notification History
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.notifications.index') }}" class="mb-4" data-ajax-filter="true" data-table-id="#notifications-table">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by title or body" value="{{ $filters['search'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="">All</option>
                                <option value="all" {{ ($filters['type'] ?? '') === 'all' ? 'selected' : '' }}>All Users</option>
                                <option value="user" {{ ($filters['type'] ?? '') === 'user' ? 'selected' : '' }}>Specific User</option>
                                <option value="store" {{ ($filters['type'] ?? '') === 'store' ? 'selected' : '' }}>Store Users</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="sending" {{ ($filters['status'] ?? '') === 'sending' ? 'selected' : '' }}>Sending</option>
                                <option value="completed" {{ ($filters['status'] ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ ($filters['status'] ?? '') === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-filter-line"></i> Filter
                            </button>
                            <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">
                                <i class="ri-refresh-line"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="notifications-table" class="table table-bordered text-nowrap w-100 table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Target</th>
                                <th>Sent</th>
                                <th>Failed</th>
                                <th>Status</th>
                                <th>Sent At</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notifications as $notification)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-medium">{{ Str::limit($notification->title, 40) }}</div>
                                    <small class="text-muted">{{ Str::limit($notification->body, 50) }}</small>
                                </td>
                                <td>
                                    @if($notification->type === 'all')
                                        <span class="badge bg-info-transparent">All Users</span>
                                    @elseif($notification->type === 'user')
                                        <span class="badge bg-primary-transparent">User</span>
                                    @elseif($notification->type === 'store')
                                        <span class="badge bg-success-transparent">Store</span>
                                    @endif
                                </td>
                                <td>
                                    @if($notification->type === 'user' && $notification->user)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm avatar-rounded bg-primary text-white me-2">
                                                {{ substr($notification->user->name, 0, 1) }}
                                            </div>
                                            {{ $notification->user->name }}
                                        </div>
                                    @elseif($notification->type === 'store' && $notification->store)
                                        {{ $notification->store->name }}
                                    @else
                                        <span class="text-muted">All Users</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success-transparent">{{ $notification->sent_count }}</span>
                                </td>
                                <td>
                                    @if($notification->failed_count > 0)
                                        <span class="badge bg-danger-transparent">{{ $notification->failed_count }}</span>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td>
                                    @if($notification->status === 'completed')
                                        <span class="badge bg-success-transparent">Completed</span>
                                    @elseif($notification->status === 'sending')
                                        <span class="badge bg-warning-transparent">Sending</span>
                                    @elseif($notification->status === 'failed')
                                        <span class="badge bg-danger-transparent">Failed</span>
                                    @else
                                        <span class="badge bg-secondary-transparent">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($notification->sent_at)
                                        {{ $notification->sent_at->format('M d, Y H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($notification->creator)
                                        {{ $notification->creator->name }}
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-list">
                                        <a href="{{ route('admin.notifications.show', $notification->id) }}" class="btn btn-sm btn-info">
                                            <i class="ri-eye-line"></i> View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">No notifications found.</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(file_exists(public_path('assets/libs/datatables.net/js/jquery.dataTables.min.js')))
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
@endif
@if(file_exists(public_path('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js')))
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
@endif
<script>
    $(document).ready(function() {
        // Initialize DataTable if available
        if ($.fn.DataTable) {
            $('#notifications-table').DataTable({
                pageLength: 25,
                order: [[0, 'desc']],
                dom: 'Bfrtip',
                buttons: [],
            });
        }
    });
</script>
@endpush

