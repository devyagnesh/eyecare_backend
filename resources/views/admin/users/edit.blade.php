@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    <div class="card">
        <div class="flex flex-col items-center justify-between border-b border-slate-200 p-4 dark:border-navy-500 sm:flex-row">
            <h2 class="text-lg font-medium tracking-wide text-slate-700 dark:text-navy-100">Edit User: {{ $user->name }}</h2>
            <div class="mt-2 sm:mt-0">
                <a href="{{ route('admin.users.index') }}" class="btn border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>

        <div class="p-4 sm:p-5">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" id="userForm">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Name Field -->
                    <label class="block">
                        <span class="flex items-center space-x-2">
                            <span>Full Name</span>
                            <span class="text-error">*</span>
                        </span>
                        <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('name') border-error @enderror" 
                               type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               placeholder="Enter full name" 
                               required />
                        @error('name')
                            <span class="mt-1.5 text-xs+ text-error">{{ $message }}</span>
                        @enderror
                    </label>

                    <!-- Email Field -->
                    <label class="block">
                        <span class="flex items-center space-x-2">
                            <span>Email Address</span>
                            <span class="text-error">*</span>
                        </span>
                        <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('email') border-error @enderror" 
                               type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               placeholder="Enter email address" 
                               required />
                        @error('email')
                            <span class="mt-1.5 text-xs+ text-error">{{ $message }}</span>
                        @enderror
                    </label>

                    <!-- Password Field -->
                    <label class="block">
                        <span>New Password</span>
                        <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('password') border-error @enderror" 
                               type="password" 
                               id="password" 
                               name="password" 
                               placeholder="Leave blank to keep current password" />
                        <span class="mt-1.5 text-xs text-slate-400 dark:text-navy-300">Leave blank to keep current password</span>
                        @error('password')
                            <span class="mt-1.5 text-xs+ text-error">{{ $message }}</span>
                        @enderror
                    </label>

                    <!-- Confirm Password Field -->
                    <label class="block">
                        <span>Confirm New Password</span>
                        <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                               type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Confirm new password" />
                    </label>
                </div>

                <!-- Role Field -->
                <label class="mt-4 block">
                    <span class="flex items-center space-x-2">
                        <span>Role</span>
                        <span class="text-error">*</span>
                    </span>
                    <select class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('role_id') border-error @enderror" 
                            id="role_id" 
                            name="role_id" 
                            required>
                        <option value="">Select a role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <span class="mt-1.5 text-xs+ text-error">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Form Actions -->
                <div class="mt-6 flex space-x-2">
                    <button type="submit" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(file_exists(public_path('assets/libs/jquery-validation/jquery.validate.min.js')))
<script>
    $(document).ready(function() {
        $('#userForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    minlength: 8
                },
                password_confirmation: {
                    equalTo: "#password"
                },
                role_id: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Please enter the user's name",
                    minlength: "Name must be at least 2 characters"
                },
                email: {
                    required: "Please enter an email address",
                    email: "Please enter a valid email address"
                },
                password: {
                    minlength: "Password must be at least 8 characters"
                },
                password_confirmation: {
                    equalTo: "Passwords do not match"
                },
                role_id: {
                    required: "Please select a role"
                }
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });
    });
</script>
@endif
@endpush
