@extends('layouts.dashboard')

@section('title', 'Roles')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/datatables/datatables.min.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    Roles Management
                </div>
                <div>
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                        <i class="ri-add-line me-1"></i> Add New Role
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="table-responsive">
                    <table id="rolesTable" class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Users Count</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td><span class="badge bg-secondary">{{ $role->slug }}</span></td>
                                <td><span class="badge bg-info">{{ $role->users_count }}</span></td>
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
                                        <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-info">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
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
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#rolesTable').DataTable({
            order: [[0, 'desc']],
            pageLength: 15,
            responsive: true
        });
    });
</script>
@endpush
