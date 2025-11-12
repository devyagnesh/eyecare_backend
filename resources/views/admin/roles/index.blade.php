@extends('layouts.dashboard')

@section('title', 'Roles Management')

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
        <h1 class="page-title fw-medium fs-18 mb-2">Roles Management</h1>
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Roles</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="btn-list">
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary-light btn-wave">
            <i class="ri-add-line align-middle"></i> Add New Role
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
                    Roles
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.roles.index') }}" class="mb-4" data-ajax-filter="true" data-table-id="#roles-table">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by name, slug or description" value="{{ $filters['search'] ?? '' }}">
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
                    <table id="roles-table" class="table table-bordered text-nowrap w-100 table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Users Count</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $index => $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary-transparent me-2">{{ $role->name }}</span>
                                    </div>
                                </td>
                                <td><code>{{ $role->slug }}</code></td>
                                <td>
                                    <span class="badge bg-info-transparent">{{ $role->users_count }} users</span>
                                </td>
                                <td>
                                    @if($role->is_active)
                                        <span class="badge bg-success-transparent">Active</span>
                                    @else
                                        <span class="badge bg-danger-transparent">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $role->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-info-light">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-primary-light">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger-light ajax-delete" data-table-id="#roles-table" data-confirm="Are you sure? This will remove all permissions from this role.">
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
    // Initialize roles DataTable with theme-standard settings
    if (typeof window.initDataTable !== 'undefined') {
        window.initDataTable('#roles-table', {
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
            $('#roles-table').DataTable({
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
