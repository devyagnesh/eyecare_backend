@extends('layouts.dashboard')

@section('title', 'API Documentation')

@push('styles')
<style>
    /* API Documentation Styles - Using Theme Variables */
    .api-docs-container {
        position: relative;
    }

    /* Search Section */
    .api-docs-search-wrapper {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: var(--custom-white, #ffffff);
        border-radius: 0.5rem;
        border: 1px solid var(--default-border, #e2e6f1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .docs-search-container {
        position: relative;
        margin-bottom: 1rem;
    }

    .docs-search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 3rem;
        border: 1px solid var(--default-border, #e2e6f1);
        border-radius: 0.5rem;
        background: var(--custom-white, #ffffff);
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
        font-size: 1.125rem;
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
        border-radius: 0.25rem;
    }

    .docs-search-clear:hover {
        background: var(--default-background, #f5f6f7);
        color: var(--default-text-color, #222f36);
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
        border-radius: 0.25rem;
        border: 1px solid var(--default-border, #e2e6f1);
        font-family: 'Monaco', 'Menlo', monospace;
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

    /* Endpoint Cards */
    .endpoint-card {
        background: var(--custom-white, #ffffff);
        border: 1px solid var(--default-border, #e2e6f1);
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
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
        border-radius: 0.375rem;
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
        border-radius: 0.375rem;
    }

    .endpoint-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--default-text-color, #222f36);
        margin: 0.75rem 0 0.5rem 0;
    }

    .endpoint-description {
        font-size: 0.9375rem;
        color: var(--text-muted, #98a5c3);
        line-height: 1.6;
    }

    /* Sections */
    .docs-section {
        margin: 1.5rem 0;
    }

    .docs-section-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--default-text-color, #222f36);
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--default-border, #e2e6f1);
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
        background: var(--custom-white, #ffffff);
        border-radius: 0.5rem;
        overflow: hidden;
        border: 1px solid var(--default-border, #e2e6f1);
    }

    .docs-table th {
        background: var(--default-background, #f9fafb);
        padding: 1rem 1.25rem;
        text-align: left;
        font-weight: 600;
        color: var(--default-text-color, #222f36);
        border-bottom: 2px solid var(--default-border, #e2e6f1);
        font-size: 0.875rem;
    }

    .docs-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--default-border, #e2e6f1);
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
        border-radius: 0.25rem;
    }

    .type-badge {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        color: #10b981;
        background: rgba(16, 185, 129, 0.1);
        padding: 0.25rem 0.625rem;
        border-radius: 0.25rem;
        font-size: 0.8125rem;
        font-weight: 600;
    }

    .required-tag {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        padding: 0.25rem 0.625rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .optional-tag {
        background: var(--default-background, #f9fafb);
        color: var(--text-muted, #98a5c3);
        padding: 0.25rem 0.625rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Code Blocks */
    .code-block-wrapper {
        position: relative;
        margin: 1.5rem 0;
        border-radius: 0.5rem;
        overflow: hidden;
        border: 1px solid var(--default-border, #e2e6f1);
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
        border-radius: 0.375rem;
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
        border-radius: 0.5rem;
        border-left: 4px solid;
    }

    .status-code-badge {
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
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
        border-radius: 0.5rem;
        margin: 1.5rem 0;
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

    .callout-success {
        background: rgba(16, 185, 129, 0.1);
        border-left-color: #10b981;
    }

    .callout-danger {
        background: rgba(239, 68, 68, 0.1);
        border-left-color: #ef4444;
    }

    .callout p {
        margin: 0;
        font-size: 0.9375rem;
        line-height: 1.7;
        color: var(--default-text-color, #222f36);
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

    /* Changelog */
    .changelog-item {
        padding: 1.5rem;
        border-left: 4px solid rgb(var(--primary-rgb, 115, 93, 255));
        background: var(--custom-white, #ffffff);
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--default-border, #e2e6f1);
    }

    .changelog-version {
        font-size: 1.25rem;
        font-weight: 700;
        color: rgb(var(--primary-rgb, 115, 93, 255));
        margin-bottom: 0.5rem;
    }

    .changelog-date {
        font-size: 0.875rem;
        color: var(--text-muted, #98a5c3);
        margin-bottom: 1rem;
    }

    .changelog-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .changelog-list li {
        padding: 0.5rem 0;
        padding-left: 1.5rem;
        position: relative;
        color: var(--default-text-color, #222f36);
    }

    .changelog-list li:before {
        content: "•";
        position: absolute;
        left: 0;
        color: rgb(var(--primary-rgb, 115, 93, 255));
        font-weight: 700;
    }

    /* Filter Pills */
    .docs-filters {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .filter-pill {
        padding: 0.5rem 1rem;
        background: var(--custom-white, #ffffff);
        border: 1px solid var(--default-border, #e2e6f1);
        border-radius: 1.5rem;
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
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">API Documentation</div>
                </div>
                <div class="card-body">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs tab-style-1" id="api-docs-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">
                                <i class="ri-information-line me-1"></i> Overview
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="authentication-tab" data-bs-toggle="tab" data-bs-target="#authentication" type="button" role="tab" aria-controls="authentication" aria-selected="false">
                                <i class="ri-lock-line me-1"></i> Authentication
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="endpoints-tab" data-bs-toggle="tab" data-bs-target="#endpoints" type="button" role="tab" aria-controls="endpoints" aria-selected="false">
                                <i class="ri-code-s-slash-line me-1"></i> Endpoints
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="examples-tab" data-bs-toggle="tab" data-bs-target="#examples" type="button" role="tab" aria-controls="examples" aria-selected="false">
                                <i class="ri-file-code-line me-1"></i> Request/Response Examples
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="errors-tab" data-bs-toggle="tab" data-bs-target="#errors" type="button" role="tab" aria-controls="errors" aria-selected="false">
                                <i class="ri-error-warning-line me-1"></i> Errors
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="changelog-tab" data-bs-toggle="tab" data-bs-target="#changelog" type="button" role="tab" aria-controls="changelog" aria-selected="false">
                                <i class="ri-history-line me-1"></i> Changelog
                            </button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content" id="api-docs-tab-content">
                        <!-- Overview Tab -->
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                            <div class="p-4">
                                <div class="mb-4">
                                    <h2 class="mb-3">Welcome to Eyecare API Documentation</h2>
                                    <p class="text-muted mb-4">
                                        This comprehensive API documentation provides everything you need to integrate with the Eyecare RESTful API. 
                                        Our API follows RESTful principles and uses JSON for request and response payloads.
                                    </p>
                                </div>

                                <div class="callout callout-info mb-4">
                                    <p><strong>Base URL:</strong> <code style="background: rgba(var(--primary-rgb, 115, 93, 255), 0.2); padding: 4px 8px; border-radius: 4px; font-family: monospace; font-weight: 600;">{{ $baseUrl }}</code></p>
                                    <p><strong>API Version:</strong> <code style="background: rgba(var(--primary-rgb, 115, 93, 255), 0.2); padding: 4px 8px; border-radius: 4px; font-family: monospace; font-weight: 600;">{{ $apiVersion }}</code></p>
                                    <p><strong>Last Updated:</strong> {{ $lastUpdated }}</p>
                                </div>

                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">
                                                    <i class="ri-checkbox-circle-line text-success me-2"></i>
                                                    Features
                                                </h5>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="mb-2"><i class="ri-check-line text-primary me-2"></i> RESTful API design</li>
                                                    <li class="mb-2"><i class="ri-check-line text-primary me-2"></i> JSON request/response format</li>
                                                    <li class="mb-2"><i class="ri-check-line text-primary me-2"></i> Bearer token authentication</li>
                                                    <li class="mb-2"><i class="ri-check-line text-primary me-2"></i> Comprehensive error handling</li>
                                                    <li class="mb-2"><i class="ri-check-line text-primary me-2"></i> Rate limiting protection</li>
                                                    <li class="mb-0"><i class="ri-check-line text-primary me-2"></i> Pagination support</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">
                                                    <i class="ri-file-list-3-line text-primary me-2"></i>
                                                    Quick Start
                                                </h5>
                                                <ol class="mb-0">
                                                    <li class="mb-2">Obtain your API token via authentication</li>
                                                    <li class="mb-2">Include the token in Authorization header</li>
                                                    <li class="mb-2">Make requests to the appropriate endpoints</li>
                                                    <li class="mb-2">Handle responses and errors appropriately</li>
                                                    <li class="mb-0">Review the examples in this documentation</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="callout callout-warning">
                                    <p><strong>Important:</strong> All API requests must include proper authentication headers. Most endpoints require a valid Bearer token obtained through the authentication endpoint.</p>
                                </div>

                                <h3 class="mb-3 mt-5">API Modules</h3>
                                <div class="row g-3">
                                    @foreach($endpoints as $group)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-title mb-2">
                                                    <i class="ri-{{ $group['icon'] }}-line text-primary me-2"></i>
                                                    {{ $group['group'] }}
                                                </h6>
                                                <p class="card-text text-muted small mb-0">{{ Str::limit($group['description'], 80) }}</p>
                                                <small class="text-muted">{{ count($group['endpoints']) }} endpoint(s)</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Authentication Tab -->
                        <div class="tab-pane fade" id="authentication" role="tabpanel" aria-labelledby="authentication-tab">
                            <div class="p-4">
                                <h2 class="mb-4">Authentication</h2>
                                
                                <p class="text-muted mb-4">
                                    The Eyecare API uses Bearer token authentication. You must obtain an access token by authenticating with valid credentials, 
                                    then include this token in the Authorization header of all subsequent requests.
                                </p>

                                <div class="callout callout-info mb-4">
                                    <p><strong>Authentication Flow:</strong></p>
                                    <ol class="mb-0">
                                        <li>Send a POST request to <code>/api/auth/login</code> with your credentials</li>
                                        <li>Receive an access token in the response</li>
                                        <li>Include the token in the <code>Authorization: Bearer YOUR_TOKEN</code> header for all protected endpoints</li>
                                    </ol>
                                </div>

                                @php
                                    $authEndpoints = collect($endpoints)->firstWhere('group', 'Authentication');
                                @endphp

                                @if($authEndpoints)
                                    @foreach($authEndpoints['endpoints'] as $endpoint)
                                        @if(str_contains(strtolower($endpoint['name']), 'login') || str_contains(strtolower($endpoint['name']), 'register'))
                                            <div class="endpoint-card">
                                                <div class="endpoint-header">
                                                    <div class="endpoint-method-url">
                                                        <span class="method-badge method-{{ strtolower($endpoint['method']) }}">
                                                            {{ $endpoint['method'] }}
                                                        </span>
                                                        <code class="endpoint-url">{{ $endpoint['url'] }}</code>
                                                    </div>
                                                    <h3 class="endpoint-title">{{ $endpoint['name'] }}</h3>
                                                    <p class="endpoint-description">{{ $endpoint['description'] ?: 'No description available.' }}</p>
                                                </div>

                                                @if(!empty($endpoint['headers']))
                                                <div class="docs-section">
                                                    <h4 class="docs-section-title">
                                                        <i class="ri-file-list-3-line"></i>
                                                        Headers
                                                    </h4>
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

                                                @if($endpoint['payload_example'])
                                                <div class="docs-section">
                                                    <h4 class="docs-section-title">
                                                        <i class="ri-code-s-slash-line"></i>
                                                        Request Payload
                                                    </h4>
                                                    <div class="code-block-wrapper">
                                                        <div class="code-block-header">
                                                            <span class="code-block-label">JSON</span>
                                                            <button class="code-copy-btn" onclick="copyCode('auth-payload-{{ $loop->index }}', this)">
                                                                <i class="ri-file-copy-line"></i>
                                                                <span>Copy</span>
                                                            </button>
                                                        </div>
                                                        <div class="code-block-content">
                                                            <pre id="auth-payload-{{ $loop->index }}"><code class="json">{{ is_string($endpoint['payload_example']) ? $endpoint['payload_example'] : json_encode($endpoint['payload_example'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if($endpoint['success_response'])
                                                <div class="docs-section">
                                                    <h4 class="docs-section-title" style="color: #10b981;">
                                                        <i class="ri-checkbox-circle-line"></i>
                                                        Success Response
                                                    </h4>
                                                    <div class="code-block-wrapper">
                                                        <div class="code-block-header">
                                                            <span class="code-block-label">JSON</span>
                                                            <button class="code-copy-btn" onclick="copyCode('auth-success-{{ $loop->index }}', this)">
                                                                <i class="ri-file-copy-line"></i>
                                                                <span>Copy</span>
                                                            </button>
                                                        </div>
                                                        <div class="code-block-content">
                                                            <pre id="auth-success-{{ $loop->index }}"><code class="json">{{ is_string($endpoint['success_response']) ? $endpoint['success_response'] : json_encode($endpoint['success_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                @endif

                                <div class="callout callout-success mt-4">
                                    <p><strong>Token Usage:</strong> Once you receive your access token, include it in every API request using the format: <code>Authorization: Bearer YOUR_TOKEN_HERE</code></p>
                                </div>
                            </div>
                        </div>

                        <!-- Endpoints Tab -->
                        <div class="tab-pane fade" id="endpoints" role="tabpanel" aria-labelledby="endpoints-tab">
                            <div class="p-4">
                                <h2 class="mb-4">API Endpoints</h2>
                                <p class="text-muted mb-4">Complete list of all available API endpoints, organized by module.</p>

                                @foreach($endpoints as $groupIndex => $group)
                                <div class="docs-group mb-5" id="endpoint-group-{{ $groupIndex }}" data-group-name="{{ strtolower($group['group']) }}">
                                    <div class="mb-4 pb-3 border-bottom">
                                        <h3 class="mb-2">
                                            <i class="ri-{{ $group['icon'] }}-line text-primary me-2"></i>
                                            {{ $group['group'] }}
                                        </h3>
                                        <p class="text-muted mb-0">{{ $group['description'] }}</p>
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
                                                <button class="btn btn-sm btn-outline-primary ms-auto" onclick="copyCode('url-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                                    <i class="ri-file-copy-line"></i> Copy URL
                                                </button>
                                                <span id="url-{{ $groupIndex }}-{{ $endpointIndex }}" style="display: none;">{{ $endpoint['url'] }}</span>
                                            </div>
                                            <h4 class="endpoint-title">{{ $endpoint['name'] }}</h4>
                                            <p class="endpoint-description">{{ $endpoint['description'] ?: 'No description available.' }}</p>
                                        </div>

                                        @if(!empty($endpoint['headers']))
                                        <div class="docs-section">
                                            <h5 class="docs-section-title">
                                                <i class="ri-file-list-3-line"></i>
                                                Headers
                                            </h5>
                                            <div class="mb-3">
                                                <button class="btn btn-sm btn-outline-primary" onclick="copyHeaders('headers-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                                    <i class="ri-file-copy-line"></i> Copy Headers
                                                </button>
                                            </div>
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
                                            <div id="headers-{{ $groupIndex }}-{{ $endpointIndex }}" style="display: none;">
                                                @foreach($endpoint['headers'] as $headerName => $headerData)
{{ $headerName }}: {{ $headerData['example'] ?? '' }}
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif

                                        @if(!empty($endpoint['query_parameters']))
                                        <div class="docs-section">
                                            <h5 class="docs-section-title">
                                                <i class="ri-search-line"></i>
                                                Query Parameters
                                            </h5>
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

                                        @if(!empty($endpoint['parameters']))
                                        <div class="docs-section">
                                            <h5 class="docs-section-title">
                                                <i class="ri-file-edit-line"></i>
                                                Request Parameters
                                            </h5>
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

                                        @if(!empty($endpoint['status_codes']))
                                        <div class="docs-section">
                                            <h5 class="docs-section-title">
                                                <i class="ri-information-line"></i>
                                                Status Codes
                                            </h5>
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
                                    </article>
                                    @endforeach
                                </div>
                                @endforeach

                                <!-- No Results -->
                                <div id="no-results-endpoints" class="no-results" style="display: none;">
                                    <div class="no-results-icon">
                                        <i class="ri-search-line"></i>
                                    </div>
                                    <h3>No endpoints found</h3>
                                    <p>Try adjusting your search criteria or filters</p>
                                </div>
                            </div>
                        </div>

                        <!-- Request/Response Examples Tab -->
                        <div class="tab-pane fade" id="examples" role="tabpanel" aria-labelledby="examples-tab">
                            <div class="p-4">
                                <h2 class="mb-4">Request/Response Examples</h2>
                                <p class="text-muted mb-4">Real-world examples of API requests and responses for all endpoints.</p>

                                @foreach($endpoints as $groupIndex => $group)
                                    @foreach($group['endpoints'] as $endpointIndex => $endpoint)
                                        @if($endpoint['payload_example'] || $endpoint['success_response'] || $endpoint['error_response'])
                                        <div class="endpoint-card" 
                                             data-endpoint-name="{{ strtolower($endpoint['name']) }}"
                                             data-endpoint-method="{{ strtolower($endpoint['method']) }}">
                                            
                                            <div class="endpoint-header">
                                                <div class="endpoint-method-url">
                                                    <span class="method-badge method-{{ strtolower($endpoint['method']) }}">
                                                        {{ $endpoint['method'] }}
                                                    </span>
                                                    <code class="endpoint-url">{{ $endpoint['url'] }}</code>
                                                </div>
                                                <h4 class="endpoint-title">{{ $endpoint['name'] }}</h4>
                                            </div>

                                            @if($endpoint['payload_example'])
                                            <div class="docs-section">
                                                <h5 class="docs-section-title">
                                                    <i class="ri-code-s-slash-line"></i>
                                                    Request Payload Example
                                                </h5>
                                                <div class="code-block-wrapper">
                                                    <div class="code-block-header">
                                                        <span class="code-block-label">JSON</span>
                                                        <button class="code-copy-btn" onclick="copyCode('example-payload-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                                            <i class="ri-file-copy-line"></i>
                                                            <span>Copy</span>
                                                        </button>
                                                    </div>
                                                    <div class="code-block-content">
                                                        <pre id="example-payload-{{ $groupIndex }}-{{ $endpointIndex }}"><code class="json">{{ is_string($endpoint['payload_example']) ? $endpoint['payload_example'] : json_encode($endpoint['payload_example'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if($endpoint['success_response'])
                                            <div class="docs-section">
                                                <h5 class="docs-section-title" style="color: #10b981;">
                                                    <i class="ri-checkbox-circle-line"></i>
                                                    Success Response Example
                                                </h5>
                                                <div class="code-block-wrapper">
                                                    <div class="code-block-header">
                                                        <span class="code-block-label">JSON</span>
                                                        <button class="code-copy-btn" onclick="copyCode('example-success-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                                            <i class="ri-file-copy-line"></i>
                                                            <span>Copy</span>
                                                        </button>
                                                    </div>
                                                    <div class="code-block-content">
                                                        <pre id="example-success-{{ $groupIndex }}-{{ $endpointIndex }}"><code class="json">{{ is_string($endpoint['success_response']) ? $endpoint['success_response'] : json_encode($endpoint['success_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if($endpoint['error_response'])
                                            <div class="docs-section">
                                                <h5 class="docs-section-title" style="color: #ef4444;">
                                                    <i class="ri-error-warning-line"></i>
                                                    Error Response Example
                                                </h5>
                                                <div class="code-block-wrapper">
                                                    <div class="code-block-header">
                                                        <span class="code-block-label">JSON</span>
                                                        <button class="code-copy-btn" onclick="copyCode('example-error-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                                            <i class="ri-file-copy-line"></i>
                                                            <span>Copy</span>
                                                        </button>
                                                    </div>
                                                    <div class="code-block-content">
                                                        <pre id="example-error-{{ $groupIndex }}-{{ $endpointIndex }}"><code class="json">{{ is_string($endpoint['error_response']) ? $endpoint['error_response'] : json_encode($endpoint['error_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    @endforeach
                                @endforeach

                                <!-- No Results -->
                                <div id="no-results-examples" class="no-results" style="display: none;">
                                    <div class="no-results-icon">
                                        <i class="ri-search-line"></i>
                                    </div>
                                    <h3>No examples found</h3>
                                    <p>Try adjusting your search criteria</p>
                                </div>
                            </div>
                        </div>

                        <!-- Errors Tab -->
                        <div class="tab-pane fade" id="errors" role="tabpanel" aria-labelledby="errors-tab">
                            <div class="p-4">
                                <h2 class="mb-4">Error Handling</h2>
                                <p class="text-muted mb-4">
                                    The API uses standard HTTP status codes and returns detailed error messages in JSON format to help you understand and resolve issues.
                                </p>

                                <div class="callout callout-info mb-4">
                                    <p><strong>Error Response Format:</strong> All error responses follow a consistent JSON structure with <code>success: false</code>, an error message, and optional error details.</p>
                                </div>

                                <h3 class="mb-3 mt-4">HTTP Status Codes</h3>
                                <div class="status-list mb-4">
                                    <div class="status-item status-2xx">
                                        <span class="status-code-badge">200</span>
                                        <span>OK - Request successful</span>
                                    </div>
                                    <div class="status-item status-2xx">
                                        <span class="status-code-badge">201</span>
                                        <span>Created - Resource successfully created</span>
                                    </div>
                                    <div class="status-item status-4xx">
                                        <span class="status-code-badge">400</span>
                                        <span>Bad Request - Invalid input or validation error</span>
                                    </div>
                                    <div class="status-item status-4xx">
                                        <span class="status-code-badge">401</span>
                                        <span>Unauthorized - Authentication required or invalid token</span>
                                    </div>
                                    <div class="status-item status-4xx">
                                        <span class="status-code-badge">403</span>
                                        <span>Forbidden - Insufficient permissions</span>
                                    </div>
                                    <div class="status-item status-4xx">
                                        <span class="status-code-badge">404</span>
                                        <span>Not Found - Resource not found</span>
                                    </div>
                                    <div class="status-item status-4xx">
                                        <span class="status-code-badge">422</span>
                                        <span>Unprocessable Entity - Validation failed</span>
                                    </div>
                                    <div class="status-item status-5xx">
                                        <span class="status-code-badge">500</span>
                                        <span>Internal Server Error - Server error occurred</span>
                                    </div>
                                </div>

                                <h3 class="mb-3 mt-5">Error Response Examples</h3>

                                <div class="endpoint-card mb-4">
                                    <h4 class="mb-3">Validation Error (400/422)</h4>
                                    <div class="code-block-wrapper">
                                        <div class="code-block-header">
                                            <span class="code-block-label">JSON</span>
                                            <button class="code-copy-btn" onclick="copyCode('error-validation', this)">
                                                <i class="ri-file-copy-line"></i>
                                                <span>Copy</span>
                                            </button>
                                        </div>
                                        <div class="code-block-content">
                                            <pre id="error-validation"><code class="json">{
  "success": false,
  "message": "The provided data is invalid.",
  "errors": {
    "email": [
      "The email field is required.",
      "The email must be a valid email address."
    ],
    "password": [
      "The password field is required.",
      "The password must be at least 8 characters."
    ]
  },
  "timestamp": "2025-01-15T10:30:00.000000Z"
}</code></pre>
                                        </div>
                                    </div>
                                </div>

                                <div class="endpoint-card mb-4">
                                    <h4 class="mb-3">Unauthorized Error (401)</h4>
                                    <div class="code-block-wrapper">
                                        <div class="code-block-header">
                                            <span class="code-block-label">JSON</span>
                                            <button class="code-copy-btn" onclick="copyCode('error-unauthorized', this)">
                                                <i class="ri-file-copy-line"></i>
                                                <span>Copy</span>
                                            </button>
                                        </div>
                                        <div class="code-block-content">
                                            <pre id="error-unauthorized"><code class="json">{
  "success": false,
  "message": "Unauthenticated.",
  "error_code": "UNAUTHORIZED",
  "timestamp": "2025-01-15T10:30:00.000000Z"
}</code></pre>
                                        </div>
                                    </div>
                                </div>

                                <div class="endpoint-card mb-4">
                                    <h4 class="mb-3">Not Found Error (404)</h4>
                                    <div class="code-block-wrapper">
                                        <div class="code-block-header">
                                            <span class="code-block-label">JSON</span>
                                            <button class="code-copy-btn" onclick="copyCode('error-notfound', this)">
                                                <i class="ri-file-copy-line"></i>
                                                <span>Copy</span>
                                            </button>
                                        </div>
                                        <div class="code-block-content">
                                            <pre id="error-notfound"><code class="json">{
  "success": false,
  "message": "Resource not found.",
  "error_code": "NOT_FOUND",
  "timestamp": "2025-01-15T10:30:00.000000Z"
}</code></pre>
                                        </div>
                                    </div>
                                </div>

                                <div class="endpoint-card mb-4">
                                    <h4 class="mb-3">Server Error (500)</h4>
                                    <div class="code-block-wrapper">
                                        <div class="code-block-header">
                                            <span class="code-block-label">JSON</span>
                                            <button class="code-copy-btn" onclick="copyCode('error-server', this)">
                                                <i class="ri-file-copy-line"></i>
                                                <span>Copy</span>
                                            </button>
                                        </div>
                                        <div class="code-block-content">
                                            <pre id="error-server"><code class="json">{
  "success": false,
  "message": "An error occurred while processing your request. Please try again later.",
  "error_code": "INTERNAL_SERVER_ERROR",
  "timestamp": "2025-01-15T10:30:00.000000Z"
}</code></pre>
                                        </div>
                                    </div>
                                </div>

                                <div class="callout callout-warning mt-4">
                                    <p><strong>Best Practices:</strong></p>
                                    <ul class="mb-0">
                                        <li>Always check the <code>success</code> field in responses</li>
                                        <li>Handle validation errors by displaying field-specific messages</li>
                                        <li>Implement retry logic for 5xx errors</li>
                                        <li>Log error responses for debugging purposes</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Changelog Tab -->
                        <div class="tab-pane fade" id="changelog" role="tabpanel" aria-labelledby="changelog-tab">
                            <div class="p-4">
                                <h2 class="mb-4">API Changelog</h2>
                                <p class="text-muted mb-4">Track changes, updates, and new features in the API.</p>

                                <div class="changelog-item">
                                    <div class="changelog-version">Version {{ $apiVersion }} - {{ date('F Y') }}</div>
                                    <div class="changelog-date">{{ $lastUpdated }}</div>
                                    <ul class="changelog-list">
                                        <li>Initial API documentation release</li>
                                        <li>Complete authentication endpoints with Bearer token support</li>
                                        <li>Store management endpoints for creating and updating store information</li>
                                        <li>Customer CRUD operations with filtering and pagination</li>
                                        <li>Eye examination management with comprehensive data fields</li>
                                        <li>Order management with file upload support and invoice generation</li>
                                        <li>Settings management for system configuration</li>
                                        <li>Comprehensive error handling with detailed error messages</li>
                                        <li>Rate limiting and security features</li>
                                    </ul>
                                </div>

                                <div class="changelog-item">
                                    <div class="changelog-version">Version 1.0.0 - January 2025</div>
                                    <div class="changelog-date">January 15, 2025</div>
                                    <ul class="changelog-list">
                                        <li>Initial API release</li>
                                        <li>RESTful API design implementation</li>
                                        <li>Bearer token authentication</li>
                                        <li>JSON request/response format</li>
                                        <li>Comprehensive documentation</li>
                                    </ul>
                                </div>

                                <div class="callout callout-info mt-4">
                                    <p><strong>Note:</strong> This changelog is updated with each API release. Check back regularly for the latest updates and breaking changes.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(file_exists(public_path('assets/js/api-documentation.js')))
<script src="{{ asset('assets/js/api-documentation.js') }}?v={{ filemtime(public_path('assets/js/api-documentation.js')) }}"></script>
@endif
@endpush
