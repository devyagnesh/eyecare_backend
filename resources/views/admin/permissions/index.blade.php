@extends('layouts.dashboard')

@section('title', 'Permissions Management')

@push('styles')
<!-- DataTables CSS -->
@if(file_exists(public_path('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
@endif

@if(file_exists(public_path('assets/libs/datatables.net-responsive/css/responsive.bootstrap.min.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-responsive/css/responsive.bootstrap.min.css') }}">
@endif
@endpush

@section('page-header')
<div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h1 class="page-title fw-medium fs-18 mb-2">Permissions Management</h1>
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Permissions</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="btn-list">
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary-light btn-wave">
            <i class="ri-add-line align-middle"></i> Add New Permission
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
                    Permissions
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.permissions.index') }}" class="mb-4" data-ajax-filter="true" data-table-id="#permissions-table">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ $filters['search'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Module</label>
                            <select name="module" class="form-select">
                                <option value="">All Modules</option>
                                @foreach($modules as $module)
                                <option value="{{ $module }}" {{ ($filters['module'] ?? '') == $module ? 'selected' : '' }}>{{ $module }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="">All</option>
                                <option value="1" {{ ($filters['is_active'] ?? '') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ ($filters['is_active'] ?? '') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="permissions-table" class="table table-bordered text-nowrap w-100 table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Module</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $index => $permission)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success-transparent me-2">{{ $permission->name }}</span>
                                    </div>
                                </td>
                                <td><code>{{ $permission->slug }}</code></td>
                                <td>
                                    @if($permission->module)
                                        <span class="badge bg-info-transparent">{{ $permission->module }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($permission->is_active)
                                        <span class="badge bg-success-transparent">Active</span>
                                    @else
                                        <span class="badge bg-danger-transparent">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $permission->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.permissions.show', $permission) }}" class="btn btn-sm btn-info-light">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-sm btn-primary-light">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger-light ajax-delete" data-table-id="#permissions-table" data-confirm="Are you sure you want to delete this permission?">
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
    // Initialize permissions DataTable with theme-standard settings
    if (typeof window.initDataTable !== 'undefined') {
        window.initDataTable('#permissions-table', {
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            order: [[5, 'desc']], // Order by Created At column (6th column, index 5)
            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 6] // Disable sorting on # and Actions columns
                }
            ]
        });
        } else if ($.fn.DataTable) {
            // Fallback initialization
            $('#permissions-table').DataTable({
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                },
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                order: [[5, 'desc']], // Order by Created At column
                responsive: true,
                searching: false, // Hide search box
                lengthChange: false, // Hide per page dropdown
                columnDefs: [
                    {
                        orderable: false,
                        targets: [0, 6] // Disable sorting on # and Actions columns
                    }
                ]
            });
        }
});
</script>
@endpush
