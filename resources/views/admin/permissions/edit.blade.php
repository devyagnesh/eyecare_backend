@extends('layouts.dashboard')

@section('title', 'Edit Permission')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card :title="'Edit Permission: ' . $permission->name">
            <x-slot name="headerActions">
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i>Back to Permissions
                </a>
            </x-slot>
            <form action="{{ route('admin.permissions.update', $permission) }}" method="POST" id="permissionForm" data-ajax="true" data-success-message="Permission updated successfully" data-redirect-on-success="true" data-redirect-url="{{ route('admin.permissions.index') }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <x-form.input 
                            name="name"
                            label="Permission Name"
                            :value="$permission->name"
                            placeholder="Enter permission name"
                            :required="true"
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form.input 
                            name="slug"
                            label="Slug"
                            :value="$permission->slug"
                            placeholder="Auto-generated from name"
                            helpText="Auto-generated from name if left blank"
                        />
                    </div>

                    <div class="col-md-12">
                        <x-form.input 
                            name="module"
                            label="Module"
                            :value="$permission->module"
                            placeholder="e.g., users, roles, permissions"
                            helpText="Optional: Group permissions by module"
                        />
                    </div>

                    <div class="col-md-12">
                        <x-form.textarea 
                            name="description"
                            label="Description"
                            :value="$permission->description"
                            placeholder="Enter permission description"
                            :rows="3"
                        />
                    </div>

                    <div class="col-md-12">
                        <x-form.checkbox 
                            name="is_active"
                            label="Active"
                            :checked="$permission->is_active"
                        />
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check me-1"></i>Update Permission
                    </button>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection
