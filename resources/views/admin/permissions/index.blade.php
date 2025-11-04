@extends('layouts.dashboard')

@section('title', 'Permissions')

@push('styles')
@if(file_exists(public_path('assets/libs/datatables/datatables.min.css')))
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables/datatables.min.css') }}">
@endif
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <x-card title="All Permissions">
            <x-slot name="headerActions">
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-plus me-1"></i>Add New Permission
                </a>
            </x-slot>
            @if($permissions->count() > 0)
            <div class="table-responsive">
                <table id="permissionsTable" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Module</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                        <tr>
                            <td>#{{ $permission->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-warning-transparent me-2">
                                        <i class="bx bx-check-circle fs-14"></i>
                                    </div>
                                    <span class="fw-semibold">{{ $permission->name }}</span>
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
                                <span class="badge bg-{{ $permission->is_active ? 'success' : 'danger' }}-transparent">
                                    {{ $permission->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $permission->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <x-table.actions 
                                    :viewRoute="route('admin.permissions.show', $permission)"
                                    :editRoute="route('admin.permissions.edit', $permission)"
                                    :deleteRoute="route('admin.permissions.destroy', $permission)"
                                    deleteTitle="Delete Permission"
                                    :deleteText="'Are you sure you want to delete ' . $permission->name . '? This action cannot be undone.'"
                                    deleteSuccessMessage="Permission deleted successfully"
                                />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bx bx-check-circle fs-48 text-muted mb-3 d-block"></i>
                <p class="text-muted">No permissions found</p>
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i>Add New Permission
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
        $('#permissionsTable').DataTable({
            order: [[0, 'desc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            responsive: true,
            language: {
                searchPlaceholder: 'Search permissions...',
                sSearch: '',
                lengthMenu: 'Show _MENU_ entries',
                info: 'Showing _START_ to _END_ of _TOTAL_ permissions',
                infoEmpty: 'No permissions found',
                infoFiltered: '(filtered from _MAX_ total permissions)',
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
