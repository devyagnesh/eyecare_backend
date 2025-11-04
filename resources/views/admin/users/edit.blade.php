@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-12">
        <x-card :title="'Edit User: ' . $user->name">
            <x-slot name="headerActions">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i>Back to Users
                </a>
            </x-slot>
            <form action="{{ route('admin.users.update', $user) }}" method="POST" id="userForm" data-ajax="true" data-success-message="User updated successfully" data-redirect-on-success="true" data-redirect-url="{{ route('admin.users.index') }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <x-form.input 
                            name="name"
                            label="Full Name"
                            :value="$user->name"
                            placeholder="Enter full name"
                            :required="true"
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form.input 
                            name="email"
                            type="email"
                            label="Email Address"
                            :value="$user->email"
                            placeholder="Enter email address"
                            :required="true"
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form.input 
                            name="password"
                            type="password"
                            label="New Password"
                            placeholder="Leave blank to keep current password"
                            helpText="Leave blank to keep current password"
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form.input 
                            name="password_confirmation"
                            type="password"
                            label="Confirm New Password"
                            placeholder="Confirm new password"
                        />
                    </div>

                    <div class="col-md-12">
                        <x-form.select 
                            name="role_id"
                            label="Role"
                            :options="$roles->pluck('name', 'id')->toArray()"
                            :value="$user->role_id"
                            placeholder="Select a role"
                            :required="true"
                        />
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check me-1"></i>Update User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </x-card>
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
