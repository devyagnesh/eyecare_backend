@extends('layouts.dashboard')

@section('title', 'Users')

@push('styles')
@if(file_exists(public_path('assets/libs/datatables/datatables.min.css')))
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables/datatables.min.css') }}">
@endif
@endpush

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    <div class="card">
        <div class="flex flex-col items-center justify-between border-b border-slate-200 p-4 dark:border-navy-500 sm:flex-row">
            <h2 class="text-lg font-medium tracking-wide text-slate-700 dark:text-navy-100">Users Management</h2>
            <div class="mt-2 sm:mt-0">
                <a href="{{ route('admin.users.create') }}" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New User
                </a>
            </div>
        </div>

        <div class="p-4 sm:p-5">
            @if(session('success'))
            <div class="alert flex rounded-lg bg-success/10 px-4 py-3 text-success mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert flex rounded-lg bg-error/10 px-4 py-3 text-error mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                {{ session('error') }}
            </div>
            @endif

            <div class="overflow-x-auto">
                <table id="usersTable" class="is-hoverable w-full text-left">
                    <thead>
                        <tr class="border-y border-slate-200 bg-slate-50 dark:border-navy-500 dark:bg-navy-700">
                            <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-700 dark:text-navy-100">ID</th>
                            <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-700 dark:text-navy-100">Name</th>
                            <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-700 dark:text-navy-100">Email</th>
                            <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-700 dark:text-navy-100">Role</th>
                            <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-700 dark:text-navy-100">Created At</th>
                            <th class="whitespace-nowrap px-4 py-3 font-semibold text-slate-700 dark:text-navy-100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                            <td class="whitespace-nowrap px-4 py-3">{{ $user->id }}</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $user->name }}</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $user->email }}</td>
                            <td class="whitespace-nowrap px-4 py-3">
                                @if($user->role)
                                    <span class="badge bg-primary text-white">{{ $user->role->name }}</span>
                                @else
                                    <span class="badge bg-slate-150 text-slate-800 dark:bg-navy-500 dark:text-navy-100">No Role</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25 text-error" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
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
@endsection

@push('scripts')
@if(file_exists(public_path('assets/libs/datatables/datatables.min.js')))
<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            order: [[0, 'desc']],
            pageLength: 15,
            responsive: true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: ''
            }
        });
    });
</script>
@endif
@endpush
