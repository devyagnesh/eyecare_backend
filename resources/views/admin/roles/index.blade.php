@extends('layouts.dashboard')

@section('title', 'Roles')

@push('styles')
@if(file_exists(public_path('assets/libs/datatables/datatables.min.css')))
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables/datatables.min.css') }}">
@endif
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <x-card title="All Roles">
            <x-slot name="headerActions">
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-plus me-1"></i>Add New Role
                </a>
            </x-slot>
            @if($roles->count() > 0)
            <div class="table-responsive">
                <table id="rolesTable" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Users</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td>#{{ $role->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-success-transparent me-2">
                                        <i class="bx bx-shield fs-14"></i>
                                    </div>
                                    <span class="fw-semibold">{{ $role->name }}</span>
                                </div>
                            </td>
                            <td><code>{{ $role->slug }}</code></td>
                            <td>
                                <span class="badge bg-primary-transparent">{{ $role->users_count }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $role->is_active ? 'success' : 'danger' }}-transparent">
                                    {{ $role->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $role->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <x-table.actions 
                                    :viewRoute="route('admin.roles.show', $role)"
                                    :editRoute="route('admin.roles.edit', $role)"
                                    :deleteRoute="route('admin.roles.destroy', $role)"
                                    deleteTitle="Delete Role"
                                    :deleteText="'Are you sure you want to delete ' . $role->name . '? This action cannot be undone.'"
                                    deleteSuccessMessage="Role deleted successfully"
                                />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bx bx-shield fs-48 text-muted mb-3 d-block"></i>
                <p class="text-muted">No roles found</p>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i>Add New Role
                </a>
            </div>
            @endif
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
@if(file_exists(public_path('assets/libs/datatables/datatables.min.js')))
<script>
    $(document).ready(function() {
        $('#rolesTable').DataTable({
            order: [[0, 'desc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            responsive: true,
            language: {
                searchPlaceholder: 'Search roles...',
                sSearch: '',
                lengthMenu: 'Show _MENU_ entries',
                info: 'Showing _START_ to _END_ of _TOTAL_ roles',
                infoEmpty: 'No roles found',
                infoFiltered: '(filtered from _MAX_ total roles)',
                paginate: {
                    first: 'First',
                    last: 'Last',
                    next: 'Next',
                    previous: 'Previous'
                }
            }
        });
    });
</script>
@endif
@endpush
