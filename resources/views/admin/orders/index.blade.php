@extends('layouts.dashboard')

@section('title', 'Orders Management')

@push('styles')
@if(file_exists(public_path('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
@endif
@endpush

@section('page-header')
<div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h1 class="page-title fw-medium fs-18 mb-2">Orders Management</h1>
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Orders</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    Orders
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4" data-ajax-filter="true" data-table-id="#orders-table">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by invoice number, customer name, email, or phone" value="{{ $filters['search'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Store</label>
                            <select name="store_id" class="form-select">
                                <option value="">All Stores</option>
                                @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ ($filters['store_id'] ?? '') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ ($filters['status'] ?? '') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-wave w-100">
                                <i class="ri-search-line"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="orders-table" class="table table-bordered text-nowrap w-100 table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice Number</th>
                                <th>Customer</th>
                                <th>Store</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Expected Completion</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $order->invoice_number }}</span>
                                </td>
                                <td>
                                    @if($order->customer)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm avatar-rounded bg-primary text-white me-2">
                                                {{ substr($order->customer->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $order->customer->name }}</div>
                                                <small class="text-muted">{{ $order->customer->email }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->store)
                                        <span class="badge bg-info-transparent">{{ $order->store->name }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-semibold">${{ number_format($order->total_price, 2) }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $color = $statusColors[$order->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}-transparent">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td>{{ $order->expected_completion_date ? $order->expected_completion_date->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info-light" title="View">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No orders found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables JS -->
@if(file_exists(public_path('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js')))
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
@endif

@if(file_exists(public_path('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')))
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
@endif

@if(file_exists(public_path('assets/libs/datatables.net-responsive/js/responsive.bootstrap.min.js')))
<script src="{{ asset('assets/libs/datatables.net-responsive/js/responsive.bootstrap.min.js') }}"></script>
@endif

<!-- DataTables Init -->
@if(file_exists(public_path('assets/js/datatables-init.js')))
<script src="{{ asset('assets/js/datatables-init.js') }}?v={{ filemtime(public_path('assets/js/datatables-init.js')) }}"></script>
@endif

<script>
$(document).ready(function() {
    // Initialize orders DataTable with theme-standard settings
    if (typeof window.initDataTable !== 'undefined') {
        window.initDataTable('#orders-table', {
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            order: [[7, 'desc']], // Order by Created At column (8th column, index 7)
            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 8] // Disable sorting on # and Actions columns
                }
            ]
        });
    } else if ($.fn.DataTable) {
        // Fallback initialization
        $('#orders-table').DataTable({
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
            },
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            order: [[7, 'desc']], // Order by Created At column
            responsive: true,
            searching: true, // Enable search box
            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 8] // Disable sorting on # and Actions columns
                }
            ]
        });
    }
});
</script>
@endpush

