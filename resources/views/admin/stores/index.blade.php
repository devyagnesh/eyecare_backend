@extends('layouts.dashboard')

@section('title', 'Stores Management')

@push('styles')
@if(file_exists(public_path('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
@endif
@endpush

@section('page-header')
<div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h1 class="page-title fw-medium fs-18 mb-2">Stores Management</h1>
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Stores</li>
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
                    Stores
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.stores.index') }}" class="mb-4" data-ajax-filter="true" data-table-id="#stores-table">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone" value="{{ $filters['search'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="">All</option>
                                <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-filter-line"></i> Filter
                            </button>
                            <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary">
                                <i class="ri-refresh-line"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="stores-table" class="table table-bordered text-nowrap w-100 table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Store Name</th>
                                <th>Owner</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stores as $store)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-medium">{{ $store->name }}</div>
                                </td>
                                <td>
                                    @if($store->user)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm avatar-rounded bg-primary text-white me-2">
                                                {{ substr($store->user->name, 0, 1) }}
                                            </div>
                                            {{ $store->user->name }}
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $store->email ?? 'N/A' }}</td>
                                <td>{{ $store->phone_number ?? 'N/A' }}</td>
                                <td>
                                    @if($store->is_active)
                                        <span class="badge bg-success-transparent">Active</span>
                                    @else
                                        <span class="badge bg-danger-transparent">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $store->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.stores.show', $store) }}" class="btn btn-sm btn-info-light" title="View">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('admin.stores.edit', $store) }}" class="btn btn-sm btn-primary-light" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('admin.stores.toggle-status', $store) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-sm {{ $store->is_active ? 'btn-warning-light' : 'btn-success-light' }} ajax-action" data-table-id="#stores-table" title="{{ $store->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="ri-{{ $store->is_active ? 'close-circle' : 'check-circle' }}-line"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.stores.destroy', $store) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger-light ajax-delete" data-table-id="#stores-table" data-confirm="Are you sure you want to delete this store?" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
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
    // Initialize stores DataTable
    if (typeof window.initDataTable !== 'undefined') {
        window.initDataTable('#stores-table', {
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            order: [[6, 'desc']], // Order by Created At column
            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 7] // Disable sorting on # and Actions columns
                }
            ]
        });
    } else if ($.fn.DataTable) {
        // Fallback initialization
        $('#stores-table').DataTable({
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            order: [[6, 'desc']],
            responsive: true,
            searching: false,
            lengthChange: false,
            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 7]
                }
            ]
        });
    }
});
</script>
@endpush

