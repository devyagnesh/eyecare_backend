@extends('layouts.dashboard')

@section('title', 'Users Management')

@push('styles')
@if(file_exists(public_path('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
@endif
@endpush

@section('page-header')
<div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h1 class="page-title fw-medium fs-18 mb-2">Users Management</h1>
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="btn-list">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary-light btn-wave">
            <i class="ri-add-line align-middle"></i> Add New User
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
                    Users
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4" data-ajax-filter="true" data-table-id="#users-table">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ $filters['search'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Role</label>
                            <select name="role_id" class="form-select">
                                <option value="">All Roles</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ ($filters['role_id'] ?? '') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Email Status</label>
                            <select name="email_verified" class="form-select">
                                <option value="">All</option>
                                <option value="1" {{ ($filters['email_verified'] ?? '') == '1' ? 'selected' : '' }}>Verified</option>
                                <option value="0" {{ ($filters['email_verified'] ?? '') == '0' ? 'selected' : '' }}>Unverified</option>
                            </select>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="users-table" class="table table-bordered text-nowrap w-100 table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm avatar-rounded bg-primary text-white me-2">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role)
                                        <span class="badge bg-primary-transparent">{{ $user->role->name }}</span>
                                    @else
                                        <span class="badge bg-secondary-transparent">No Role</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success-transparent">Verified</span>
                                    @else
                                        <span class="badge bg-warning-transparent">Unverified</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info-light">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary-light">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger-light ajax-delete" data-table-id="#users-table" data-confirm="Are you sure you want to delete this user?">
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
        // Initialize users DataTable with theme-standard settings
        if (typeof window.initDataTable !== 'undefined') {
            window.initDataTable('#users-table', {
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
            $('#users-table').DataTable({
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
