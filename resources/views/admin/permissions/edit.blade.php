@extends('layouts.dashboard')

@section('title', 'Edit Permission')

@section('content')
<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    <div class="card">
        <div class="flex flex-col items-center justify-between border-b border-slate-200 p-4 dark:border-navy-500 sm:flex-row">
            <h2 class="text-lg font-medium tracking-wide text-slate-700 dark:text-navy-100">Edit Permission: {{ $permission->name }}</h2>
            <div class="mt-2 sm:mt-0">
                <a href="{{ route('admin.permissions.index') }}" class="btn border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Permissions
                </a>
            </div>
        </div>

        <div class="p-4 sm:p-5">
            <form action="{{ route('admin.permissions.update', $permission) }}" method="POST" id="permissionForm">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Name Field -->
                    <label class="block">
                        <span class="flex items-center space-x-2">
                            <span>Permission Name</span>
                            <span class="text-error">*</span>
                        </span>
                        <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('name') border-error @enderror" 
                               type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $permission->name) }}" 
                               placeholder="Enter permission name" 
                               required />
                        @error('name')
                            <span class="mt-1.5 text-xs+ text-error">{{ $message }}</span>
                        @enderror
                    </label>

                    <!-- Slug Field -->
                    <label class="block">
                        <span>Slug</span>
                        <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('slug') border-error @enderror" 
                               type="text" 
                               id="slug" 
                               name="slug" 
                               value="{{ old('slug', $permission->slug) }}" 
                               placeholder="Auto-generated from name" />
                        <span class="mt-1.5 text-xs text-slate-400 dark:text-navy-300">Auto-generated from name if left blank</span>
                        @error('slug')
                            <span class="mt-1.5 text-xs+ text-error">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <!-- Module Field -->
                <label class="mt-4 block">
                    <span>Module</span>
                    <input class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('module') border-error @enderror" 
                           type="text" 
                           id="module" 
                           name="module" 
                           value="{{ old('module', $permission->module) }}" 
                           placeholder="e.g., users, roles, permissions" />
                    <span class="mt-1.5 text-xs text-slate-400 dark:text-navy-300">Optional: Group permissions by module</span>
                    @error('module')
                        <span class="mt-1.5 text-xs+ text-error">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Description Field -->
                <label class="mt-4 block">
                    <span>Description</span>
                    <textarea class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent @error('description') border-error @enderror" 
                              id="description" 
                              name="description" 
                              rows="3" 
                              placeholder="Enter permission description">{{ old('description', $permission->description) }}</textarea>
                    @error('description')
                        <span class="mt-1.5 text-xs+ text-error">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Active Switch -->
                <label class="mt-4 flex items-center space-x-2">
                    <input class="form-checkbox is-basic size-5 rounded border-slate-400/70 checked:border-primary checked:bg-primary hover:border-primary focus:border-primary dark:border-navy-400 dark:checked:border-accent dark:checked:bg-accent dark:hover:border-accent dark:focus:border-accent" 
                           type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           {{ old('is_active', $permission->is_active) ? 'checked' : '' }} />
                    <span>Active</span>
                </label>

                <!-- Form Actions -->
                <div class="mt-6 flex space-x-2">
                    <button type="submit" class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Permission
                    </button>
                    <a href="{{ route('admin.permissions.index') }}" class="btn border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
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
        $('#permissionForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                }
            },
            messages: {
                name: {
                    required: "Please enter a permission name",
                    minlength: "Permission name must be at least 2 characters"
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
