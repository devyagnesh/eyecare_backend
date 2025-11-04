@extends('layouts.dashboard')

@section('title', 'Create Role')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card title="Create New Role">
            <x-slot name="headerActions">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i>Back to Roles
                </a>
            </x-slot>
            <form action="{{ route('admin.roles.store') }}" method="POST" id="roleForm" data-ajax="true" data-success-message="Role created successfully" data-redirect-on-success="true" data-redirect-url="{{ route('admin.roles.index') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <x-form.input 
                            name="name"
                            label="Role Name"
                            placeholder="Enter role name"
                            :required="true"
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form.input 
                            name="slug"
                            label="Slug"
                            placeholder="Auto-generated from name"
                            helpText="Auto-generated from name if left blank"
                        />
                    </div>

                    <div class="col-md-12">
                        <x-form.textarea 
                            name="description"
                            label="Description"
                            placeholder="Enter role description"
                            :rows="3"
                        />
                    </div>

                    <div class="col-md-12">
                        <x-form.checkbox 
                            name="is_active"
                            label="Active"
                            :checked="true"
                        />
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold mb-3">Permissions</label>
                        <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                            @foreach($permissions as $module => $modulePermissions)
                            <div class="mb-4">
                                <h6 class="mb-2 fw-semibold">{{ $module ?: 'General' }}</h6>
                                <div class="d-flex flex-column gap-2">
                                    @foreach($modulePermissions as $permission)
                                    <div class="form-check">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="permissions[]" 
                                            value="{{ $permission->id }}" 
                                            id="perm_{{ $permission->id }}"
                                            {{ old('permissions') && in_array($permission->id, old('permissions')) ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            <div>
                                                <strong>{{ $permission->name }}</strong>
                                                @if($permission->description)
                                                    <br><small class="text-muted">{{ $permission->description }}</small>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check me-1"></i>Create Role
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection
