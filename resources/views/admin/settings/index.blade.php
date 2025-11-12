@extends('layouts.dashboard')

@section('title', 'Settings Management')

@push('styles')
@if(file_exists(public_path('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
@endif
@if(file_exists(public_path('assets/libs/datatables.net-responsive/css/responsive.bootstrap.min.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-responsive/css/responsive.bootstrap.min.css') }}">
@endif
@endpush

@section('page-header')
<div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h1 class="page-title fw-medium fs-18 mb-2">Settings Management</h1>
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Settings</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="btn-list">
        <a href="{{ route('admin.settings.create') }}" class="btn btn-primary-light btn-wave">
            <i class="ri-add-line align-middle"></i> Add New Setting
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
                    Settings
                </div>
            </div>
            <div class="card-body">
            <!-- Filters -->
            <form method="GET" action="{{ route('admin.settings.index') }}" class="mb-4" data-ajax-filter="true" data-table-id="#settings-table">
                <div class="row g-3">
                    <div class="col-md-3">
                        <x-form-input 
                            name="search" 
                            placeholder="Search by key or description"
                            value="{{ $filters['search'] ?? '' }}"
                        />
                    </div>
                    <div class="col-md-2">
                        <x-form-select 
                            name="group" 
                            placeholder="All Groups"
                            :options="array_combine($groups, $groups)"
                            value="{{ $filters['group'] ?? '' }}"
                        />
                    </div>
                    <div class="col-md-2">
                        <x-form-select 
                            name="type" 
                            placeholder="All Types"
                            :options="[
                                'string' => 'String',
                                'integer' => 'Integer',
                                'boolean' => 'Boolean',
                                'json' => 'JSON',
                                'text' => 'Text',
                                'float' => 'Float'
                            ]"
                            value="{{ $filters['type'] ?? '' }}"
                        />
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table id="settings-table" class="table table-bordered text-nowrap w-100 table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Key</th>
                            <th>Value</th>
                            <th>Type</th>
                            <th>Group</th>
                            <th>Public</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($settings as $index => $setting)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <code class="text-primary">{{ $setting->key }}</code>
                            </td>
                            <td>
                                @if($setting->type === 'boolean')
                                    <span class="badge bg-{{ $setting->getCastedValue() ? 'success' : 'danger' }}-transparent">
                                        {{ $setting->getCastedValue() ? 'Yes' : 'No' }}
                                    </span>
                                @elseif($setting->type === 'json')
                                    <code class="text-muted">{{ Str::limit(json_encode($setting->getCastedValue()), 50) }}</code>
                                @else
                                    {{ Str::limit($setting->value, 50) }}
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info-transparent">{{ $setting->type }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary-transparent">{{ $setting->group }}</span>
                            </td>
                            <td>
                                @if($setting->is_public)
                                    <span class="badge bg-success-transparent">Yes</span>
                                @else
                                    <span class="badge bg-warning-transparent">No</span>
                                @endif
                            </td>
                            <td>{{ $setting->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.settings.show', $setting) }}" 
                                       class="btn btn-sm btn-info-light" 
                                       title="View">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.settings.edit', $setting) }}" 
                                       class="btn btn-sm btn-primary-light" 
                                       title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <form action="{{ route('admin.settings.destroy', $setting) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger-light ajax-delete" 
                                                title="Delete"
                                                data-table-id="#settings-table"
                                                data-confirm="Are you sure you want to delete this setting?">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
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
</div>
@endsection

@push('scripts')
<!-- DataTables JS -->
@if(file_exists(public_path('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js')))
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
@endif

@if(file_exists(public_path('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')))
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
@endif

@if(file_exists(public_path('assets/libs/datatables.net-responsive/js/responsive.bootstrap.min.js')))
<script src="{{ asset('assets/libs/datatables.net-responsive/js/responsive.bootstrap.min.js') }}"></script>
@endif

<!-- DataTables Init -->
@if(file_exists(public_path('assets/js/datatables-init.js')))
<script src="{{ asset('assets/js/datatables-init.js') }}?v={{ filemtime(public_path('assets/js/datatables-init.js')) }}"></script>
@endif

<script>
$(document).ready(function() {
    // Initialize settings DataTable with theme-standard settings
    if (typeof window.initDataTable !== 'undefined') {
        window.initDataTable('#settings-table', {
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            order: [[6, 'desc']], // Order by Created At column (7th column, index 6)
            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 7] // Disable sorting on # and Actions columns
                }
            ]
        });
    } else if ($.fn.DataTable) {
        // Fallback initialization
        $('#settings-table').DataTable({
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            order: [[6, 'desc']], // Order by Created At column (7th column, index 6)
            responsive: true,
            searching: false, // Hide search box
            lengthChange: false, // Hide per page dropdown
            columnDefs: [
                {
                    orderable: false,
                    targets: [0, 7] // Disable sorting on # and Actions columns
                }
            ]
        });
    }
});
</script>
@endpush

