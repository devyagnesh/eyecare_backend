@extends('layouts.dashboard')

@section('title', 'Edit Setting')

@section('page-header')
<div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h1 class="page-title fw-medium fs-18 mb-2">Edit Setting</h1>
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    Setting Details
                </div>
            </div>
            <div class="card-body">
            <form action="{{ route('admin.settings.update', $setting) }}" method="POST" data-ajax="true" data-table-id="#settings-table">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            name="key" 
                            label="Key"
                            placeholder="e.g., app_name"
                            value="{{ old('key', $setting->key) }}"
                            required
                            help="Only letters, numbers, and underscores allowed"
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form-select 
                            name="type" 
                            label="Type"
                            :options="[
                                'string' => 'String',
                                'integer' => 'Integer',
                                'boolean' => 'Boolean',
                                'json' => 'JSON',
                                'text' => 'Text',
                                'float' => 'Float'
                            ]"
                            value="{{ old('type', $setting->type) }}"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-form-input 
                            name="group" 
                            label="Group"
                            placeholder="e.g., general, email, system"
                            value="{{ old('group', $setting->group) }}"
                            required
                            help="Group settings together (e.g., 'email', 'system', 'appearance')"
                        />
                    </div>
                    <div class="col-md-6">
                        <x-form-input 
                            name="value" 
                            label="Value"
                            placeholder="Enter setting value"
                            value="{{ old('value', $setting->value) }}"
                            help="Value will be cast according to the selected type"
                        />
                    </div>
                </div>

                <x-form-textarea 
                    name="description" 
                    label="Description"
                    placeholder="Describe what this setting does"
                    value="{{ old('description', $setting->description) }}"
                    rows="3"
                />

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="is_public" 
                               id="is_public" 
                               value="1"
                               {{ old('is_public', $setting->is_public) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_public">
                            Public Setting
                        </label>
                        <div class="form-text">Public settings can be accessed via API without authentication</div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <x-button type="submit" variant="primary" wave>
                        <i class="ri-save-line"></i> Update Setting
                    </x-button>
                    <x-button href="{{ route('admin.settings.index') }}" variant="secondary" wave>
                        <i class="ri-close-line"></i> Cancel
                    </x-button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection

