@extends('layouts.dashboard')

@section('title', 'API Documentation')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
<style>
    :root {
        --api-doc-primary: #667eea;
        --api-doc-primary-dark: #5568d3;
        --api-doc-bg: #ffffff;
        --api-doc-text: #1f2937;
        --api-doc-text-muted: #6b7280;
        --api-doc-border: #e5e7eb;
        --api-doc-code-bg: #1e293b;
        --api-doc-code-text: #e2e8f0;
        --api-doc-success: #10b981;
        --api-doc-error: #ef4444;
        --api-doc-warning: #f59e0b;
    }

    [data-theme="dark"] {
        --api-doc-bg: #1f2937;
        --api-doc-text: #f9fafb;
        --api-doc-text-muted: #d1d5db;
        --api-doc-border: #374151;
        --api-doc-code-bg: #0f172a;
        --api-doc-code-text: #cbd5e1;
    }

    .api-doc-wrapper {
        background: var(--api-doc-bg);
        color: var(--api-doc-text);
        min-height: 100vh;
        transition: background 0.3s, color 0.3s;
    }

    .api-doc-header {
        background: linear-gradient(135deg, var(--api-doc-primary) 0%, #764ba2 100%);
        color: white;
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        border-radius: 0 0 20px 20px;
    }

    .api-doc-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .api-doc-header p {
        font-size: 1.1rem;
        opacity: 0.95;
    }

    .api-doc-controls {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .api-doc-search {
        flex: 1;
        min-width: 300px;
        position: relative;
    }

    .api-doc-search input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 3rem;
        border: 2px solid var(--api-doc-border);
        border-radius: 8px;
        font-size: 1rem;
        background: var(--api-doc-bg);
        color: var(--api-doc-text);
        transition: all 0.3s;
    }

    .api-doc-search input:focus {
        outline: none;
        border-color: var(--api-doc-primary);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .api-doc-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--api-doc-text-muted);
    }

    .theme-toggle {
        padding: 0.75rem 1.5rem;
        background: var(--api-doc-primary);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .theme-toggle:hover {
        background: var(--api-doc-primary-dark);
        transform: translateY(-2px);
    }

    .api-doc-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        border-bottom: 2px solid var(--api-doc-border);
        padding-bottom: 0.5rem;
    }

    .api-doc-tab {
        padding: 0.75rem 1.5rem;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        color: var(--api-doc-text-muted);
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }

    .api-doc-tab:hover {
        color: var(--api-doc-primary);
        background: rgba(102, 126, 234, 0.05);
    }

    .api-doc-tab.active {
        color: var(--api-doc-primary);
        border-bottom-color: var(--api-doc-primary);
        background: rgba(102, 126, 234, 0.05);
    }

    .api-doc-tab-content {
        display: none;
    }

    .api-doc-tab-content.active {
        display: block;
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .endpoint-card {
        background: var(--api-doc-bg);
        border: 1px solid var(--api-doc-border);
        border-radius: 12px;
        margin-bottom: 2rem;
        overflow: hidden;
        transition: all 0.3s;
    }

    .endpoint-card:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border-color: var(--api-doc-primary);
    }

    .endpoint-header {
        padding: 1.5rem;
        background: var(--api-doc-bg);
        border-bottom: 1px solid var(--api-doc-border);
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
        padding: 6px 14px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        min-width: 60px;
        text-align: center;
    }

    .method-get { background: #10b981; color: white; }
    .method-post { background: #3b82f6; color: white; }
    .method-put { background: #f59e0b; color: white; }
    .method-delete { background: #ef4444; color: white; }
    .method-patch { background: #8b5cf6; color: white; }

    .endpoint-url {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.95rem;
        color: var(--api-doc-primary);
        font-weight: 500;
        flex: 1;
    }

    .endpoint-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--api-doc-text);
        margin: 0.5rem 0;
    }

    .endpoint-description {
        color: var(--api-doc-text-muted);
        margin: 0;
        line-height: 1.6;
    }

    .endpoint-toggle {
        background: transparent;
        border: none;
        color: var(--api-doc-text-muted);
        cursor: pointer;
        font-size: 1.5rem;
        transition: transform 0.3s;
    }

    .endpoint-toggle.expanded {
        transform: rotate(180deg);
    }

    .endpoint-content {
        display: none;
        padding: 1.5rem;
        border-top: 1px solid var(--api-doc-border);
    }

    .endpoint-content.expanded {
        display: block;
    }

    .endpoint-section {
        margin-bottom: 2rem;
    }

    .endpoint-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--api-doc-text);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--api-doc-border);
    }

    .params-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }

    .params-table th {
        background: rgba(102, 126, 234, 0.05);
        padding: 0.75rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--api-doc-text);
        border-bottom: 2px solid var(--api-doc-border);
    }

    .params-table td {
        padding: 0.75rem;
        border-bottom: 1px solid var(--api-doc-border);
        font-size: 0.9rem;
        color: var(--api-doc-text);
    }

    .params-table tr:last-child td {
        border-bottom: none;
    }

    .param-name {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        color: var(--api-doc-primary);
        font-weight: 500;
    }

    .param-type {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        color: var(--api-doc-success);
        background: rgba(16, 185, 129, 0.1);
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .required-badge {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .optional-badge {
        background: rgba(107, 114, 128, 0.1);
        color: var(--api-doc-text-muted);
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
    }

    .code-block {
        background: var(--api-doc-code-bg);
        border-radius: 8px;
        padding: 1.25rem;
        position: relative;
        margin-bottom: 1rem;
        overflow-x: auto;
    }

    .code-block pre {
        margin: 0;
        color: var(--api-doc-code-text);
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.85rem;
        line-height: 1.6;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .copy-button {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--api-doc-code-text);
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .copy-button:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .copy-button.copied {
        background: var(--api-doc-success);
        border-color: var(--api-doc-success);
    }

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
        background: rgba(102, 126, 234, 0.05);
        border-radius: 6px;
    }

    .status-code {
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 0.75rem;
        min-width: 60px;
        text-align: center;
    }

    .status-2xx { background: rgba(16, 185, 129, 0.2); color: #059669; }
    .status-4xx { background: rgba(239, 68, 68, 0.2); color: #dc2626; }
    .status-5xx { background: rgba(245, 158, 11, 0.2); color: #d97706; }

    .back-to-top {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 50px;
        height: 50px;
        background: var(--api-doc-primary);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.5rem;
        display: none;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        transition: all 0.3s;
        z-index: 1000;
    }

    .back-to-top:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
    }

    .back-to-top.visible {
        display: flex;
    }

    .no-results {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--api-doc-text-muted);
    }

    .no-results-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .info-card {
        background: rgba(102, 126, 234, 0.05);
        border: 1px solid var(--api-doc-primary);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-card h3 {
        color: var(--api-doc-primary);
        margin-bottom: 1rem;
    }

    .info-card code {
        background: var(--api-doc-code-bg);
        color: var(--api-doc-code-text);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.9rem;
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
        <a href="{{ route('admin.api-documentation.download') }}" class="btn btn-primary-light btn-wave">
            <i class="ri-download-line align-middle"></i> Download Postman Collection
        </a>
    </div>
</div>
@endsection

@section('content')

<div class="api-doc-wrapper">
    <!-- Header -->
    <div class="api-doc-header">
        <h1>API Documentation</h1>
        <p>Complete reference for all available API endpoints</p>
        <div class="mt-3 d-flex gap-4 flex-wrap">
            <div><strong>Version:</strong> {{ $apiVersion }}</div>
            <div><strong>Base URL:</strong> <code style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 4px;">{{ $baseUrl }}</code></div>
            <div><strong>Last Updated:</strong> {{ $lastUpdated }}</div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Controls -->
        <div class="api-doc-controls">
            <div class="api-doc-search">
                <i class="ri-search-line api-doc-search-icon"></i>
                <input type="text" id="endpoint-search" placeholder="Search endpoints by name, method, URL, or description...">
            </div>
            <button class="theme-toggle" id="theme-toggle">
                <i class="ri-moon-line" id="theme-icon"></i>
                <span id="theme-text">Dark Mode</span>
            </button>
        </div>

        <!-- Getting Started -->
        <div class="info-card">
            <h3><i class="ri-information-line"></i> Getting Started</h3>
            <p><strong>Authentication:</strong> Most endpoints require authentication. Include your token in the Authorization header:</p>
            <code>Authorization: Bearer YOUR_ACCESS_TOKEN</code>
            <p class="mt-3"><strong>Rate Limits:</strong> API requests are rate-limited. Contact support for higher limits.</p>
        </div>

        <!-- Tabs -->
        <div class="api-doc-tabs" id="api-tabs">
            @foreach($endpoints as $groupIndex => $group)
            <button class="api-doc-tab {{ $groupIndex === 0 ? 'active' : '' }}" 
                    data-tab="group-{{ $groupIndex }}">
                <i class="ri-{{ $group['icon'] }}-line"></i>
                {{ $group['group'] }}
            </button>
            @endforeach
        </div>

        <!-- Tab Contents -->
        @foreach($endpoints as $groupIndex => $group)
        <div class="api-doc-tab-content {{ $groupIndex === 0 ? 'active' : '' }}" 
             id="group-{{ $groupIndex }}"
             data-group="{{ strtolower($group['group']) }}">
            <div class="mb-4">
                <h2 class="mb-2">{{ $group['group'] }}</h2>
                <p class="text-muted">{{ $group['description'] }}</p>
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
                        <div>
                            <h3 class="endpoint-title">{{ $endpoint['name'] }}</h3>
                            <p class="endpoint-description">{{ $endpoint['description'] }}</p>
                        </div>
                        <button class="endpoint-toggle" type="button">
                            <i class="ri-arrow-down-s-line"></i>
                        </button>
                    </div>

                    <div class="endpoint-content">
                        <!-- Headers -->
                        @if(!empty($endpoint['headers']))
                        <div class="endpoint-section">
                            <h4 class="section-title">Headers</h4>
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
                                        <td><code style="font-size: 0.8rem;">{{ $headerData['example'] ?? '' }}</code></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <!-- Query Parameters -->
                        @if(!empty($endpoint['query_parameters']))
                        <div class="endpoint-section">
                            <h4 class="section-title">Query Parameters</h4>
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
                        <div class="endpoint-section">
                            <h4 class="section-title">Request Parameters</h4>
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

                        <!-- Request Payload -->
                        @if($endpoint['payload_example'])
                        <div class="endpoint-section">
                            <h4 class="section-title">Request Payload</h4>
                            <div class="code-block">
                                <button class="copy-button" onclick="copyToClipboard('payload-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                    <i class="ri-file-copy-line"></i> Copy
                                </button>
                                <pre id="payload-{{ $groupIndex }}-{{ $endpointIndex }}"><code>{{ is_string($endpoint['payload_example']) ? $endpoint['payload_example'] : json_encode($endpoint['payload_example'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                            </div>
                        </div>
                        @endif

                        <!-- Success Response -->
                        @if($endpoint['success_response'])
                        <div class="endpoint-section">
                            <h4 class="section-title text-success">Success Response</h4>
                            <div class="code-block">
                                <button class="copy-button" onclick="copyToClipboard('success-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                    <i class="ri-file-copy-line"></i> Copy
                                </button>
                                <pre id="success-{{ $groupIndex }}-{{ $endpointIndex }}"><code>{{ is_string($endpoint['success_response']) ? $endpoint['success_response'] : json_encode($endpoint['success_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                            </div>
                        </div>
                        @endif

                        <!-- Error Response -->
                        @if($endpoint['error_response'])
                        <div class="endpoint-section">
                            <h4 class="section-title text-danger">Error Response</h4>
                            <div class="code-block">
                                <button class="copy-button" onclick="copyToClipboard('error-{{ $groupIndex }}-{{ $endpointIndex }}', this)">
                                    <i class="ri-file-copy-line"></i> Copy
                                </button>
                                <pre id="error-{{ $groupIndex }}-{{ $endpointIndex }}"><code>{{ is_string($endpoint['error_response']) ? $endpoint['error_response'] : json_encode($endpoint['error_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                            </div>
                        </div>
                        @endif

                        <!-- Status Codes -->
                        @if(!empty($endpoint['status_codes']))
                        <div class="endpoint-section">
                            <h4 class="section-title">Status Codes</h4>
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
    </div>

    <!-- Back to Top -->
    <button class="back-to-top" id="back-to-top" onclick="scrollToTop()">
        <i class="ri-arrow-up-line"></i>
    </button>
</div>
@endsection

@push('scripts')
<script>
(function($) {
    'use strict';

    // Theme Toggle
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const themeText = document.getElementById('theme-text');
    const wrapper = document.querySelector('.api-doc-wrapper');

    // Load saved theme
    const savedTheme = localStorage.getItem('api-doc-theme') || 'light';
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
            localStorage.setItem('api-doc-theme', 'light');
        } else {
            wrapper.setAttribute('data-theme', 'dark');
            themeIcon.className = 'ri-sun-line';
            themeText.textContent = 'Light Mode';
            localStorage.setItem('api-doc-theme', 'dark');
        }
    });

    // Tab Switching
    document.querySelectorAll('.api-doc-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active from all tabs and contents
            document.querySelectorAll('.api-doc-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.api-doc-tab-content').forEach(c => c.classList.remove('active'));
            
            // Add active to clicked tab and content
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
            
            // Scroll to top of content
            document.getElementById(targetTab).scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // Endpoint Toggle
    window.toggleEndpoint = function(header) {
        const card = header.closest('.endpoint-card');
        const content = card.querySelector('.endpoint-content');
        const toggle = card.querySelector('.endpoint-toggle');
        
        if (content.classList.contains('expanded')) {
            content.classList.remove('expanded');
            toggle.classList.remove('expanded');
        } else {
            content.classList.add('expanded');
            toggle.classList.add('expanded');
        }
    };

    // Search Functionality
    $('#endpoint-search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase().trim();
        let visibleCount = 0;

        if (searchTerm === '') {
            $('.endpoint-card, .api-doc-tab-content').show();
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
                $card.closest('.api-doc-tab-content').show();
                visibleCount++;
            } else {
                $card.hide();
            }
        });

        // Hide groups with no visible endpoints
        $('.api-doc-tab-content').each(function() {
            const $content = $(this);
            const visibleInGroup = $content.find('.endpoint-card:visible').length;
            if (visibleInGroup === 0 && searchTerm !== '') {
                $content.hide();
            }
        });

        // Show/hide no results
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

    // Back to Top
    window.scrollToTop = function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    window.addEventListener('scroll', function() {
        const backToTop = document.getElementById('back-to-top');
        if (window.pageYOffset > 300) {
            backToTop.classList.add('visible');
        } else {
            backToTop.classList.remove('visible');
        }
    });

})(jQuery);
</script>
@endpush
