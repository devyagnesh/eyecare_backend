@extends('layouts.dashboard')

@section('title', 'API Documentation')

@push('styles')
<style>
    /* React.dev-inspired API Documentation Styles */
    :root {
        --react-blue: #087ea4;
        --react-blue-dark: #0a5d7a;
        --react-bg: #ffffff;
        --react-sidebar-bg: #f9fafb;
        --react-text: #23272f;
        --react-text-muted: #6b7280;
        --react-border: #e5e7eb;
        --react-code-bg: #1e1e1e;
        --react-code-text: #d4d4d4;
        --react-success: #22c55e;
        --react-error: #ef4444;
        --react-warning: #f59e0b;
    }

    [data-theme="dark"] {
        --react-bg: #0d1117;
        --react-sidebar-bg: #161b22;
        --react-text: #c9d1d9;
        --react-text-muted: #8b949e;
        --react-border: #30363d;
        --react-code-bg: #0d1117;
        --react-code-text: #c9d1d9;
    }

    .api-docs-wrapper {
        background: var(--react-bg);
        min-height: calc(100vh - 200px);
        display: flex;
        transition: background 0.2s ease;
    }

    /* Top Navigation Bar (React.dev style) */
    .api-docs-nav {
        position: sticky;
        top: 0;
        z-index: 100;
        background: var(--react-bg);
        border-bottom: 1px solid var(--react-border);
        padding: 1rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
    }

    .api-docs-nav-left {
        display: flex;
        align-items: center;
        gap: 2rem;
        flex: 1;
    }

    .api-docs-logo {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--react-blue);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .api-docs-search-wrapper {
        position: relative;
        flex: 1;
        max-width: 500px;
    }

    .api-docs-search {
        width: 100%;
        padding: 0.625rem 0.875rem 0.625rem 2.5rem;
        border: 1px solid var(--react-border);
        border-radius: 6px;
        background: var(--react-sidebar-bg);
        color: var(--react-text);
        font-size: 0.9375rem;
        transition: all 0.2s;
    }

    .api-docs-search:focus {
        outline: none;
        border-color: var(--react-blue);
        box-shadow: 0 0 0 3px rgba(8, 126, 164, 0.1);
    }

    .api-docs-search-icon {
        position: absolute;
        left: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--react-text-muted);
    }

    .api-docs-nav-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    /* Sidebar */
    .api-docs-sidebar {
        width: 260px;
        background: var(--react-sidebar-bg);
        border-right: 1px solid var(--react-border);
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
        color: var(--react-text-muted);
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
        padding: 0.5rem 1.5rem;
        color: var(--react-text);
        text-decoration: none;
        font-size: 0.9375rem;
        transition: all 0.15s;
        border-left: 3px solid transparent;
    }

    .sidebar-nav-link:hover {
        background: rgba(8, 126, 164, 0.08);
        color: var(--react-blue);
    }

    .sidebar-nav-link.active {
        background: rgba(8, 126, 164, 0.12);
        color: var(--react-blue);
        border-left-color: var(--react-blue);
        font-weight: 600;
    }

    .sidebar-nav-link i {
        font-size: 1rem;
        width: 18px;
        text-align: center;
    }

    /* Main Content */
    .api-docs-content {
        flex: 1;
        max-width: 900px;
        margin: 0 auto;
        padding: 3rem 2rem;
    }

    .docs-group {
        margin-bottom: 4rem;
    }

    .docs-group-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--react-border);
    }

    .docs-group-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--react-text);
        margin: 0 0 0.5rem 0;
        line-height: 1.2;
    }

    .docs-group-description {
        font-size: 1.125rem;
        color: var(--react-text-muted);
        margin: 0;
        line-height: 1.6;
    }

    /* Endpoint Card */
    .endpoint-item {
        margin-bottom: 3rem;
        scroll-margin-top: 100px;
    }

    .endpoint-header-section {
        margin-bottom: 1.5rem;
    }

    .endpoint-method-url {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
    }

    .method-tag {
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        min-width: 65px;
        text-align: center;
    }

    .method-get { background: var(--react-success); color: white; }
    .method-post { background: var(--react-blue); color: white; }
    .method-put { background: var(--react-warning); color: white; }
    .method-delete { background: var(--react-error); color: white; }
    .method-patch { background: #8b5cf6; color: white; }

    .endpoint-url-code {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.9375rem;
        color: var(--react-blue);
        font-weight: 500;
        background: rgba(8, 126, 164, 0.1);
        padding: 0.375rem 0.75rem;
        border-radius: 4px;
    }

    .endpoint-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--react-text);
        margin: 0.5rem 0;
        line-height: 1.3;
    }

    .endpoint-description {
        font-size: 1rem;
        color: var(--react-text-muted);
        margin: 0.75rem 0 0 0;
        line-height: 1.6;
    }

    /* Sections */
    .docs-section {
        margin: 2rem 0;
    }

    .docs-section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--react-text);
        margin: 0 0 1rem 0;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--react-border);
    }

    /* Tables */
    .docs-table {
        width: 100%;
        border-collapse: collapse;
        margin: 1rem 0;
        font-size: 0.9375rem;
    }

    .docs-table th {
        background: var(--react-sidebar-bg);
        padding: 0.75rem 1rem;
        text-align: left;
        font-weight: 600;
        color: var(--react-text);
        border-bottom: 2px solid var(--react-border);
        font-size: 0.875rem;
    }

    .docs-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--react-border);
        color: var(--react-text);
        vertical-align: top;
    }

    .docs-table tr:last-child td {
        border-bottom: none;
    }

    .param-code {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        color: var(--react-blue);
        font-weight: 500;
        font-size: 0.875rem;
    }

    .type-badge {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        color: var(--react-success);
        background: rgba(34, 197, 94, 0.1);
        padding: 0.125rem 0.5rem;
        border-radius: 3px;
        font-size: 0.8125rem;
    }

    .required-tag {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        padding: 0.125rem 0.5rem;
        border-radius: 3px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .optional-tag {
        background: rgba(107, 114, 128, 0.1);
        color: var(--react-text-muted);
        padding: 0.125rem 0.5rem;
        border-radius: 3px;
        font-size: 0.75rem;
    }

    /* Code Blocks */
    .code-example {
        background: var(--react-code-bg);
        border-radius: 8px;
        padding: 1.25rem;
        position: relative;
        margin: 1rem 0;
        overflow-x: auto;
        border: 1px solid var(--react-border);
    }

    .code-example pre {
        margin: 0;
        color: var(--react-code-text);
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.875rem;
        line-height: 1.6;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .code-example code {
        color: var(--react-code-text);
    }

    .code-copy-btn {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--react-code-text);
        padding: 0.375rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .code-copy-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .code-copy-btn.copied {
        background: var(--react-success);
        border-color: var(--react-success);
        color: white;
    }

    /* Status Codes */
    .status-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin: 1rem 0;
    }

    .status-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem;
        background: var(--react-sidebar-bg);
        border-radius: 6px;
        border-left: 3px solid transparent;
    }

    .status-code-badge {
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.8125rem;
        min-width: 55px;
        text-align: center;
    }

    .status-2xx { 
        background: rgba(34, 197, 94, 0.15); 
        color: var(--react-success);
        border-left-color: var(--react-success);
    }
    .status-4xx { 
        background: rgba(239, 68, 68, 0.15); 
        color: var(--react-error);
        border-left-color: var(--react-error);
    }
    .status-5xx { 
        background: rgba(245, 158, 11, 0.15); 
        color: var(--react-warning);
        border-left-color: var(--react-warning);
    }

    /* Callout Boxes */
    .callout {
        padding: 1rem 1.25rem;
        border-radius: 6px;
        margin: 1.5rem 0;
        border-left: 4px solid;
    }

    .callout-info {
        background: rgba(8, 126, 164, 0.1);
        border-left-color: var(--react-blue);
        color: var(--react-text);
    }

    .callout-warning {
        background: rgba(245, 158, 11, 0.1);
        border-left-color: var(--react-warning);
        color: var(--react-text);
    }

    .callout p {
        margin: 0;
        font-size: 0.9375rem;
        line-height: 1.6;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .api-docs-wrapper {
            flex-direction: column;
        }

        .api-docs-sidebar {
            width: 100%;
            height: auto;
            position: relative;
            top: 0;
            border-right: none;
            border-bottom: 1px solid var(--react-border);
        }

        .api-docs-content {
            padding: 2rem 1.5rem;
        }
    }

    .no-results-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--react-text-muted);
    }

    .no-results-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
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
<div class="api-docs-wrapper" id="api-docs-wrapper">
    <!-- Top Navigation -->
    <nav class="api-docs-nav">
        <div class="api-docs-nav-left">
            <a href="#getting-started" class="api-docs-logo">
                <i class="ri-code-s-slash-line"></i>
                API Reference
            </a>
            <div class="api-docs-search-wrapper">
                <i class="ri-search-line api-docs-search-icon"></i>
                <input type="text" 
                       class="api-docs-search" 
                       id="api-search" 
                       placeholder="Search endpoints...">
            </div>
        </div>
        <div class="api-docs-nav-right">
            <div style="font-size: 0.875rem; color: var(--react-text-muted);">
                <strong>v{{ $apiVersion }}</strong> · {{ $lastUpdated }}
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="api-docs-sidebar">
        <div class="sidebar-section">
            <div class="sidebar-section-title">API Groups</div>
            <ul class="sidebar-nav">
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

    <!-- Main Content -->
    <main class="api-docs-content">
        <!-- Getting Started -->
        <div class="docs-group" id="getting-started">
            <div class="docs-group-header">
                <h1 class="docs-group-title">Getting Started</h1>
                <p class="docs-group-description">
                    Welcome to the Eyecare API documentation. This guide will help you integrate with our RESTful API.
                </p>
            </div>

            <div class="callout callout-info">
                <p><strong>Base URL:</strong> <code style="background: rgba(8, 126, 164, 0.2); padding: 2px 6px; border-radius: 3px;">{{ $baseUrl }}</code></p>
                <p style="margin-top: 0.5rem;"><strong>Authentication:</strong> Most endpoints require authentication. Include your Bearer token in the Authorization header.</p>
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
            <article class="endpoint-item" 
                     id="endpoint-{{ $groupIndex }}-{{ $endpointIndex }}"
                     data-endpoint-name="{{ strtolower($endpoint['name']) }}"
                     data-endpoint-method="{{ strtolower($endpoint['method']) }}"
                     data-endpoint-url="{{ strtolower($endpoint['url']) }}"
                     data-endpoint-description="{{ strtolower($endpoint['description']) }}">
                
                <div class="endpoint-header-section">
                    <div class="endpoint-method-url">
                        <span class="method-tag method-{{ strtolower($endpoint['method']) }}">
                            {{ $endpoint['method'] }}
                        </span>
                        <code class="endpoint-url-code">{{ $endpoint['url'] }}</code>
                    </div>
                    <h2 class="endpoint-title">{{ $endpoint['name'] }}</h2>
                    <p class="endpoint-description">{{ $endpoint['description'] ?: 'No description available.' }}</p>
                </div>

                <!-- Headers -->
                @if(!empty($endpoint['headers']))
                <div class="docs-section">
                    <h3 class="docs-section-title">Headers</h3>
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
                                <td><code style="font-size: 0.8125rem;">{{ $headerData['example'] ?? '' }}</code></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- Query Parameters -->
                @if(!empty($endpoint['query_parameters']))
                <div class="docs-section">
                    <h3 class="docs-section-title">Query Parameters</h3>
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
                    <h3 class="docs-section-title">Request Parameters</h3>
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

                <!-- Request Payload Example -->
                @if($endpoint['payload_example'])
                <div class="docs-section">
                    <h3 class="docs-section-title">Request Payload</h3>
                    <div class="code-example">
                        <button class="code-copy-btn" onclick="copyCode('payload-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                            <i class="ri-file-copy-line"></i> Copy
                        </button>
                        <pre id="payload-{{ $groupIndex }}-{{ $endpointIndex }}"><code class="json">{{ is_string($endpoint['payload_example']) ? $endpoint['payload_example'] : json_encode($endpoint['payload_example'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                    </div>
                </div>
                @endif

                <!-- Success Response Example -->
                @if($endpoint['success_response'])
                <div class="docs-section">
                    <h3 class="docs-section-title" style="color: var(--react-success);">
                        Success Response
                    </h3>
                    <div class="code-example">
                        <button class="code-copy-btn" onclick="copyCode('success-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                            <i class="ri-file-copy-line"></i> Copy
                        </button>
                        <pre id="success-{{ $groupIndex }}-{{ $endpointIndex }}"><code class="json">{{ is_string($endpoint['success_response']) ? $endpoint['success_response'] : json_encode($endpoint['success_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                    </div>
                </div>
                @endif

                <!-- Error Response Example -->
                @if($endpoint['error_response'])
                <div class="docs-section">
                    <h3 class="docs-section-title" style="color: var(--react-error);">
                        Error Response
                    </h3>
                    <div class="code-example">
                        <button class="code-copy-btn" onclick="copyCode('error-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                            <i class="ri-file-copy-line"></i> Copy
                        </button>
                        <pre id="error-{{ $groupIndex }}-{{ $endpointIndex }}"><code class="json">{{ is_string($endpoint['error_response']) ? $endpoint['error_response'] : json_encode($endpoint['error_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                    </div>
                </div>
                @endif

                <!-- Status Codes -->
                @if(!empty($endpoint['status_codes']))
                <div class="docs-section">
                    <h3 class="docs-section-title">Status Codes</h3>
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
        <div id="no-results" class="no-results-state" style="display: none;">
            <i class="ri-search-line"></i>
            <h3>No endpoints found</h3>
            <p>Try adjusting your search criteria</p>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
(function($) {
    'use strict';

    const wrapper = document.getElementById('api-docs-wrapper');
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const themeText = document.getElementById('theme-text');

    // Theme Toggle
    const savedTheme = localStorage.getItem('api-docs-theme') || 'light';
    if (savedTheme === 'dark') {
        wrapper.setAttribute('data-theme', 'dark');
        themeIcon.className = 'ri-sun-line';
        themeText.textContent = 'Light Mode';
    }

    themeToggle.addEventListener('click', function() {
        const currentTheme = wrapper.getAttribute('data-theme');
        if (currentTheme === 'dark') {
            wrapper.removeAttribute('data-theme');
            themeIcon.className = 'ri-moon-line';
            themeText.textContent = 'Dark Mode';
            localStorage.setItem('api-docs-theme', 'light');
        } else {
            wrapper.setAttribute('data-theme', 'dark');
            themeIcon.className = 'ri-sun-line';
            themeText.textContent = 'Light Mode';
            localStorage.setItem('api-docs-theme', 'dark');
        }
    });

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

    // Search Functionality
    $('#api-search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase().trim();
        let visibleCount = 0;

        if (searchTerm === '') {
            $('.endpoint-item, .docs-group').show();
            $('#no-results').hide();
            return;
        }

        $('.endpoint-item').each(function() {
            const $item = $(this);
            const name = $item.data('endpoint-name') || '';
            const method = $item.data('endpoint-method') || '';
            const url = $item.data('endpoint-url') || '';
            const description = $item.data('endpoint-description') || '';

            const matches = name.includes(searchTerm) || 
                          method.includes(searchTerm) || 
                          url.includes(searchTerm) || 
                          description.includes(searchTerm);

            if (matches) {
                $item.show();
                $item.closest('.docs-group').show();
                visibleCount++;
            } else {
                $item.hide();
            }
        });

        $('.docs-group').each(function() {
            const $group = $(this);
            const visibleInGroup = $group.find('.endpoint-item:visible').length;
            if (visibleInGroup === 0 && searchTerm !== '') {
                $group.hide();
            }
        });

        if (visibleCount === 0) {
            $('#no-results').show();
        } else {
            $('#no-results').hide();
        }
    });

    // Copy Code Function
    window.copyCode = function(elementId, button) {
        const element = document.getElementById(elementId);
        if (!element) return;

        const text = element.textContent || element.innerText;
        
        navigator.clipboard.writeText(text).then(function() {
            const $btn = $(button);
            const originalHtml = $btn.html();
            $btn.html('<i class="ri-check-line"></i> Copied!');
            $btn.addClass('copied');
            
            setTimeout(function() {
                $btn.html(originalHtml);
                $btn.removeClass('copied');
            }, 2000);
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
        });
    };

    // Highlight active section on scroll
    let currentSection = null;
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('.docs-group');
        const sidebarLinks = document.querySelectorAll('.sidebar-nav-link');
        
        sections.forEach((section, index) => {
            const rect = section.getBoundingClientRect();
            if (rect.top <= 150 && rect.bottom >= 150) {
                if (currentSection !== index) {
                    currentSection = index;
                    sidebarLinks.forEach(link => link.classList.remove('active'));
                    if (sidebarLinks[index]) {
                        sidebarLinks[index].classList.add('active');
                    }
                }
            }
        });
    });

})(jQuery);
</script>
@endpush

