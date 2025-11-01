@extends('layouts.dashboard')

@section('title', 'API Documentation')

@push('styles')
<style>
    .api-doc-container {
        background: #f8f9fa;
        border-radius: 16px;
        padding: 2.5rem;
        min-height: 100vh;
    }
    
    .api-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
    }
    
    .api-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .search-box {
        position: relative;
        margin-bottom: 2rem;
    }
    
    .search-box input {
        padding-left: 3rem;
        border-radius: 12px;
        border: 2px solid #e9ecef;
        font-size: 1rem;
        transition: all 0.3s;
    }
    
    .search-box input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    
    .endpoint-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    
    .endpoint-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .endpoint-header {
        padding: 1.5rem;
        border-left: 5px solid;
        background: #ffffff;
        cursor: pointer;
    }
    
    .endpoint-header.collapsed {
        border-bottom: 1px solid #e9ecef;
    }
    
    .endpoint-header.method-get {
        border-left-color: #61affe;
    }
    
    .endpoint-header.method-post {
        border-left-color: #49cc90;
    }
    
    .endpoint-header.method-put {
        border-left-color: #fca130;
    }
    
    .endpoint-header.method-delete {
        border-left-color: #f93e3e;
    }
    
    .method-badge {
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: white;
        display: inline-block;
        text-transform: uppercase;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .method-get { 
        background: linear-gradient(135deg, #61affe 0%, #4fa8e8 100%);
    }
    
    .method-post { 
        background: linear-gradient(135deg, #49cc90 0%, #3db877 100%);
    }
    
    .method-put { 
        background: linear-gradient(135deg, #fca130 0%, #f99e1a 100%);
    }
    
    .method-delete { 
        background: linear-gradient(135deg, #f93e3e 0%, #e82c2c 100%);
    }
    
    .endpoint-url {
        background: #f8f9fa;
        padding: 0.875rem 1.25rem;
        border-radius: 10px;
        font-family: 'Courier New', 'Fira Code', Consolas, monospace;
        font-size: 14px;
        border: 1px solid #e9ecef;
        margin-top: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    
    .endpoint-url code {
        background: transparent;
        color: #495057;
        padding: 0;
        font-family: inherit;
    }
    
    .copy-url-btn {
        padding: 4px 12px;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .copy-url-btn:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
    }
    
    .code-block-wrapper {
        position: relative;
        background: #1e1e1e;
        border-radius: 12px;
        overflow: hidden;
        margin: 1rem 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .code-block-header {
        background: #252526;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #3e3e42;
        font-size: 12px;
        color: #cccccc;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    .code-block-header .badge {
        font-size: 10px;
        padding: 4px 8px;
    }
    
    .copy-btn {
        background: #3e3e42;
        border: none;
        color: #cccccc;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .copy-btn:hover {
        background: #4e4e52;
        color: white;
    }
    
    .copy-btn.copied {
        background: #4caf50;
        color: white;
    }
    
    .code-block {
        padding: 1.5rem;
        margin: 0;
        overflow-x: auto;
        font-family: 'Courier New', 'Fira Code', Consolas, monospace;
    }
    
    .code-block pre {
        margin: 0;
        color: #d4d4d4;
        font-size: 13px;
        line-height: 1.6;
        white-space: pre;
    }
    
    .status-badge {
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        display: inline-block;
    }
    
    .status-200 { background: #d4edda; color: #155724; }
    .status-201 { background: #d1ecf1; color: #0c5460; }
    .status-400 { background: #f8d7da; color: #721c24; }
    .status-401 { background: #fff3cd; color: #856404; }
    .status-403 { background: #fef0cd; color: #856404; }
    .status-404 { background: #f8d7da; color: #721c24; }
    .status-409 { background: #f5c6cb; color: #721c24; }
    .status-422 { background: #f8d7da; color: #721c24; }
    
    .param-table {
        font-size: 14px;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .param-table th {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
        padding: 1rem;
    }
    
    .param-table td {
        padding: 0.875rem 1rem;
        vertical-align: middle;
    }
    
    .param-table code {
        background: #f1f3f5;
        padding: 4px 8px;
        border-radius: 6px;
        color: #c92a2a;
        font-size: 13px;
        font-weight: 500;
        font-family: 'Courier New', 'Fira Code', Consolas, monospace;
    }
    
    .nav-tabs {
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 1.5rem;
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.25rem;
        border-radius: 8px 8px 0 0;
        margin-right: 0.5rem;
        transition: all 0.2s;
    }
    
    .nav-tabs .nav-link:hover {
        color: #495057;
        background: #f8f9fa;
    }
    
    .nav-tabs .nav-link.active {
        color: #667eea;
        border-bottom: 3px solid #667eea;
        background: transparent;
        font-weight: 600;
    }
    
    .info-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
    }
    
    .info-box code {
        background: rgba(255,255,255,0.25);
        padding: 6px 12px;
        border-radius: 6px;
        color: white;
        font-size: 14px;
        font-weight: 500;
    }
    
    .group-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .group-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }
    
    .collapse-icon {
        transition: transform 0.3s;
        margin-left: auto;
        cursor: pointer;
    }
    
    .collapse-icon.collapsed {
        transform: rotate(-90deg);
    }
    
    .endpoint-description {
        color: #6c757d;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    
    .auth-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 8px 14px;
        background: #e7f3ff;
        border: 1px solid #b3d9ff;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
    }
    
    .auth-badge i {
        color: #0066cc;
    }
    
    .no-results {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }
    
    .no-results i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
<div class="api-doc-container">
    <!-- Header -->
    <div class="api-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="mb-2">📚 API Documentation</h1>
                <p class="mb-0 opacity-90">Complete API reference with interactive examples</p>
            </div>
            <div>
                <a href="{{ route('admin.api-documentation.download') }}" class="btn btn-light btn-lg" target="_blank">
                    <i class="ri-download-line me-2"></i> Download Postman Collection
                </a>
            </div>
        </div>
    </div>

    <!-- Search Box -->
    <div class="search-box">
        <i class="ri-search-line"></i>
        <input type="text" id="apiSearch" class="form-control form-control-lg" placeholder="Search APIs by name, method, or URL...">
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <div class="d-flex align-items-center mb-3">
            <i class="ri-information-line fs-20 me-2"></i>
            <h5 class="mb-0">API Base URL</h5>
        </div>
        <p class="mb-2 fs-16">
            <code>{{ config('app.url') }}/api</code>
        </p>
        <p class="mb-0 small opacity-90">
            All authenticated requests require a <code>Bearer</code> token in the Authorization header.
            Include the token like: <code>Authorization: Bearer {your_token}</code>
        </p>
    </div>

    <!-- API Groups -->
    <div id="apiGroups">
        @foreach($endpoints as $groupIndex => $group)
        <div class="api-group mb-5" data-group="{{ strtolower($group['group']) }}">
            <div class="group-header">
                <div class="group-icon">
                    <i class="ri-code-s-slash-line"></i>
                </div>
                <div class="flex-grow-1">
                    <h3 class="fw-bold mb-1">{{ $group['group'] }}</h3>
                    <p class="text-muted mb-0 small">{{ count($group['endpoints']) }} endpoints available</p>
                </div>
                <i class="ri-arrow-down-s-line collapse-icon fs-20 text-muted" onclick="toggleGroup({{ $groupIndex }})"></i>
            </div>
            
            <div class="group-content" id="group-{{ $groupIndex }}">
                @foreach($group['endpoints'] as $endpointIndex => $endpoint)
                <div class="endpoint-card" data-endpoint="{{ strtolower($endpoint['name'] . ' ' . $endpoint['method'] . ' ' . $endpoint['url']) }}">
                    <div class="endpoint-header method-{{ strtolower($endpoint['method']) }}" onclick="toggleEndpoint('{{ md5($endpoint['url']) }}')">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                            <div class="d-flex align-items-center gap-3 flex-grow-1">
                                <span class="method-badge method-{{ strtolower($endpoint['method']) }}">
                                    {{ $endpoint['method'] }}
                                </span>
                                <h4 class="mb-0 fw-bold">{{ $endpoint['name'] }}</h4>
                            </div>
                            <i class="ri-arrow-down-s-line collapse-icon-endpoint fs-20 text-muted" id="icon-{{ md5($endpoint['url']) }}"></i>
                        </div>
                        <div class="endpoint-url">
                            <code>{{ $endpoint['url'] }}</code>
                            <button class="copy-url-btn" onclick="copyToClipboard('{{ $endpoint['url'] }}', this, event)">
                                <i class="ri-file-copy-line me-1"></i> Copy URL
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body p-4 endpoint-content" id="content-{{ md5($endpoint['url']) }}" style="display: none;">
                        <p class="endpoint-description">{{ $endpoint['description'] }}</p>
                        
                        <!-- Authentication Info -->
                        <div class="mb-4 p-3 bg-light rounded-3">
                            <div class="auth-badge">
                                <i class="ri-shield-keyhole-line"></i>
                                <span>
                                    @if($endpoint['auth'] === 'None')
                                        No Authentication Required
                                    @else
                                        {{ $endpoint['auth'] }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#params-{{ md5($endpoint['url']) }}" type="button">
                                    <i class="ri-list-check me-1"></i> Parameters
                                </button>
                            </li>
                            @if(isset($endpoint['request_payload']) && $endpoint['request_payload'])
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#request-{{ md5($endpoint['url']) }}" type="button">
                                    <i class="ri-send-plane-line me-1"></i> Request
                                </button>
                            </li>
                            @endif
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#response-{{ md5($endpoint['url']) }}" type="button">
                                    <i class="ri-checkbox-circle-line me-1"></i> Response
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#error-{{ md5($endpoint['url']) }}" type="button">
                                    <i class="ri-error-warning-line me-1"></i> Errors
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content mt-4">
                            <!-- Parameters Tab -->
                            <div class="tab-pane fade show active" id="params-{{ md5($endpoint['url']) }}">
                                @if(!empty($endpoint['parameters']['required']) || !empty($endpoint['parameters']['optional']))
                                    @if(!empty($endpoint['parameters']['required']))
                                    <div class="mb-4">
                                        <h6 class="fw-bold text-danger mb-3">
                                            <i class="ri-asterisk me-1"></i> Required Parameters
                                        </h6>
                                        <div class="table-responsive">
                                            <table class="table param-table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th width="20%">Parameter</th>
                                                        <th width="15%">Type</th>
                                                        <th>Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($endpoint['parameters']['required'] as $param => $desc)
                                                    <tr>
                                                        <td><code>{{ $param }}</code></td>
                                                        <td><span class="badge bg-info">{{ explode(' - ', $desc)[0] }}</span></td>
                                                        <td>{{ isset(explode(' - ', $desc)[1]) ? explode(' - ', $desc)[1] : '' }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endif

                                    @if(!empty($endpoint['parameters']['optional']))
                                    <div>
                                        <h6 class="fw-bold text-muted mb-3">
                                            <i class="ri-subtract-line me-1"></i> Optional Parameters
                                        </h6>
                                        <div class="table-responsive">
                                            <table class="table param-table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th width="20%">Parameter</th>
                                                        <th width="15%">Type</th>
                                                        <th>Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($endpoint['parameters']['optional'] as $param => $desc)
                                                    <tr>
                                                        <td><code>{{ $param }}</code></td>
                                                        <td><span class="badge bg-secondary">{{ explode(' - ', $desc)[0] }}</span></td>
                                                        <td>{{ isset(explode(' - ', $desc)[1]) ? explode(' - ', $desc)[1] : '' }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endif
                                @else
                                <p class="text-muted">No parameters required for this endpoint.</p>
                                @endif
                            </div>

                            <!-- Request Example Tab -->
                            @if(isset($endpoint['request_payload']) && $endpoint['request_payload'])
                            <div class="tab-pane fade" id="request-{{ md5($endpoint['url']) }}">
                                @php
                                    $requestPayload = json_encode($endpoint['request_payload'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                                @endphp
                                <div class="code-block-wrapper">
                                    <div class="code-block-header">
                                        <span><i class="ri-code-s-slash-line me-1"></i> Request Payload</span>
                                        <button class="copy-btn" data-code="{{ htmlspecialchars($requestPayload, ENT_QUOTES, 'UTF-8') }}" onclick="copyCode(this)">
                                            <i class="ri-file-copy-line"></i> Copy
                                        </button>
                                    </div>
                                    <div class="code-block">
                                        <pre>{{ $requestPayload }}</pre>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Success Response Tab -->
                            <div class="tab-pane fade" id="response-{{ md5($endpoint['url']) }}">
                                @if(isset($endpoint['response']))
                                @php
                                    $statusCode = 200;
                                    if($endpoint['method'] === 'POST') {
                                        $statusCode = 201;
                                    } elseif($endpoint['method'] === 'PUT' || $endpoint['method'] === 'PATCH') {
                                        $statusCode = 200;
                                    }
                                    $responsePayload = json_encode($endpoint['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                                @endphp
                                <div class="mb-3">
                                    <span class="status-badge status-{{ $statusCode }}">HTTP {{ $statusCode }} {{ $statusCode === 201 ? 'Created' : 'OK' }}</span>
                                </div>
                                <div class="code-block-wrapper">
                                    <div class="code-block-header">
                                        <span><i class="ri-checkbox-circle-line me-1"></i> Success Response</span>
                                        <button class="copy-btn" data-code="{{ htmlspecialchars($responsePayload, ENT_QUOTES, 'UTF-8') }}" onclick="copyCode(this)">
                                            <i class="ri-file-copy-line"></i> Copy
                                        </button>
                                    </div>
                                    <div class="code-block">
                                        <pre>{{ $responsePayload }}</pre>
                                    </div>
                                </div>
                                @else
                                <p class="text-muted">No response example available for this endpoint.</p>
                                @endif
                            </div>

                            <!-- Error Response Tab -->
                            <div class="tab-pane fade" id="error-{{ md5($endpoint['url']) }}">
                                @php
                                    $errorResponses = [];
                                    if(isset($endpoint['error_response'])) {
                                        $errorResponses[] = $endpoint['error_response'];
                                    }
                                    $i = 2;
                                    while(isset($endpoint['error_response_' . $i])) {
                                        $errorResponses[] = $endpoint['error_response_' . $i];
                                        $i++;
                                    }
                                @endphp
                                
                                @if(!empty($errorResponses))
                                    @foreach($errorResponses as $index => $errorResponse)
                                    @php
                                        $errorPayload = json_encode($errorResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                                    @endphp
                                    <div class="mb-4 {{ $index > 0 ? 'mt-4' : '' }}">
                                        <div class="mb-3">
                                            <span class="status-badge status-{{ isset($errorResponse['status']) ? $errorResponse['status'] : '400' }}">
                                                HTTP {{ isset($errorResponse['status']) ? $errorResponse['status'] : '400' }}
                                            </span>
                                            @if(count($errorResponses) > 1)
                                            <span class="ms-2 text-muted small">Error Response {{ $index + 1 }}</span>
                                            @endif
                                        </div>
                                        <div class="code-block-wrapper">
                                            <div class="code-block-header">
                                                <span><i class="ri-error-warning-line me-1"></i> Error Response</span>
                                                <button class="copy-btn" data-code="{{ htmlspecialchars($errorPayload, ENT_QUOTES, 'UTF-8') }}" onclick="copyCode(this)">
                                                    <i class="ri-file-copy-line"></i> Copy
                                                </button>
                                            </div>
                                            <div class="code-block">
                                                <pre>{{ $errorPayload }}</pre>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                <p class="text-muted">No error examples available for this endpoint.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <!-- No Results -->
    <div id="noResults" class="no-results" style="display: none;">
        <i class="ri-search-line"></i>
        <h4>No APIs found</h4>
        <p>Try searching with different keywords</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Search functionality
        $('#apiSearch').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            let hasResults = false;
            
            $('.endpoint-card').each(function() {
                const endpointText = $(this).data('endpoint') || '';
                if (endpointText.includes(searchTerm) || searchTerm === '') {
                    $(this).show();
                    hasResults = true;
                } else {
                    $(this).hide();
                }
            });
            
            // Hide/show groups with no visible endpoints
            $('.api-group').each(function() {
                const visibleEndpoints = $(this).find('.endpoint-card:visible').length;
                if (visibleEndpoints === 0 && searchTerm !== '') {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
            
            // Show/hide no results message
            if (!hasResults && searchTerm !== '') {
                $('#noResults').show();
            } else {
                $('#noResults').hide();
            }
        });
    });

    function toggleGroup(groupIndex) {
        const $content = $('#group-' + groupIndex);
        const $icon = $(`.group-header:has(#group-${groupIndex})`).find('.collapse-icon');
        
        if ($content.is(':visible')) {
            $content.slideUp(300);
            $icon.addClass('collapsed');
        } else {
            $content.slideDown(300);
            $icon.removeClass('collapsed');
        }
    }

    function toggleEndpoint(endpointId) {
        const $content = $('#content-' + endpointId);
        const $icon = $('#icon-' + endpointId);
        
        if ($content.is(':visible')) {
            $content.slideUp(300);
            $icon.addClass('collapsed');
        } else {
            $content.slideDown(300);
            $icon.removeClass('collapsed');
        }
    }

    function copyCode(button) {
        const textToCopy = $(button).data('code') || $(button).closest('.code-block-wrapper').find('pre').text();
        
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(textToCopy).then(function() {
                const $btn = $(button);
                $btn.addClass('copied');
                $btn.html('<i class="ri-check-line"></i> Copied!');
                
                setTimeout(function() {
                    $btn.removeClass('copied');
                    $btn.html('<i class="ri-file-copy-line"></i> Copy');
                }, 2000);
            }).catch(function(err) {
                console.error('Failed to copy:', err);
                fallbackCopy(textToCopy, button);
            });
        } else {
            fallbackCopy(textToCopy, button);
        }
    }

    function fallbackCopy(text, button) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        textarea.style.left = '-9999px';
        document.body.appendChild(textarea);
        textarea.select();
        
        try {
            document.execCommand('copy');
            const $btn = $(button);
            $btn.addClass('copied');
            $btn.html('<i class="ri-check-line"></i> Copied!');
            
            setTimeout(function() {
                $btn.removeClass('copied');
                $btn.html('<i class="ri-file-copy-line"></i> Copy');
            }, 2000);
        } catch (err) {
            console.error('Fallback copy failed:', err);
            alert('Failed to copy. Please select and copy manually.');
        }
        
        document.body.removeChild(textarea);
    }

    function copyToClipboard(text, button, event) {
        event.stopPropagation();
        
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                const $btn = $(button);
                const originalHtml = $btn.html();
                $btn.html('<i class="ri-check-line me-1"></i> Copied!');
                $btn.css('background', '#4caf50').css('color', 'white');
                
                setTimeout(function() {
                    $btn.html(originalHtml);
                    $btn.css('background', '').css('color', '');
                }, 2000);
            }).catch(function(err) {
                console.error('Failed to copy:', err);
            });
        } else {
            fallbackCopy(text, button);
        }
    }

    // Initialize all endpoints as collapsed
    $(document).ready(function() {
        $('.endpoint-content').hide();
        $('.collapse-icon-endpoint').addClass('collapsed');
    });
</script>
@endpush
