@extends('layouts.dashboard')

@section('title', 'API Documentation')

@push('styles')
<style>
    /* Professional Modern API Documentation Styles */
    :root {
        --primary: #087ea4;
        --primary-hover: #0a5d7a;
        --bg: #ffffff;
        --sidebar-bg: #f8fafc;
        --text: #1a202c;
        --text-muted: #64748b;
        --border: #e2e8f0;
        --code-bg: #1e293b;
        --code-text: #e2e8f0;
        --success: #10b981;
        --error: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    [data-theme="dark"] {
        --bg: #0f172a;
        --sidebar-bg: #1e293b;
        --text: #f1f5f9;
        --text-muted: #94a3b8;
        --border: #334155;
        --code-bg: #020617;
        --code-text: #cbd5e1;
    }

    .api-docs-container {
        background: var(--bg);
        min-height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
        transition: background 0.2s ease;
    }

    /* Enhanced Top Bar */
    .docs-top-bar {
        position: sticky;
        top: 0;
        z-index: 200;
        background: var(--bg);
        border-bottom: 1px solid var(--border);
        padding: 1rem 2rem;
        box-shadow: var(--shadow-sm);
    }

    .docs-top-bar-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        max-width: 1600px;
        margin: 0 auto;
    }

    .docs-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
        text-decoration: none;
    }

    .docs-brand i {
        font-size: 1.5rem;
    }

    /* Advanced Search */
    .docs-search-container {
        flex: 1;
        max-width: 600px;
        position: relative;
    }

    .docs-search-wrapper {
        position: relative;
    }

    .docs-search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 3rem;
        border: 2px solid var(--border);
        border-radius: 8px;
        background: var(--sidebar-bg);
        color: var(--text);
        font-size: 0.9375rem;
        transition: all 0.2s;
    }

    .docs-search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(8, 126, 164, 0.1);
        background: var(--bg);
    }

    .docs-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 1.125rem;
    }

    .docs-search-clear {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: 0.25rem;
        display: none;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .docs-search-clear:hover {
        background: var(--border);
        color: var(--text);
    }

    .docs-search-clear.visible {
        display: flex;
    }

    /* Search Filters */
    .docs-search-filters {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
        flex-wrap: wrap;
    }

    .filter-chip {
        padding: 0.375rem 0.75rem;
        background: var(--sidebar-bg);
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 0.8125rem;
        color: var(--text);
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-chip:hover {
        background: var(--border);
    }

    .filter-chip.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .filter-chip i {
        font-size: 0.75rem;
    }

    /* Search Results Count */
    .docs-search-results {
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: var(--text-muted);
        display: none;
    }

    .docs-search-results.visible {
        display: block;
    }

    /* Main Layout */
    .docs-main-layout {
        display: flex;
        flex: 1;
        max-width: 1600px;
        margin: 0 auto;
        width: 100%;
    }

    /* Sidebar */
    .docs-sidebar {
        width: 280px;
        background: var(--sidebar-bg);
        border-right: 1px solid var(--border);
        padding: 1.5rem 0;
        position: sticky;
        top: 73px;
        height: calc(100vh - 273px);
        overflow-y: auto;
        transition: all 0.2s;
    }

    .sidebar-section {
        margin-bottom: 2rem;
    }

    .sidebar-section-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        padding: 0 1.5rem;
        margin-bottom: 0.75rem;
    }

    .sidebar-nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-nav-item {
        margin: 0.125rem 0;
    }

    .sidebar-nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.625rem 1.5rem;
        color: var(--text);
        text-decoration: none;
        font-size: 0.9375rem;
        transition: all 0.15s;
        border-left: 3px solid transparent;
        position: relative;
    }

    .sidebar-nav-link:hover {
        background: rgba(8, 126, 164, 0.08);
        color: var(--primary);
    }

    .sidebar-nav-link.active {
        background: rgba(8, 126, 164, 0.12);
        color: var(--primary);
        border-left-color: var(--primary);
        font-weight: 600;
    }

    .sidebar-nav-link i {
        font-size: 1rem;
        width: 18px;
        text-align: center;
    }

    /* Content Area */
    .docs-content {
        flex: 1;
        padding: 2rem 3rem;
        max-width: 900px;
    }

    /* Group Header */
    .docs-group {
        margin-bottom: 4rem;
        scroll-margin-top: 100px;
    }

    .docs-group-header {
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid var(--border);
    }

    .docs-group-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text);
        margin: 0 0 0.75rem 0;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .docs-group-description {
        font-size: 1.125rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.7;
    }

    /* Endpoint Card */
    .endpoint-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2.5rem;
        transition: all 0.2s;
        scroll-margin-top: 100px;
    }

    .endpoint-card:hover {
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
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
        box-shadow: var(--shadow-sm);
    }

    .method-get { background: var(--success); color: white; }
    .method-post { background: var(--info); color: white; }
    .method-put { background: var(--warning); color: white; }
    .method-delete { background: var(--error); color: white; }
    .method-patch { background: #8b5cf6; color: white; }

    .endpoint-url {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Courier New', monospace;
        font-size: 0.9375rem;
        color: var(--primary);
        font-weight: 500;
        background: rgba(8, 126, 164, 0.1);
        padding: 0.5rem 0.875rem;
        border-radius: 6px;
        border: 1px solid rgba(8, 126, 164, 0.2);
    }

    .endpoint-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text);
        margin: 0.75rem 0 0.5rem 0;
        line-height: 1.3;
    }

    .endpoint-description {
        font-size: 1rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.7;
    }

    /* Section Headers */
    .docs-section {
        margin: 2.5rem 0;
    }

    .docs-section-title {
        font-size: 1.375rem;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 1.25rem 0;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .docs-section-title i {
        color: var(--primary);
        font-size: 1.25rem;
    }

    /* Tables */
    .docs-table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        font-size: 0.9375rem;
        background: var(--bg);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .docs-table th {
        background: var(--sidebar-bg);
        padding: 1rem 1.25rem;
        text-align: left;
        font-weight: 600;
        color: var(--text);
        border-bottom: 2px solid var(--border);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .docs-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        color: var(--text);
        vertical-align: top;
    }

    .docs-table tr:last-child td {
        border-bottom: none;
    }

    .docs-table tr:hover {
        background: var(--sidebar-bg);
    }

    .param-code {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Courier New', monospace;
        color: var(--primary);
        font-weight: 600;
        font-size: 0.875rem;
        background: rgba(8, 126, 164, 0.1);
        padding: 0.125rem 0.5rem;
        border-radius: 4px;
    }

    .type-badge {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Courier New', monospace;
        color: var(--success);
        background: rgba(16, 185, 129, 0.1);
        padding: 0.25rem 0.625rem;
        border-radius: 4px;
        font-size: 0.8125rem;
        font-weight: 500;
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
        background: rgba(107, 114, 128, 0.1);
        color: var(--text-muted);
        padding: 0.25rem 0.625rem;
        border-radius: 4px;
        font-size: 0.75rem;
    }

    /* Enhanced Code Blocks */
    .code-block-wrapper {
        position: relative;
        margin: 1.5rem 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
    }

    .code-block-header {
        background: var(--code-bg);
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .code-block-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--code-text);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        opacity: 0.7;
    }

    .code-copy-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--code-text);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.8125rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }

    .code-copy-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-1px);
    }

    .code-copy-btn.copied {
        background: var(--success);
        border-color: var(--success);
        color: white;
    }

    .code-copy-btn i {
        font-size: 1rem;
    }

    .code-block-content {
        background: var(--code-bg);
        padding: 1.5rem;
        overflow-x: auto;
    }

    .code-block-content pre {
        margin: 0;
        color: var(--code-text);
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Courier New', monospace;
        font-size: 0.875rem;
        line-height: 1.7;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .code-block-content code {
        color: var(--code-text);
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
        background: var(--sidebar-bg);
        border-radius: 8px;
        border-left: 4px solid;
        transition: all 0.2s;
    }

    .status-item:hover {
        box-shadow: var(--shadow-sm);
        transform: translateY(-2px);
    }

    .status-code-badge {
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        min-width: 65px;
        text-align: center;
        box-shadow: var(--shadow-sm);
    }

    .status-2xx { 
        background: rgba(16, 185, 129, 0.15); 
        color: var(--success);
        border-left-color: var(--success);
    }
    .status-2xx .status-code-badge {
        background: var(--success);
        color: white;
    }
    .status-4xx { 
        background: rgba(239, 68, 68, 0.15); 
        color: var(--error);
        border-left-color: var(--error);
    }
    .status-4xx .status-code-badge {
        background: var(--error);
        color: white;
    }
    .status-5xx { 
        background: rgba(245, 158, 11, 0.15); 
        color: var(--warning);
        border-left-color: var(--warning);
    }
    .status-5xx .status-code-badge {
        background: var(--warning);
        color: white;
    }

    /* Callout Boxes */
    .callout {
        padding: 1.25rem 1.5rem;
        border-radius: 8px;
        margin: 2rem 0;
        border-left: 4px solid;
        box-shadow: var(--shadow-sm);
    }

    .callout-info {
        background: rgba(8, 126, 164, 0.1);
        border-left-color: var(--primary);
        color: var(--text);
    }

    .callout-warning {
        background: rgba(245, 158, 11, 0.1);
        border-left-color: var(--warning);
        color: var(--text);
    }

    .callout p {
        margin: 0;
        font-size: 0.9375rem;
        line-height: 1.7;
    }

    .callout strong {
        color: var(--text);
    }


    /* No Results */
    .no-results {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-muted);
    }

    .no-results i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
        color: var(--text-muted);
    }

    .no-results h3 {
        font-size: 1.5rem;
        margin: 0 0 0.5rem 0;
        color: var(--text);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .docs-main-layout {
            flex-direction: column;
        }

        .docs-sidebar {
            width: 100%;
            height: auto;
            position: relative;
            top: 0;
            border-right: none;
            border-bottom: 1px solid var(--border);
        }

        .docs-content {
            padding: 2rem 1.5rem;
        }

        .docs-top-bar-content {
            flex-direction: column;
            gap: 1rem;
        }

        .docs-search-container {
            max-width: 100%;
        }
    }

    /* Keyboard Shortcut Hint */
    .keyboard-hint {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.75rem;
        color: var(--text-muted);
        background: var(--sidebar-bg);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        border: 1px solid var(--border);
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
        <button class="btn btn-primary-light btn-wave" id="theme-toggle">
            <i class="ri-moon-line" id="theme-icon"></i>
            <span id="theme-text">Dark Mode</span>
        </button>
        <a href="{{ route('admin.api-documentation.download') }}" class="btn btn-primary btn-wave">
            <i class="ri-download-line align-middle"></i> Download Postman Collection
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="api-docs-container" id="api-docs-container">
    <!-- Top Bar with Enhanced Search -->
    <div class="docs-top-bar">
        <div class="docs-top-bar-content">
            <a href="#getting-started" class="docs-brand">
                <i class="ri-code-s-slash-line"></i>
                <span>API Reference</span>
            </a>
            
            <div class="docs-search-container">
                <div class="docs-search-wrapper">
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
                
                <div class="docs-search-filters" id="search-filters">
                    <button class="filter-chip" data-filter="all">
                        <i class="ri-list-check"></i> All
                    </button>
                    <button class="filter-chip" data-filter="get">
                        <i class="ri-download-line"></i> GET
                    </button>
                    <button class="filter-chip" data-filter="post">
                        <i class="ri-upload-line"></i> POST
                    </button>
                    <button class="filter-chip" data-filter="put">
                        <i class="ri-edit-line"></i> PUT
                    </button>
                    <button class="filter-chip" data-filter="delete">
                        <i class="ri-delete-bin-line"></i> DELETE
                    </button>
                </div>
                
                <div class="docs-search-results" id="search-results">
                    <span id="results-count">0</span> endpoint(s) found
                </div>
            </div>
            
            <div style="font-size: 0.875rem; color: var(--text-muted); white-space: nowrap;">
                <strong>v{{ $apiVersion }}</strong> · {{ $lastUpdated }}
            </div>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="docs-main-layout">
        <!-- Sidebar -->
        <aside class="docs-sidebar">
            <div class="sidebar-section">
                <div class="sidebar-section-title">API Groups</div>
                <ul class="sidebar-nav" id="sidebar-nav">
                    @foreach($endpoints as $groupIndex => $group)
                    <li class="sidebar-nav-item">
                        <a href="#group-{{ $groupIndex }}" 
                           class="sidebar-nav-link {{ $groupIndex === 0 ? 'active' : '' }}"
                           data-group="group-{{ $groupIndex }}">
                            <i class="ri-{{ $group['icon'] }}-line"></i>
                            <span>{{ $group['group'] }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <!-- Content -->
        <main class="docs-content">
            <!-- Getting Started -->
            <div class="docs-group" id="getting-started">
                <div class="docs-group-header">
                    <h1 class="docs-group-title">Getting Started</h1>
                    <p class="docs-group-description">
                        Welcome to the Eyecare API documentation. This comprehensive guide will help you integrate with our RESTful API quickly and efficiently.
                    </p>
                </div>

                <div class="callout callout-info">
                    <p><strong>Base URL:</strong> <code style="background: rgba(8, 126, 164, 0.2); padding: 2px 6px; border-radius: 3px; font-family: monospace;">{{ $baseUrl }}</code></p>
                    <p style="margin-top: 0.75rem;"><strong>Authentication:</strong> Most endpoints require authentication. Include your Bearer token in the Authorization header.</p>
                    <p style="margin-top: 0.5rem;"><strong>Rate Limiting:</strong> API requests are rate-limited. Check response headers for rate limit information.</p>
                </div>
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
                                    <td><code style="font-size: 0.8125rem; background: var(--sidebar-bg); padding: 2px 6px; border-radius: 3px;">{{ $headerData['example'] ?? '' }}</code></td>
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
                        <h3 class="docs-section-title" style="color: var(--success);">
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
                        <h3 class="docs-section-title" style="color: var(--error);">
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
                <i class="ri-search-line"></i>
                <h3>No endpoints found</h3>
                <p>Try adjusting your search criteria or filters</p>
            </div>
        </main>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function($) {
    'use strict';

    const container = document.getElementById('api-docs-container');
    const searchInput = document.getElementById('api-search');
    const searchClear = document.getElementById('search-clear');
    const searchResults = document.getElementById('search-results');
    const resultsCount = document.getElementById('results-count');
    const noResults = document.getElementById('no-results');
    const filterChips = document.querySelectorAll('.filter-chip');
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const themeText = document.getElementById('theme-text');

    let currentFilter = 'all';
    let searchTerm = '';

    // Theme Toggle
    const savedTheme = localStorage.getItem('api-docs-theme') || 'light';
    if (savedTheme === 'dark') {
        container.setAttribute('data-theme', 'dark');
        themeIcon.className = 'ri-sun-line';
        themeText.textContent = 'Light Mode';
    }

    themeToggle.addEventListener('click', function() {
        const currentTheme = container.getAttribute('data-theme');
        if (currentTheme === 'dark') {
            container.removeAttribute('data-theme');
            themeIcon.className = 'ri-moon-line';
            themeText.textContent = 'Dark Mode';
            localStorage.setItem('api-docs-theme', 'light');
        } else {
            container.setAttribute('data-theme', 'dark');
            themeIcon.className = 'ri-sun-line';
            themeText.textContent = 'Light Mode';
            localStorage.setItem('api-docs-theme', 'dark');
        }
    });

    // Keyboard Shortcut: Press / to focus search
    document.addEventListener('keydown', function(e) {
        if (e.key === '/' && !e.ctrlKey && !e.metaKey && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
            e.preventDefault();
            searchInput.focus();
            searchInput.select();
        }
        
        // Escape to clear search
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            searchInput.value = '';
            performSearch();
            searchInput.blur();
        }
    });

    // Filter Chips
    filterChips.forEach(chip => {
        chip.addEventListener('click', function() {
            filterChips.forEach(c => c.classList.remove('active'));
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

    // Enhanced Search Function
    function performSearch() {
        const term = searchTerm;
        const filter = currentFilter;
        let visibleCount = 0;

        if (term === '' && filter === 'all') {
            $('.endpoint-card, .docs-group').show();
            searchResults.classList.remove('visible');
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

        // Hide groups with no visible endpoints
        $('.docs-group').each(function() {
            const $group = $(this);
            const visibleInGroup = $group.find('.endpoint-card:visible').length;
            if (visibleInGroup === 0 && (term !== '' || filter !== 'all')) {
                $group.hide();
            }
        });

        // Update results count
        resultsCount.textContent = visibleCount;
        searchResults.classList.toggle('visible', term !== '' || filter !== 'all');

        // Show/hide no results
        if (visibleCount === 0) {
            noResults.show();
        } else {
            noResults.hide();
            updateActiveSidebar();
        }
    }

    // Update Active Sidebar Link
    function updateActiveSidebar() {
        const groups = document.querySelectorAll('.docs-group');
        const sidebarLinks = document.querySelectorAll('.sidebar-nav-link');
        
        let activeGroup = null;
        groups.forEach((group, index) => {
            const rect = group.getBoundingClientRect();
            if (rect.top <= 150 && rect.bottom >= 150 && $(group).is(':visible')) {
                activeGroup = index;
            }
        });

        sidebarLinks.forEach((link, index) => {
            link.classList.toggle('active', index === activeGroup);
        });
    }

    // Sidebar Navigation
    document.querySelectorAll('.sidebar-nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                document.querySelectorAll('.sidebar-nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Scroll Spy for Sidebar
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
            // Update button
            const $btn = $(button);
            const originalHtml = $btn.html();
            $btn.html('<i class="ri-check-line"></i><span>Copied!</span>');
            $btn.addClass('copied');
            
            setTimeout(function() {
                $btn.html(originalHtml);
                $btn.removeClass('copied');
            }, 2000);

            // Show SweetAlert2 toast notification
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
            
            // Show error notification with SweetAlert2
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
            } else {
                alert('Failed to copy to clipboard. Please try again.');
            }
        });
    };

    // Initialize
    updateActiveSidebar();
    filterChips[0].classList.add('active');

})(jQuery);
</script>
@endpush
