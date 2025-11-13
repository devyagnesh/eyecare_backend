@extends('layouts.dashboard')

@section('title', 'API Documentation')

@push('styles')
<style>
    /* Modern API Documentation Design - Works with Theme */
    .api-docs-container {
        position: relative;
    }

    /* Search Section in Page Header */
    .api-docs-search-wrapper {
        margin-top: 1.5rem;
        padding: 1.5rem;
        background: var(--default-background, #f9fafb);
        border-radius: 8px;
        border: 1px solid var(--default-border, #f3f2f9);
    }

    .docs-search-container {
        position: relative;
        margin-bottom: 1rem;
    }

    .docs-search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 3rem;
        border: 1px solid var(--input-border, #e2e6f1);
        border-radius: 6px;
        background: var(--form-control-bg, #ffffff);
        color: var(--default-text-color, #222f36);
        font-size: 0.9375rem;
        transition: all 0.2s;
    }

    .docs-search-input:focus {
        outline: none;
        border-color: rgb(var(--primary-rgb, 115, 93, 255));
        box-shadow: 0 0 0 3px rgba(var(--primary-rgb, 115, 93, 255), 0.1);
    }

    .docs-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted, #98a5c3);
    }

    .docs-search-clear {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: var(--text-muted, #98a5c3);
        cursor: pointer;
        padding: 0.25rem;
        display: none;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }

    .docs-search-clear:hover {
        background: var(--list-hover-focus-bg, #f5f6f7);
    }

    .docs-search-clear.visible {
        display: flex;
    }

    .keyboard-hint {
        position: absolute;
        right: 3rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.75rem;
        color: var(--text-muted, #98a5c3);
        background: var(--default-background, #f9fafb);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        border: 1px solid var(--default-border, #f3f2f9);
        font-family: 'Monaco', 'Menlo', monospace;
    }

    .docs-filters {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .filter-pill {
        padding: 0.5rem 1rem;
        background: var(--form-control-bg, #ffffff);
        border: 1px solid var(--input-border, #e2e6f1);
        border-radius: 20px;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--default-text-color, #222f36);
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-pill:hover {
        border-color: rgb(var(--primary-rgb, 115, 93, 255));
        background: rgba(var(--primary-rgb, 115, 93, 255), 0.05);
    }

    .filter-pill.active {
        background: rgb(var(--primary-rgb, 115, 93, 255));
        border-color: rgb(var(--primary-rgb, 115, 93, 255));
        color: white;
    }

    .docs-search-stats {
        margin-top: 0.75rem;
        font-size: 0.875rem;
        color: var(--text-muted, #98a5c3);
        display: none;
    }

    .docs-search-stats.visible {
        display: block;
    }

    /* API Documentation Content */
    .api-docs-content {
        margin-top: 2rem;
    }

    .docs-hero {
        text-align: center;
        margin-bottom: 3rem;
        padding: 2rem 0;
    }

    .docs-hero-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--default-text-color, #222f36);
        margin-bottom: 1rem;
    }

    .docs-hero-description {
        font-size: 1.125rem;
        color: var(--text-muted, #98a5c3);
        max-width: 700px;
        margin: 0 auto;
    }

    .docs-group {
        margin-bottom: 4rem;
        scroll-margin-top: 100px;
    }

    .docs-group-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid var(--default-border, #f3f2f9);
    }

    .docs-group-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--default-text-color, #222f36);
        margin-bottom: 0.75rem;
    }

    .docs-group-description {
        font-size: 1rem;
        color: var(--text-muted, #98a5c3);
    }

    /* Endpoint Cards */
    .endpoint-card {
        background: var(--form-control-bg, #ffffff);
        border: 1px solid var(--default-border, #f3f2f9);
        border-radius: 8px;
        padding: 2rem;
        margin-bottom: 2rem;
        transition: all 0.3s;
        scroll-margin-top: 100px;
    }

    .endpoint-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border-color: rgb(var(--primary-rgb, 115, 93, 255));
    }

    .endpoint-header {
        margin-bottom: 1.5rem;
    }

    .endpoint-method-url {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .method-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        min-width: 70px;
        text-align: center;
    }

    .method-get { background: #10b981; color: white; }
    .method-post { background: #3b82f6; color: white; }
    .method-put { background: #f59e0b; color: white; }
    .method-delete { background: #ef4444; color: white; }
    .method-patch { background: #8b5cf6; color: white; }

    .endpoint-url {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.9375rem;
        color: rgb(var(--primary-rgb, 115, 93, 255));
        font-weight: 600;
        background: rgba(var(--primary-rgb, 115, 93, 255), 0.1);
        padding: 0.5rem 0.875rem;
        border-radius: 6px;
    }

    .endpoint-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--default-text-color, #222f36);
        margin: 0.75rem 0 0.5rem 0;
    }

    .endpoint-description {
        font-size: 1rem;
        color: var(--text-muted, #98a5c3);
        line-height: 1.6;
    }

    /* Sections */
    .docs-section {
        margin: 2rem 0;
    }

    .docs-section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--default-text-color, #222f36);
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--default-border, #f3f2f9);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .docs-section-title i {
        color: rgb(var(--primary-rgb, 115, 93, 255));
    }

    /* Tables */
    .docs-table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        font-size: 0.9375rem;
        background: var(--form-control-bg, #ffffff);
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--default-border, #f3f2f9);
    }

    .docs-table th {
        background: var(--default-background, #f9fafb);
        padding: 1rem 1.25rem;
        text-align: left;
        font-weight: 600;
        color: var(--default-text-color, #222f36);
        border-bottom: 2px solid var(--default-border, #f3f2f9);
        font-size: 0.875rem;
    }

    .docs-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--default-border, #f3f2f9);
        color: var(--default-text-color, #222f36);
    }

    .docs-table tr:last-child td {
        border-bottom: none;
    }

    .docs-table tbody tr:hover {
        background: var(--default-background, #f9fafb);
    }

    .param-code {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        color: rgb(var(--primary-rgb, 115, 93, 255));
        font-weight: 600;
        font-size: 0.875rem;
        background: rgba(var(--primary-rgb, 115, 93, 255), 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }

    .type-badge {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        color: #10b981;
        background: rgba(16, 185, 129, 0.1);
        padding: 0.25rem 0.625rem;
        border-radius: 4px;
        font-size: 0.8125rem;
        font-weight: 600;
    }

    .required-tag {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        padding: 0.25rem 0.625rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .optional-tag {
        background: var(--default-background, #f9fafb);
        color: var(--text-muted, #98a5c3);
        padding: 0.25rem 0.625rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Code Blocks */
    .code-block-wrapper {
        position: relative;
        margin: 1.5rem 0;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--default-border, #f3f2f9);
        background: #1e293b;
    }

    .code-block-header {
        background: rgba(15, 23, 42, 0.8);
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .code-block-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #cbd5e1;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .code-copy-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #cbd5e1;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .code-copy-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .code-copy-btn.copied {
        background: #10b981;
        border-color: #10b981;
        color: white;
    }

    .code-block-content {
        background: #1e293b;
        padding: 1.5rem;
        overflow-x: auto;
    }

    .code-block-content pre {
        margin: 0;
        color: #e2e8f0;
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.875rem;
        line-height: 1.7;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    /* Status Codes */
    .status-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
        margin: 1.5rem 0;
    }

    .status-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--default-background, #f9fafb);
        border-radius: 8px;
        border-left: 4px solid;
    }

    .status-code-badge {
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        min-width: 65px;
        text-align: center;
    }

    .status-2xx { 
        background: rgba(16, 185, 129, 0.1);
        border-left-color: #10b981;
    }
    .status-2xx .status-code-badge {
        background: #10b981;
        color: white;
    }
    .status-4xx { 
        background: rgba(239, 68, 68, 0.1);
        border-left-color: #ef4444;
    }
    .status-4xx .status-code-badge {
        background: #ef4444;
        color: white;
    }
    .status-5xx { 
        background: rgba(245, 158, 11, 0.1);
        border-left-color: #f59e0b;
    }
    .status-5xx .status-code-badge {
        background: #f59e0b;
        color: white;
    }

    /* Callout Boxes */
    .callout {
        padding: 1.25rem 1.5rem;
        border-radius: 8px;
        margin: 2rem 0;
        border-left: 4px solid;
    }

    .callout-info {
        background: rgba(var(--primary-rgb, 115, 93, 255), 0.1);
        border-left-color: rgb(var(--primary-rgb, 115, 93, 255));
    }

    .callout-warning {
        background: rgba(245, 158, 11, 0.1);
        border-left-color: #f59e0b;
    }

    .callout p {
        margin: 0;
        font-size: 0.9375rem;
        line-height: 1.7;
    }

    .callout p + p {
        margin-top: 0.5rem;
    }

    /* No Results */
    .no-results {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-muted, #98a5c3);
    }

    .no-results-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        background: var(--default-background, #f9fafb);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: var(--text-muted, #98a5c3);
        opacity: 0.5;
    }

    .no-results h3 {
        font-size: 1.5rem;
        margin: 0 0 0.5rem 0;
        color: var(--default-text-color, #222f36);
        font-weight: 700;
    }

    /* API Groups Sidebar Navigation */
    .api-groups-nav {
        position: sticky;
        top: 100px;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }

    .api-groups-nav .card {
        border: 1px solid var(--default-border, #f3f2f9);
    }

    .api-groups-nav .list-group-item {
        border: none;
        border-bottom: 1px solid var(--default-border, #f3f2f9);
        padding: 0.75rem 1rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .api-groups-nav .list-group-item:hover {
        background: var(--default-background, #f9fafb);
        color: rgb(var(--primary-rgb, 115, 93, 255));
    }

    .api-groups-nav .list-group-item.active {
        background: rgba(var(--primary-rgb, 115, 93, 255), 0.1);
        color: rgb(var(--primary-rgb, 115, 93, 255));
        font-weight: 600;
        border-left: 3px solid rgb(var(--primary-rgb, 115, 93, 255));
    }

    .api-groups-nav .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@endpush

@section('page-header')
<div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h1 class="page-title fw-medium fs-18 mb-2">API Documentation</h1>
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">API Documentation</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="btn-list">
        <a href="{{ route('admin.api-documentation.download') }}" class="btn btn-primary btn-wave">
            <i class="ri-download-line align-middle"></i> Download Postman Collection
        </a>
    </div>
</div>

<!-- Search and Filters -->
<div class="api-docs-search-wrapper">
    <div class="docs-search-container">
        <i class="ri-search-line docs-search-icon"></i>
        <input type="text" 
               class="docs-search-input" 
               id="api-search" 
               placeholder="Search endpoints, methods, URLs... (Press / to focus)"
               autocomplete="off">
        <button class="docs-search-clear" id="search-clear" title="Clear search">
            <i class="ri-close-line"></i>
        </button>
        <span class="keyboard-hint">/</span>
    </div>
    
    <div class="docs-filters" id="search-filters">
        <button class="filter-pill active" data-filter="all">
            <i class="ri-list-check"></i>
            <span>All</span>
        </button>
        <button class="filter-pill" data-filter="get">
            <i class="ri-download-line"></i>
            <span>GET</span>
        </button>
        <button class="filter-pill" data-filter="post">
            <i class="ri-upload-line"></i>
            <span>POST</span>
        </button>
        <button class="filter-pill" data-filter="put">
            <i class="ri-edit-line"></i>
            <span>PUT</span>
        </button>
        <button class="filter-pill" data-filter="delete">
            <i class="ri-delete-bin-line"></i>
            <span>DELETE</span>
        </button>
    </div>
    
    <div class="docs-search-stats" id="search-results">
        <strong id="results-count">0</strong> endpoint(s) found
    </div>
</div>
@endsection

@section('content')
<div class="api-docs-container">
    <div class="row">
        <!-- API Groups Navigation Sidebar -->
        <div class="col-xl-3 col-lg-4 d-lg-block d-none">
            <div class="api-groups-nav">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">API Groups</div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush" id="sidebar-nav">
                            @foreach($endpoints as $groupIndex => $group)
                            <li class="list-group-item {{ $groupIndex === 0 ? 'active' : '' }}" 
                                data-group="group-{{ $groupIndex }}">
                                <a href="#group-{{ $groupIndex }}" class="text-decoration-none d-flex align-items-center gap-2">
                                    <i class="ri-{{ $group['icon'] }}-line"></i>
                                    <span>{{ $group['group'] }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-xl-9 col-lg-8">
            <div class="api-docs-content">
                <!-- Hero Section -->
                <div class="docs-hero" id="getting-started">
                    <h1 class="docs-hero-title">API Documentation</h1>
                    <p class="docs-hero-description">
                        Comprehensive guide to integrate with our RESTful API. Explore endpoints, understand request formats, and build amazing applications.
                    </p>
                </div>

                <!-- Getting Started Info -->
                <div class="callout callout-info">
                    <p><strong>Base URL:</strong> <code style="background: rgba(var(--primary-rgb, 115, 93, 255), 0.2); padding: 4px 8px; border-radius: 4px; font-family: monospace; font-weight: 600;">{{ $baseUrl }}</code></p>
                    <p><strong>Authentication:</strong> Most endpoints require authentication. Include your Bearer token in the Authorization header.</p>
                    <p><strong>Rate Limiting:</strong> API requests are rate-limited. Check response headers for rate limit information.</p>
                </div>

                <!-- API Groups -->
                @foreach($endpoints as $groupIndex => $group)
                <div class="docs-group" id="group-{{ $groupIndex }}" data-group-name="{{ strtolower($group['group']) }}">
                    <div class="docs-group-header">
                        <h1 class="docs-group-title">{{ $group['group'] }}</h1>
                        <p class="docs-group-description">{{ $group['description'] }}</p>
                    </div>

                    @foreach($group['endpoints'] as $endpointIndex => $endpoint)
                    <article class="endpoint-card" 
                             id="endpoint-{{ $groupIndex }}-{{ $endpointIndex }}"
                             data-endpoint-name="{{ strtolower($endpoint['name']) }}"
                             data-endpoint-method="{{ strtolower($endpoint['method']) }}"
                             data-endpoint-url="{{ strtolower($endpoint['url']) }}"
                             data-endpoint-description="{{ strtolower($endpoint['description']) }}">
                        
                        <div class="endpoint-header">
                            <div class="endpoint-method-url">
                                <span class="method-badge method-{{ strtolower($endpoint['method']) }}">
                                    {{ $endpoint['method'] }}
                                </span>
                                <code class="endpoint-url">{{ $endpoint['url'] }}</code>
                            </div>
                            <h2 class="endpoint-title">{{ $endpoint['name'] }}</h2>
                            <p class="endpoint-description">{{ $endpoint['description'] ?: 'No description available.' }}</p>
                        </div>

                        <!-- Headers -->
                        @if(!empty($endpoint['headers']))
                        <div class="docs-section">
                            <h3 class="docs-section-title">
                                <i class="ri-file-list-3-line"></i>
                                Headers
                            </h3>
                            <table class="docs-table">
                                <thead>
                                    <tr>
                                        <th>Header</th>
                                        <th>Type</th>
                                        <th>Required</th>
                                        <th>Description</th>
                                        <th>Example</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($endpoint['headers'] as $headerName => $headerData)
                                    <tr>
                                        <td><code class="param-code">{{ $headerName }}</code></td>
                                        <td><span class="type-badge">{{ $headerData['type'] ?? 'string' }}</span></td>
                                        <td>
                                            @if($headerData['required'] ?? false)
                                            <span class="required-tag">Required</span>
                                            @else
                                            <span class="optional-tag">Optional</span>
                                            @endif
                                        </td>
                                        <td>{{ $headerData['description'] ?? '' }}</td>
                                        <td><code style="font-size: 0.8125rem; background: var(--default-background, #f9fafb); padding: 4px 8px; border-radius: 4px;">{{ $headerData['example'] ?? '' }}</code></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <!-- Query Parameters -->
                        @if(!empty($endpoint['query_parameters']))
                        <div class="docs-section">
                            <h3 class="docs-section-title">
                                <i class="ri-search-line"></i>
                                Query Parameters
                            </h3>
                            <table class="docs-table">
                                <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Type</th>
                                        <th>Required</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($endpoint['query_parameters'] as $paramName => $paramDetails)
                                    <tr>
                                        <td><code class="param-code">{{ $paramName }}</code></td>
                                        <td><span class="type-badge">{{ $paramDetails['type'] ?? 'string' }}</span></td>
                                        <td>
                                            @if($paramDetails['required'] ?? false)
                                            <span class="required-tag">Required</span>
                                            @else
                                            <span class="optional-tag">Optional</span>
                                            @endif
                                        </td>
                                        <td>{{ $paramDetails['description'] ?? '' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <!-- Request Parameters -->
                        @if(!empty($endpoint['parameters']))
                        <div class="docs-section">
                            <h3 class="docs-section-title">
                                <i class="ri-file-edit-line"></i>
                                Request Parameters
                            </h3>
                            <table class="docs-table">
                                <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Type</th>
                                        <th>Required</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($endpoint['parameters'] as $paramName => $paramDetails)
                                    <tr>
                                        <td><code class="param-code">{{ $paramName }}</code></td>
                                        <td><span class="type-badge">{{ $paramDetails['type'] ?? 'string' }}</span></td>
                                        <td>
                                            @if($paramDetails['required'] ?? false)
                                            <span class="required-tag">Required</span>
                                            @else
                                            <span class="optional-tag">Optional</span>
                                            @endif
                                        </td>
                                        <td>{{ $paramDetails['description'] ?? '' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <!-- Request Payload -->
                        @if($endpoint['payload_example'])
                        <div class="docs-section">
                            <h3 class="docs-section-title">
                                <i class="ri-code-s-slash-line"></i>
                                Request Payload
                            </h3>
                            <div class="code-block-wrapper">
                                <div class="code-block-header">
                                    <span class="code-block-label">JSON</span>
                                    <button class="code-copy-btn" onclick="copyCode('payload-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                        <i class="ri-file-copy-line"></i>
                                        <span>Copy</span>
                                    </button>
                                </div>
                                <div class="code-block-content">
                                    <pre id="payload-{{ $groupIndex }}-{{ $endpointIndex }}"><code class="json">{{ is_string($endpoint['payload_example']) ? $endpoint['payload_example'] : json_encode($endpoint['payload_example'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Success Response -->
                        @if($endpoint['success_response'])
                        <div class="docs-section">
                            <h3 class="docs-section-title" style="color: #10b981;">
                                <i class="ri-checkbox-circle-line"></i>
                                Success Response
                            </h3>
                            <div class="code-block-wrapper">
                                <div class="code-block-header">
                                    <span class="code-block-label">JSON</span>
                                    <button class="code-copy-btn" onclick="copyCode('success-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                        <i class="ri-file-copy-line"></i>
                                        <span>Copy</span>
                                    </button>
                                </div>
                                <div class="code-block-content">
                                    <pre id="success-{{ $groupIndex }}-{{ $endpointIndex }}"><code class="json">{{ is_string($endpoint['success_response']) ? $endpoint['success_response'] : json_encode($endpoint['success_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Error Response -->
                        @if($endpoint['error_response'])
                        <div class="docs-section">
                            <h3 class="docs-section-title" style="color: #ef4444;">
                                <i class="ri-error-warning-line"></i>
                                Error Response
                            </h3>
                            <div class="code-block-wrapper">
                                <div class="code-block-header">
                                    <span class="code-block-label">JSON</span>
                                    <button class="code-copy-btn" onclick="copyCode('error-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                        <i class="ri-file-copy-line"></i>
                                        <span>Copy</span>
                                    </button>
                                </div>
                                <div class="code-block-content">
                                    <pre id="error-{{ $groupIndex }}-{{ $endpointIndex }}"><code class="json">{{ is_string($endpoint['error_response']) ? $endpoint['error_response'] : json_encode($endpoint['error_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Status Codes -->
                        @if(!empty($endpoint['status_codes']))
                        <div class="docs-section">
                            <h3 class="docs-section-title">
                                <i class="ri-information-line"></i>
                                Status Codes
                            </h3>
                            <div class="status-list">
                                @foreach($endpoint['status_codes'] as $code => $message)
                                <div class="status-item status-{{ floor($code / 100) }}xx">
                                    <span class="status-code-badge">{{ $code }}</span>
                                    <span>{{ $message }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Notes -->
                        @if(!empty($endpoint['notes']))
                        <div class="callout callout-warning">
                            <p><strong>Note:</strong> {{ $endpoint['notes'] }}</p>
                        </div>
                        @endif
                    </article>
                    @endforeach
                </div>
                @endforeach

                <!-- No Results -->
                <div id="no-results" class="no-results" style="display: none;">
                    <div class="no-results-icon">
                        <i class="ri-search-line"></i>
                    </div>
                    <h3>No endpoints found</h3>
                    <p>Try adjusting your search criteria or filters</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function($) {
    'use strict';

    const searchInput = document.getElementById('api-search');
    const searchClear = document.getElementById('search-clear');
    const searchStats = document.getElementById('search-results');
    const resultsCount = document.getElementById('results-count');
    const noResults = document.getElementById('no-results');
    const filterPills = document.querySelectorAll('.filter-pill');
    const sidebarNav = document.querySelectorAll('#sidebar-nav .list-group-item');

    let currentFilter = 'all';
    let searchTerm = '';

    // Keyboard Shortcut
    document.addEventListener('keydown', function(e) {
        if (e.key === '/' && !e.ctrlKey && !e.metaKey && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
            e.preventDefault();
            searchInput.focus();
            searchInput.select();
        }
        
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            searchInput.value = '';
            performSearch();
            searchInput.blur();
        }
    });

    // Filter Pills
    filterPills.forEach(pill => {
        pill.addEventListener('click', function() {
            filterPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            performSearch();
        });
    });

    // Search Input
    searchInput.addEventListener('input', function() {
        searchTerm = this.value.toLowerCase().trim();
        searchClear.classList.toggle('visible', searchTerm.length > 0);
        performSearch();
    });

    // Clear Search
    searchClear.addEventListener('click', function() {
        searchInput.value = '';
        searchTerm = '';
        this.classList.remove('visible');
        performSearch();
        searchInput.focus();
    });

    // Enhanced Search
    function performSearch() {
        const term = searchTerm;
        const filter = currentFilter;
        let visibleCount = 0;

        if (term === '' && filter === 'all') {
            $('.endpoint-card, .docs-group').show();
            searchStats.classList.remove('visible');
            noResults.hide();
            updateActiveSidebar();
            return;
        }

        $('.endpoint-card').each(function() {
            const $card = $(this);
            const name = $card.data('endpoint-name') || '';
            const method = $card.data('endpoint-method') || '';
            const url = $card.data('endpoint-url') || '';
            const description = $card.data('endpoint-description') || '';

            const matchesSearch = term === '' || 
                name.includes(term) || 
                method.includes(term) || 
                url.includes(term) || 
                description.includes(term);

            const matchesFilter = filter === 'all' || method === filter;

            if (matchesSearch && matchesFilter) {
                $card.show();
                $card.closest('.docs-group').show();
                visibleCount++;
            } else {
                $card.hide();
            }
        });

        $('.docs-group').each(function() {
            const $group = $(this);
            const visibleInGroup = $group.find('.endpoint-card:visible').length;
            if (visibleInGroup === 0 && (term !== '' || filter !== 'all')) {
                $group.hide();
            }
        });

        resultsCount.textContent = visibleCount;
        searchStats.classList.toggle('visible', term !== '' || filter !== 'all');

        if (visibleCount === 0) {
            noResults.show();
        } else {
            noResults.hide();
            updateActiveSidebar();
        }
    }

    // Update Active Sidebar
    function updateActiveSidebar() {
        const groups = document.querySelectorAll('.docs-group');
        
        let activeGroup = null;
        groups.forEach((group, index) => {
            const rect = group.getBoundingClientRect();
            if (rect.top <= 150 && rect.bottom >= 150 && $(group).is(':visible')) {
                activeGroup = index;
            }
        });

        sidebarNav.forEach((item, index) => {
            item.classList.toggle('active', index === activeGroup);
        });
    }

    // Sidebar Navigation
    sidebarNav.forEach(item => {
        const link = item.querySelector('a');
        if (link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    sidebarNav.forEach(i => i.classList.remove('active'));
                    item.classList.add('active');
                    targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        }
    });

    // Scroll Spy
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(updateActiveSidebar, 100);
    });

    // Enhanced Copy Function with SweetAlert2
    window.copyCode = function(elementId, button) {
        const element = document.getElementById(elementId);
        if (!element) return;

        const text = element.textContent || element.innerText;
        
        navigator.clipboard.writeText(text).then(function() {
            const $btn = $(button);
            const originalHtml = $btn.html();
            $btn.html('<i class="ri-check-line"></i><span>Copied!</span>');
            $btn.addClass('copied');
            
            setTimeout(function() {
                $btn.html(originalHtml);
                $btn.removeClass('copied');
            }, 2000);

            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Copied to clipboard!',
                    text: 'Code has been copied successfully.'
                });
            }
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
            
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                Toast.fire({
                    icon: 'error',
                    title: 'Failed to copy!',
                    text: 'Please try again or copy manually.'
                });
            }
        });
    };

    // Initialize
    updateActiveSidebar();
    filterPills[0].classList.add('active');

})(jQuery);
</script>
@endpush
