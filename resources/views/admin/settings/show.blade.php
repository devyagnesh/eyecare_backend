@extends('layouts.dashboard')

@section('title', 'Setting Details')

@section('page-header')
<div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h1 class="page-title fw-medium fs-18 mb-2">Setting Details</h1>
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="btn-list">
        <a href="{{ route('admin.settings.edit', $setting) }}" class="btn btn-primary-light btn-wave">
            <i class="ri-pencil-line align-middle"></i> Edit
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    Setting Information
                </div>
            </div>
            <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>ID:</strong>
                </div>
                <div class="col-md-9">
                    {{ $setting->id }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Key:</strong>
                </div>
                <div class="col-md-9">
                    <code class="text-primary">{{ $setting->key }}</code>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Value:</strong>
                </div>
                <div class="col-md-9">
                    @if($setting->type === 'boolean')
                        <span class="badge bg-{{ $setting->getCastedValue() ? 'success' : 'danger' }}-transparent">
                            {{ $setting->getCastedValue() ? 'Yes' : 'No' }}
                        </span>
                    @elseif($setting->type === 'json')
                        <pre class="bg-light p-3 rounded"><code>{{ json_encode($setting->getCastedValue(), JSON_PRETTY_PRINT) }}</code></pre>
                    @else
                        {{ $setting->value }}
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Type:</strong>
                </div>
                <div class="col-md-9">
                    <span class="badge bg-info-transparent">{{ $setting->type }}</span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Group:</strong>
                </div>
                <div class="col-md-9">
                    <span class="badge bg-secondary-transparent">{{ $setting->group }}</span>
                </div>
            </div>

            @if($setting->description)
            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Description:</strong>
                </div>
                <div class="col-md-9">
                    {{ $setting->description }}
                </div>
            </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Public:</strong>
                </div>
                <div class="col-md-9">
                    @if($setting->is_public)
                        <span class="badge bg-success-transparent">Yes</span>
                    @else
                        <span class="badge bg-warning-transparent">No</span>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Created At:</strong>
                </div>
                <div class="col-md-9">
                    {{ $setting->created_at->format('F d, Y \a\t H:i A') }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Updated At:</strong>
                </div>
                <div class="col-md-9">
                    {{ $setting->updated_at->format('F d, Y \a\t H:i A') }}
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <x-button href="{{ route('admin.settings.edit', $setting) }}" variant="primary" wave>
                    <i class="ri-pencil-line"></i> Edit
                </x-button>
                <form action="{{ route('admin.settings.destroy', $setting) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger" wave onclick="return confirm('Are you sure?')">
                        <i class="ri-delete-bin-line"></i> Delete
                    </x-button>
                </form>
                <x-button href="{{ route('admin.settings.index') }}" variant="secondary" wave>
                    <i class="ri-arrow-left-line"></i> Back to List
                </x-button>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

