@extends('layouts.dashboard')

@section('title', 'API Documentation')

@push('styles')
<style>
    :root {
        --doc-sidebar-width: 280px;
        --doc-primary: #6366f1;
        --doc-primary-dark: #4f46e5;
        --doc-success: #10b981;
        --doc-error: #ef4444;
        --doc-warning: #f59e0b;
        --doc-bg: #ffffff;
        --doc-sidebar-bg: #f8fafc;
        --doc-text: #1e293b;
        --doc-text-muted: #64748b;
        --doc-border: #e2e8f0;
        --doc-code-bg: #0f172a;
        --doc-code-text: #e2e8f0;
    }

    [data-theme="dark"] {
        --doc-bg: #0f172a;
        --doc-sidebar-bg: #1e293b;
        --doc-text: #f1f5f9;
        --doc-text-muted: #94a3b8;
        --doc-border: #334155;
        --doc-code-bg: #020617;
        --doc-code-text: #cbd5e1;
    }

    .api-doc-container {
        display: flex;
        min-height: calc(100vh - 200px);
        background: var(--doc-bg);
        transition: background 0.3s;
    }

    /* Left Sidebar Navigation */
    .api-doc-sidebar {
        width: var(--doc-sidebar-width);
        background: var(--doc-sidebar-bg);
        border-right: 1px solid var(--doc-border);
        padding: 2rem 0;
        position: sticky;
        top: 0;
        height: calc(100vh - 200px);
        overflow-y: auto;
        transition: all 0.3s;
    }

    .sidebar-header {
        padding: 0 1.5rem 1.5rem;
        border-bottom: 1px solid var(--doc-border);
        margin-bottom: 1rem;
    }

    .sidebar-header h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--doc-text);
        margin: 0 0 0.5rem 0;
    }

    .sidebar-header .meta {
        font-size: 0.875rem;
        color: var(--doc-text-muted);
    }

    .sidebar-search {
        padding: 0 1.5rem 1rem;
        margin-bottom: 1rem;
    }

    .sidebar-search input {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid var(--doc-border);
        border-radius: 8px;
        background: var(--doc-bg);
        color: var(--doc-text);
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .sidebar-search input:focus {
        outline: none;
        border-color: var(--doc-primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .sidebar-nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-nav-item {
        margin: 0.25rem 0;
    }

    .sidebar-nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.5rem;
        color: var(--doc-text-muted);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
        border-left: 3px solid transparent;
    }

    .sidebar-nav-link:hover {
        background: rgba(99, 102, 241, 0.05);
        color: var(--doc-primary);
    }

    .sidebar-nav-link.active {
        background: rgba(99, 102, 241, 0.1);
        color: var(--doc-primary);
        border-left-color: var(--doc-primary);
        font-weight: 600;
    }

    .sidebar-nav-link i {
        font-size: 1.125rem;
        width: 20px;
        text-align: center;
    }

    /* Main Content Area */
    .api-doc-content {
        flex: 1;
        padding: 2rem;
        overflow-y: auto;
    }

    .content-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid var(--doc-border);
    }

    .content-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--doc-text);
        margin: 0 0 0.5rem 0;
    }

    .content-header .description {
        font-size: 1rem;
        color: var(--doc-text-muted);
        margin: 0;
    }

    /* Endpoint Card */
    .endpoint-card {
        background: var(--doc-bg);
        border: 1px solid var(--doc-border);
        border-radius: 12px;
        margin-bottom: 2rem;
        overflow: hidden;
        transition: all 0.3s;
    }

    .endpoint-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-color: var(--doc-primary);
    }

    .endpoint-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
        border-bottom: 1px solid var(--doc-border);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .endpoint-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
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

    .method-get { background: var(--doc-success); color: white; }
    .method-post { background: #3b82f6; color: white; }
    .method-put { background: var(--doc-warning); color: white; }
    .method-delete { background: var(--doc-error); color: white; }
    .method-patch { background: #8b5cf6; color: white; }

    .endpoint-url {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.9375rem;
        color: var(--doc-primary);
        font-weight: 500;
        flex: 1;
    }

    .endpoint-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--doc-text);
        margin: 0.5rem 0;
    }

    .endpoint-description {
        color: var(--doc-text-muted);
        margin: 0;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .endpoint-toggle {
        background: transparent;
        border: none;
        color: var(--doc-text-muted);
        cursor: pointer;
        font-size: 1.25rem;
        transition: transform 0.3s;
        padding: 0.5rem;
    }

    .endpoint-toggle.expanded {
        transform: rotate(180deg);
    }

    .endpoint-body {
        display: none;
        padding: 1.5rem;
    }

    .endpoint-body.expanded {
        display: block;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .section {
        margin-bottom: 2rem;
    }

    .section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--doc-text);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--doc-border);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: var(--doc-primary);
    }

    /* Tables */
    .params-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
        font-size: 0.875rem;
    }

    .params-table th {
        background: rgba(99, 102, 241, 0.05);
        padding: 0.75rem;
        text-align: left;
        font-weight: 600;
        color: var(--doc-text);
        border-bottom: 2px solid var(--doc-border);
    }

    .params-table td {
        padding: 0.75rem;
        border-bottom: 1px solid var(--doc-border);
        color: var(--doc-text);
    }

    .params-table tr:last-child td {
        border-bottom: none;
    }

    .param-name {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        color: var(--doc-primary);
        font-weight: 500;
    }

    .param-type {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        color: var(--doc-success);
        background: rgba(16, 185, 129, 0.1);
        padding: 0.125rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
    }

    .required-badge {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        padding: 0.125rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .optional-badge {
        background: rgba(107, 114, 128, 0.1);
        color: var(--doc-text-muted);
        padding: 0.125rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
    }

    /* Code Blocks */
    .code-block {
        background: var(--doc-code-bg);
        border-radius: 8px;
        padding: 1.25rem;
        position: relative;
        margin-bottom: 1rem;
        overflow-x: auto;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .code-block pre {
        margin: 0;
        color: var(--doc-code-text);
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.8125rem;
        line-height: 1.6;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .code-block code {
        color: var(--doc-code-text);
    }

    .copy-button {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--doc-code-text);
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .copy-button:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .copy-button.copied {
        background: var(--doc-success);
        border-color: var(--doc-success);
    }

    /* Status Codes */
    .status-codes {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .status-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem;
        background: rgba(99, 102, 241, 0.05);
        border-radius: 6px;
    }

    .status-code {
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        min-width: 60px;
        text-align: center;
    }

    .status-2xx { background: rgba(16, 185, 129, 0.2); color: #059669; }
    .status-4xx { background: rgba(239, 68, 68, 0.2); color: #dc2626; }
    .status-5xx { background: rgba(245, 158, 11, 0.2); color: #d97706; }

    /* Notes */
    .notes-box {
        background: rgba(245, 158, 11, 0.1);
        border-left: 4px solid var(--doc-warning);
        padding: 1rem;
        border-radius: 6px;
        margin-top: 1rem;
    }

    .notes-box p {
        margin: 0;
        color: var(--doc-text);
        font-size: 0.875rem;
        line-height: 1.6;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .api-doc-container {
            flex-direction: column;
        }

        .api-doc-sidebar {
            width: 100%;
            height: auto;
            position: relative;
            border-right: none;
            border-bottom: 1px solid var(--doc-border);
        }

        .api-doc-content {
            padding: 1.5rem;
        }
    }

    /* No Results */
    .no-results {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--doc-text-muted);
    }

    .no-results-icon {
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
<div class="api-doc-container" id="api-doc-container">
    <!-- Left Sidebar -->
    <aside class="api-doc-sidebar">
        <div class="sidebar-header">
            <h2>API Reference</h2>
            <div class="meta">
                <div>Version: {{ $apiVersion }}</div>
                <div>Base URL: <code style="font-size: 0.75rem;">{{ $baseUrl }}</code></div>
            </div>
        </div>

        <div class="sidebar-search">
            <input type="text" id="sidebar-search" placeholder="Search endpoints...">
        </div>

        <nav>
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
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="api-doc-content">
        @foreach($endpoints as $groupIndex => $group)
        <div class="api-doc-group" id="group-{{ $groupIndex }}" data-group-name="{{ strtolower($group['group']) }}">
            <div class="content-header">
                <h1>{{ $group['group'] }}</h1>
                <p class="description">{{ $group['description'] }}</p>
            </div>

            <div class="endpoints-list">
                @foreach($group['endpoints'] as $endpointIndex => $endpoint)
                <div class="endpoint-card" 
                     data-endpoint-name="{{ strtolower($endpoint['name']) }}"
                     data-endpoint-method="{{ strtolower($endpoint['method']) }}"
                     data-endpoint-url="{{ strtolower($endpoint['url']) }}"
                     data-endpoint-description="{{ strtolower($endpoint['description']) }}">
                    
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <div class="endpoint-header-left">
                            <span class="method-badge method-{{ strtolower($endpoint['method']) }}">
                                {{ $endpoint['method'] }}
                            </span>
                            <code class="endpoint-url">{{ $endpoint['url'] }}</code>
                        </div>
                        <div style="flex: 1; margin-left: 1rem;">
                            <h3 class="endpoint-title">{{ $endpoint['name'] }}</h3>
                            <p class="endpoint-description">{{ $endpoint['description'] ?: 'No description available.' }}</p>
                        </div>
                        <button class="endpoint-toggle" type="button">
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                    </div>

                    <div class="endpoint-body">
                        <!-- Headers Table -->
                        @if(!empty($endpoint['headers']))
                        <div class="section">
                            <h4 class="section-title">
                                <i class="ri-file-list-3-line"></i>
                                Headers
                            </h4>
                            <table class="params-table">
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
                                        <td><code class="param-name">{{ $headerName }}</code></td>
                                        <td><span class="param-type">{{ $headerData['type'] ?? 'string' }}</span></td>
                                        <td>
                                            @if($headerData['required'] ?? false)
                                            <span class="required-badge">Required</span>
                                            @else
                                            <span class="optional-badge">Optional</span>
                                            @endif
                                        </td>
                                        <td>{{ $headerData['description'] ?? '' }}</td>
                                        <td><code style="font-size: 0.75rem;">{{ $headerData['example'] ?? '' }}</code></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <!-- Query Parameters -->
                        @if(!empty($endpoint['query_parameters']))
                        <div class="section">
                            <h4 class="section-title">
                                <i class="ri-search-line"></i>
                                Query Parameters
                            </h4>
                            <table class="params-table">
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
                                        <td><code class="param-name">{{ $paramName }}</code></td>
                                        <td><span class="param-type">{{ $paramDetails['type'] ?? 'string' }}</span></td>
                                        <td>
                                            @if($paramDetails['required'] ?? false)
                                            <span class="required-badge">Required</span>
                                            @else
                                            <span class="optional-badge">Optional</span>
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
                        <div class="section">
                            <h4 class="section-title">
                                <i class="ri-file-edit-line"></i>
                                Request Parameters
                            </h4>
                            <table class="params-table">
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
                                        <td><code class="param-name">{{ $paramName }}</code></td>
                                        <td><span class="param-type">{{ $paramDetails['type'] ?? 'string' }}</span></td>
                                        <td>
                                            @if($paramDetails['required'] ?? false)
                                            <span class="required-badge">Required</span>
                                            @else
                                            <span class="optional-badge">Optional</span>
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
                        <div class="section">
                            <h4 class="section-title">
                                <i class="ri-code-s-slash-line"></i>
                                Request Payload Example
                            </h4>
                            <div class="code-block">
                                <button class="copy-button" onclick="copyToClipboard('payload-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                    <i class="ri-file-copy-line"></i> Copy
                                </button>
                                <pre id="payload-{{ $groupIndex }}-{{ $endpointIndex }}"><code class="json">{{ is_string($endpoint['payload_example']) ? $endpoint['payload_example'] : json_encode($endpoint['payload_example'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                            </div>
                        </div>
                        @endif

                        <!-- Success Response Example -->
                        @if($endpoint['success_response'])
                        <div class="section">
                            <h4 class="section-title" style="color: var(--doc-success);">
                                <i class="ri-checkbox-circle-line"></i>
                                Success Response Example
                            </h4>
                            <div class="code-block">
                                <button class="copy-button" onclick="copyToClipboard('success-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                    <i class="ri-file-copy-line"></i> Copy
                                </button>
                                <pre id="success-{{ $groupIndex }}-{{ $endpointIndex }}"><code class="json">{{ is_string($endpoint['success_response']) ? $endpoint['success_response'] : json_encode($endpoint['success_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                            </div>
                        </div>
                        @endif

                        <!-- Error Response Example -->
                        @if($endpoint['error_response'])
                        <div class="section">
                            <h4 class="section-title" style="color: var(--doc-error);">
                                <i class="ri-error-warning-line"></i>
                                Error Response Example
                            </h4>
                            <div class="code-block">
                                <button class="copy-button" onclick="copyToClipboard('error-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                    <i class="ri-file-copy-line"></i> Copy
                                </button>
                                <pre id="error-{{ $groupIndex }}-{{ $endpointIndex }}"><code class="json">{{ is_string($endpoint['error_response']) ? $endpoint['error_response'] : json_encode($endpoint['error_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                            </div>
                        </div>
                        @endif

                        <!-- Status Codes -->
                        @if(!empty($endpoint['status_codes']))
                        <div class="section">
                            <h4 class="section-title">
                                <i class="ri-information-line"></i>
                                Status Codes
                            </h4>
                            <div class="status-codes">
                                @foreach($endpoint['status_codes'] as $code => $message)
                                <div class="status-item">
                                    <span class="status-code status-{{ floor($code / 100) }}xx">{{ $code }}</span>
                                    <span>{{ $message }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Notes -->
                        @if(!empty($endpoint['notes']))
                        <div class="section">
                            <div class="notes-box">
                                <p><strong>Note:</strong> {{ $endpoint['notes'] }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <!-- No Results -->
        <div id="no-results" class="no-results" style="display: none;">
            <div class="no-results-icon">
                <i class="ri-search-line"></i>
            </div>
            <h5>No endpoints found</h5>
            <p>Try adjusting your search criteria</p>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
(function($) {
    'use strict';

    const container = document.getElementById('api-doc-container');
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const themeText = document.getElementById('theme-text');

    // Theme Toggle
    const savedTheme = localStorage.getItem('api-doc-theme') || 'light';
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
            localStorage.setItem('api-doc-theme', 'light');
        } else {
            container.setAttribute('data-theme', 'dark');
            themeIcon.className = 'ri-sun-line';
            themeText.textContent = 'Light Mode';
            localStorage.setItem('api-doc-theme', 'dark');
        }
    });

    // Sidebar Navigation
    document.querySelectorAll('.sidebar-nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                // Remove active from all links
                document.querySelectorAll('.sidebar-nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                // Scroll to target
                targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Endpoint Toggle
    window.toggleEndpoint = function(header) {
        const card = header.closest('.endpoint-card');
        const body = card.querySelector('.endpoint-body');
        const toggle = card.querySelector('.endpoint-toggle');
        
        if (body.classList.contains('expanded')) {
            body.classList.remove('expanded');
            toggle.classList.remove('expanded');
        } else {
            body.classList.add('expanded');
            toggle.classList.add('expanded');
        }
    };

    // Search Functionality
    $('#sidebar-search, #endpoint-search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase().trim();
        let visibleCount = 0;

        if (searchTerm === '') {
            $('.endpoint-card, .api-doc-group').show();
            $('#no-results').hide();
            return;
        }

        $('.endpoint-card').each(function() {
            const $card = $(this);
            const name = $card.data('endpoint-name') || '';
            const method = $card.data('endpoint-method') || '';
            const url = $card.data('endpoint-url') || '';
            const description = $card.data('endpoint-description') || '';

            const matches = name.includes(searchTerm) || 
                          method.includes(searchTerm) || 
                          url.includes(searchTerm) || 
                          description.includes(searchTerm);

            if (matches) {
                $card.show();
                $card.closest('.api-doc-group').show();
                visibleCount++;
            } else {
                $card.hide();
            }
        });

        // Hide groups with no visible endpoints
        $('.api-doc-group').each(function() {
            const $group = $(this);
            const visibleInGroup = $group.find('.endpoint-card:visible').length;
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

    // Copy to Clipboard
    window.copyToClipboard = function(elementId, button) {
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

    // Auto-expand first endpoint in each group
    document.querySelectorAll('.api-doc-group').forEach(group => {
        const firstCard = group.querySelector('.endpoint-card');
        if (firstCard) {
            const header = firstCard.querySelector('.endpoint-header');
            if (header) {
                toggleEndpoint(header);
            }
        }
    });

})(jQuery);
</script>
@endpush
