@extends('layouts.dashboard')

@section('title', 'Users')

@push('styles')
@if(file_exists(public_path('assets/libs/datatables/datatables.min.css')))
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables/datatables.min.css') }}">
@endif
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <x-card title="All Users">
            <x-slot name="headerActions">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                    <i class="bx bx-plus me-1"></i>Add New User
                </a>
            </x-slot>
            @if($users->count() > 0)
            <div class="table-responsive">
                <table id="usersTable" class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>#{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-primary-transparent me-2">
                                        <i class="bx bx-user fs-14"></i>
                                    </div>
                                    <span class="fw-semibold">{{ $user->name }}</span>
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
                                <span class="badge bg-{{ $user->email_verified_at ? 'success' : 'warning' }}-transparent">
                                    {{ $user->email_verified_at ? 'Verified' : 'Pending' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <x-table.actions 
                                    :viewRoute="route('admin.users.show', $user)"
                                    :editRoute="route('admin.users.edit', $user)"
                                    :deleteRoute="$user->id !== auth()->id() ? route('admin.users.destroy', $user) : null"
                                    deleteTitle="Delete User"
                                    :deleteText="'Are you sure you want to delete ' . $user->name . '? This action cannot be undone.'"
                                    deleteSuccessMessage="User deleted successfully"
                                />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bx bx-user fs-48 text-muted mb-3 d-block"></i>
                <p class="text-muted">No users found</p>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i>Add New User
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
        $('#usersTable').DataTable({
            order: [[0, 'desc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            responsive: true,
            language: {
                searchPlaceholder: 'Search users...',
                sSearch: '',
                lengthMenu: 'Show _MENU_ entries',
                info: 'Showing _START_ to _END_ of _TOTAL_ users',
                infoEmpty: 'No users found',
                infoFiltered: '(filtered from _MAX_ total users)',
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
