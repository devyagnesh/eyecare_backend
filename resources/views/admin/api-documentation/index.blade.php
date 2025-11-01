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
    
    .module-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }
    
    .module-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 1.5rem 2rem;
        border-bottom: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .module-icon {
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
    
    .api-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    
    .api-table thead {
        background: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .api-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .api-table td {
        padding: 1rem;
        vertical-align: top;
        border-bottom: 1px solid #e9ecef;
    }
    
    .api-table tbody tr:hover {
        background: #f8f9fa;
    }
    
    .api-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .method-badge {
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: white;
        display: inline-block;
        text-transform: uppercase;
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
    
    .endpoint-name {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.25rem;
    }
    
    .endpoint-url {
        font-family: 'Courier New', 'Fira Code', Consolas, monospace;
        font-size: 12px;
        color: #6c757d;
        margin-top: 0.5rem;
    }
    
    .endpoint-description {
        font-size: 12px;
        color: #6c757d;
        margin-top: 0.5rem;
    }
    
    .auth-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 4px 8px;
        background: #e7f3ff;
        border: 1px solid #b3d9ff;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
        margin-top: 0.5rem;
    }
    
    .code-example {
        position: relative;
        background: #1e1e1e;
        border-radius: 8px;
        overflow: hidden;
        margin: 0.5rem 0;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .code-example-header {
        background: #252526;
        padding: 0.5rem 0.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 10px;
        color: #cccccc;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 5;
    }
    
    .copy-btn-small {
        background: #3e3e42;
        border: none;
        color: #cccccc;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 10px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 3px;
    }
    
    .copy-btn-small:hover {
        background: #4e4e52;
        color: white;
    }
    
    .copy-btn-small.copied {
        background: #4caf50;
        color: white;
    }
    
    .code-example pre {
        padding: 1rem;
        margin: 0;
        color: #d4d4d4;
        font-family: 'Courier New', 'Fira Code', Consolas, monospace;
        font-size: 12px;
        line-height: 1.5;
        white-space: pre;
        overflow-x: auto;
    }
    
    .status-badge-small {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 0.5rem;
    }
    
    .status-200 { background: #d4edda; color: #155724; }
    .status-201 { background: #d1ecf1; color: #0c5460; }
    .status-400 { background: #f8d7da; color: #721c24; }
    .status-401 { background: #fff3cd; color: #856404; }
    .status-403 { background: #fef0cd; color: #856404; }
    .status-404 { background: #f8d7da; color: #721c24; }
    .status-409 { background: #f5c6cb; color: #721c24; }
    .status-422 { background: #f8d7da; color: #721c24; }
    
    .no-example {
        color: #adb5bd;
        font-size: 12px;
        font-style: italic;
        padding: 1rem;
        text-align: center;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .col-method { width: 100px; }
    .col-endpoint { width: 250px; min-width: 200px; }
    .col-request { width: 300px; min-width: 250px; }
    .col-response { width: 300px; min-width: 250px; }
    .col-error { width: 300px; min-width: 250px; }
    
    .no-results {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
        background: white;
        border-radius: 16px;
    }
    
    .no-results i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    /* Scrollbar styling for code examples */
    .code-example::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .code-example::-webkit-scrollbar-track {
        background: #1e1e1e;
    }
    
    .code-example::-webkit-scrollbar-thumb {
        background: #555;
        border-radius: 4px;
    }
    
    .code-example::-webkit-scrollbar-thumb:hover {
        background: #777;
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
                <p class="mb-0 opacity-90">Complete API reference in tabular format</p>
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

    <!-- API Tables by Module -->
    <div id="apiTables">
        @foreach($endpoints as $group)
        <div class="module-section" data-module="{{ strtolower($group['group']) }}">
            <div class="module-header">
                <div class="module-icon">
                    <i class="ri-code-s-slash-line"></i>
                </div>
                <div class="flex-grow-1">
                    <h3 class="fw-bold mb-1">{{ $group['group'] }}</h3>
                    <p class="text-muted mb-0 small">{{ count($group['endpoints']) }} endpoints</p>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="api-table">
                    <thead>
                        <tr>
                            <th class="col-method">Method</th>
                            <th class="col-endpoint">Endpoint</th>
                            <th class="col-request">Request Example</th>
                            <th class="col-response">Success Response Example</th>
                            <th class="col-error">Error Response Example</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group['endpoints'] as $endpoint)
                        <tr data-endpoint="{{ strtolower($endpoint['name'] . ' ' . $endpoint['method'] . ' ' . $endpoint['url']) }}">
                            <!-- Method Column -->
                            <td>
                                <span class="method-badge method-{{ strtolower($endpoint['method']) }}">
                                    {{ $endpoint['method'] }}
                                </span>
                            </td>
                            
                            <!-- Endpoint Column -->
                            <td>
                                <div class="endpoint-name">{{ $endpoint['name'] }}</div>
                                <div class="endpoint-url">{{ $endpoint['url'] }}</div>
                                <div class="endpoint-description">{{ $endpoint['description'] }}</div>
                                <div class="auth-badge">
                                    <i class="ri-shield-keyhole-line"></i>
                                    <span>
                                        @if($endpoint['auth'] === 'None')
                                            No Auth
                                        @else
                                            {{ str_replace('Bearer Token (Required)', 'Auth Required', $endpoint['auth']) }}
                                        @endif
                                    </span>
                                </div>
                            </td>
                            
                            <!-- Request Example Column -->
                            <td>
                                @php
                                    $requestExamples = [];
                                    if(isset($endpoint['request_example_1'])) {
                                        $requestExamples[] = $endpoint['request_example_1'];
                                    }
                                    if(isset($endpoint['request_example_2'])) {
                                        $requestExamples[] = $endpoint['request_example_2'];
                                    }
                                    if(isset($endpoint['request_example_3'])) {
                                        $requestExamples[] = $endpoint['request_example_3'];
                                    }
                                @endphp
                                
                                @if(isset($endpoint['request_payload']) && $endpoint['request_payload'])
                                    @php
                                        $requestPayload = json_encode($endpoint['request_payload'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                                    @endphp
                                    <div class="code-example">
                                        <div class="code-example-header">
                                            <span><i class="ri-send-plane-line me-1"></i> Request Body</span>
                                            <button class="copy-btn-small" data-code="{{ htmlspecialchars($requestPayload, ENT_QUOTES, 'UTF-8') }}" onclick="copyCode(this)">
                                                <i class="ri-file-copy-line"></i> Copy
                                            </button>
                                        </div>
                                        <pre>{{ $requestPayload }}</pre>
                                    </div>
                                @elseif(!empty($requestExamples))
                                    @foreach($requestExamples as $index => $example)
                                        <div class="mb-2 {{ $index > 0 ? 'mt-3' : '' }}">
                                            <div class="code-example">
                                                <div class="code-example-header">
                                                    <span><i class="ri-link me-1"></i> Example {{ $index + 1 }}</span>
                                                    <button class="copy-btn-small" data-code="{{ htmlspecialchars($example, ENT_QUOTES, 'UTF-8') }}" onclick="copyCode(this)">
                                                        <i class="ri-file-copy-line"></i> Copy
                                                    </button>
                                                </div>
                                                <pre>{{ $example }}</pre>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="no-example">
                                        <i class="ri-subtract-line"></i> No request body
                                    </div>
                                @endif
                            </td>
                            
                            <!-- Success Response Example Column -->
                            <td>
                                @php
                                    $successResponses = [];
                                    if(isset($endpoint['response'])) {
                                        $successResponses[] = $endpoint['response'];
                                    }
                                    if(isset($endpoint['response_2'])) {
                                        $successResponses[] = $endpoint['response_2'];
                                    }
                                    if(isset($endpoint['response_3'])) {
                                        $successResponses[] = $endpoint['response_3'];
                                    }
                                @endphp
                                
                                @if(!empty($successResponses))
                                    @foreach($successResponses as $index => $response)
                                        @php
                                            $statusCode = isset($response['status']) ? $response['status'] : 200;
                                            if(!isset($response['status'])) {
                                                if($endpoint['method'] === 'POST') {
                                                    $statusCode = 201;
                                                } elseif($endpoint['method'] === 'PUT' || $endpoint['method'] === 'PATCH') {
                                                    $statusCode = 200;
                                                }
                                            }
                                            $responsePayload = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                                        @endphp
                                        <div class="mb-2 {{ $index > 0 ? 'mt-3' : '' }}">
                                            <div class="status-badge-small status-{{ $statusCode }}">
                                                HTTP {{ $statusCode }}
                                                @if(count($successResponses) > 1 && isset($response['description']))
                                                    <span style="font-size: 10px; opacity: 0.8;"> - {{ $response['description'] }}</span>
                                                @elseif(count($successResponses) > 1)
                                                    <span style="font-size: 10px; opacity: 0.8;"> ({{ $index + 1 }})</span>
                                                @endif
                                            </div>
                                            <div class="code-example">
                                                <div class="code-example-header">
                                                    <span><i class="ri-checkbox-circle-line me-1"></i> Success</span>
                                                    <button class="copy-btn-small" data-code="{{ htmlspecialchars($responsePayload, ENT_QUOTES, 'UTF-8') }}" onclick="copyCode(this)">
                                                        <i class="ri-file-copy-line"></i> Copy
                                                    </button>
                                                </div>
                                                <pre>{{ $responsePayload }}</pre>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="no-example">
                                        <i class="ri-subtract-line"></i> No example
                                    </div>
                                @endif
                            </td>
                            
                            <!-- Error Response Example Column -->
                            <td>
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
                                        <div class="mb-2 {{ $index > 0 ? 'mt-3' : '' }}">
                                            @if(count($errorResponses) > 1)
                                                <div class="status-badge-small status-{{ isset($errorResponse['status']) ? $errorResponse['status'] : '400' }}">
                                                    HTTP {{ isset($errorResponse['status']) ? $errorResponse['status'] : '400' }} ({{ $index + 1 }})
                                                </div>
                                            @else
                                                <div class="status-badge-small status-{{ isset($errorResponse['status']) ? $errorResponse['status'] : '400' }}">
                                                    HTTP {{ isset($errorResponse['status']) ? $errorResponse['status'] : '400' }}
                                                </div>
                                            @endif
                                            <div class="code-example">
                                                <div class="code-example-header">
                                                    <span><i class="ri-error-warning-line me-1"></i> Error</span>
                                                    <button class="copy-btn-small" data-code="{{ htmlspecialchars($errorPayload, ENT_QUOTES, 'UTF-8') }}" onclick="copyCode(this)">
                                                        <i class="ri-file-copy-line"></i> Copy
                                                    </button>
                                                </div>
                                                <pre>{{ $errorPayload }}</pre>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="no-example">
                                        <i class="ri-subtract-line"></i> No examples
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
            
            $('.api-table tbody tr').each(function() {
                const endpointText = $(this).data('endpoint') || '';
                if (endpointText.includes(searchTerm) || searchTerm === '') {
                    $(this).show();
                    hasResults = true;
                } else {
                    $(this).hide();
                }
            });
            
            // Hide/show modules with no visible endpoints
            $('.module-section').each(function() {
                const visibleRows = $(this).find('tbody tr:visible').length;
                if (visibleRows === 0 && searchTerm !== '') {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
            
            // Show/hide no results message
            if (!hasResults && searchTerm !== '') {
                $('#noResults').show();
                $('#apiTables').hide();
            } else {
                $('#noResults').hide();
                $('#apiTables').show();
            }
        });
    });

    function copyCode(button) {
        const textToCopy = $(button).data('code') || $(button).closest('.code-example').find('pre').text();
        
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
</script>
@endpush
