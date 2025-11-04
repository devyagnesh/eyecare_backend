@extends('layouts.dashboard')

@section('title', 'Create Permission')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card title="Create New Permission">
            <x-slot name="headerActions">
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i>Back to Permissions
                </a>
            </x-slot>
            <form action="{{ route('admin.permissions.store') }}" method="POST" id="permissionForm" data-ajax="true" data-success-message="Permission created successfully" data-redirect-on-success="true" data-redirect-url="{{ route('admin.permissions.index') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <x-form.input 
                            name="name"
                            label="Permission Name"
                            placeholder="Enter permission name"
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
                        <x-form.input 
                            name="module"
                            label="Module"
                            placeholder="e.g., users, roles, permissions"
                            helpText="Optional: Group permissions by module"
                        />
                    </div>

                    <div class="col-md-12">
                        <x-form.textarea 
                            name="description"
                            label="Description"
                            placeholder="Enter permission description"
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
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check me-1"></i>Create Permission
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
