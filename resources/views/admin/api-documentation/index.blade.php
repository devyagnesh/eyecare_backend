@extends('layouts.dashboard')

@section('title', 'API Documentation')

@push('styles')
<style>
    .api-doc-container {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 2rem;
    }
    
    .endpoint-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .endpoint-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .endpoint-header {
        padding: 1.5rem;
        border-left: 4px solid;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
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
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: white;
        display: inline-block;
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
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-size: 14px;
        border: 1px solid #e9ecef;
    }
    
    .code-block {
        background: #2d2d2d;
        border-radius: 8px;
        padding: 1.5rem;
        margin: 0;
        overflow-x: auto;
        position: relative;
    }
    
    .code-block code {
        color: #f8f8f2;
        font-family: 'Courier New', Consolas, monospace;
        font-size: 13px;
        line-height: 1.6;
    }
    
    .code-block-header {
        background: #3d3d3d;
        padding: 0.75rem 1rem;
        border-radius: 8px 8px 0 0;
        margin: -1.5rem -1.5rem 1rem -1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        font-size: 12px;
        color: #a8a8a8;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
    }
    
    .code-block-header .btn-link {
        padding: 2px 8px;
        text-decoration: none;
        border: none;
    }
    
    .code-block-header .btn-link:hover {
        color: #ffffff !important;
        text-decoration: none;
    }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .status-200 { background: #d4edda; color: #155724; }
    .status-201 { background: #d1ecf1; color: #0c5460; }
    .status-400 { background: #f8d7da; color: #721c24; }
    .status-401 { background: #fff3cd; color: #856404; }
    .status-404 { background: #f8d7da; color: #721c24; }
    .status-422 { background: #f8d7da; color: #721c24; }
    
    .param-table {
        font-size: 14px;
    }
    
    .param-table th {
        background: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }
    
    .param-table code {
        background: #f1f3f5;
        padding: 2px 6px;
        border-radius: 4px;
        color: #c92a2a;
        font-size: 13px;
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
    }
    
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom: 2px solid #0d6efd;
        background: transparent;
    }
    
    .info-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .info-box code {
        background: rgba(255,255,255,0.2);
        padding: 4px 8px;
        border-radius: 4px;
        color: white;
    }
    
    .section-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #dee2e6, transparent);
        margin: 2rem 0;
    }
</style>
@endpush

@section('content')
<div class="api-doc-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-2">API Documentation</h2>
            <p class="text-muted mb-0">Complete API reference with request/response examples</p>
        </div>
        <div>
            <a href="{{ route('admin.api-documentation.download') }}" class="btn btn-primary" target="_blank">
                <i class="ri-download-line me-2"></i> Download Postman Collection
            </a>
        </div>
    </div>

    <div class="info-box">
        <div class="d-flex align-items-center mb-3">
            <i class="ri-information-line fs-20 me-2"></i>
            <h5 class="mb-0">API Base URL</h5>
        </div>
        <p class="mb-2 fs-16">
            <code>{{ config('app.url') }}/api</code>
        </p>
        <p class="mb-0 small opacity-75">
            All authenticated requests require a <code>Bearer</code> token in the Authorization header.
            Include the token like: <code>Authorization: Bearer {your_token}</code>
        </p>
    </div>

    @foreach($endpoints as $group)
    <div class="mb-5">
        <div class="d-flex align-items-center mb-4">
            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                <i class="ri-key-2-line text-primary fs-20"></i>
            </div>
            <div>
                <h3 class="fw-bold mb-1">{{ $group['group'] }}</h3>
                <p class="text-muted mb-0 small">{{ count($group['endpoints']) }} endpoints</p>
            </div>
        </div>
        
        @foreach($group['endpoints'] as $endpoint)
        <div class="endpoint-card">
            <div class="endpoint-header method-{{ strtolower($endpoint['method']) }}">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <span class="method-badge method-{{ strtolower($endpoint['method']) }}">
                            {{ $endpoint['method'] }}
                        </span>
                        <h4 class="mb-0 fw-bold">{{ $endpoint['name'] }}</h4>
                    </div>
                </div>
                <div class="endpoint-url">
                    {{ $endpoint['url'] }}
                </div>
            </div>
            
            <div class="card-body p-4">
                <p class="text-muted mb-4">{{ $endpoint['description'] }}</p>
                
                <!-- Authentication Info -->
                <div class="mb-4 p-3 bg-light rounded">
                    <div class="d-flex align-items-center mb-2">
                        <i class="ri-shield-keyhole-line text-primary me-2"></i>
                        <strong class="me-3">Authentication:</strong>
                        @if($endpoint['auth'] === 'None')
                            <span class="badge bg-secondary">No Authentication Required</span>
                        @else
                            <span class="badge bg-primary">{{ $endpoint['auth'] }}</span>
                        @endif
                    </div>
                </div>

                <!-- Tabs for Request/Response/Errors -->
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#params-{{ md5($endpoint['url']) }}" type="button">
                            <i class="ri-list-check me-1"></i> Parameters
                        </button>
                    </li>
                    @if(isset($endpoint['request_payload']) && $endpoint['request_payload'])
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#request-{{ md5($endpoint['url']) }}" type="button">
                            <i class="ri-send-plane-line me-1"></i> Request Example
                        </button>
                    </li>
                    @endif
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#response-{{ md5($endpoint['url']) }}" type="button">
                            <i class="ri-checkbox-circle-line me-1"></i> Success Response
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#error-{{ md5($endpoint['url']) }}" type="button">
                            <i class="ri-error-warning-line me-1"></i> Error Response
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
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
                        <div class="mb-3">
                            <span class="status-badge status-200">HTTP {{ $endpoint['method'] }}</span>
                            <span class="ms-2 text-muted small">Request Payload</span>
                        </div>
                        <div class="code-block">
                            <div class="code-block-header">
                                <i class="ri-code-s-slash-line me-1"></i>
                                JSON Request Body
                            </div>
                            <code>{{ json_encode($endpoint['request_payload'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code>
                        </div>
                    </div>
                    @endif

                    <!-- Success Response Tab -->
                    <div class="tab-pane fade" id="response-{{ md5($endpoint['url']) }}">
                        <div class="mb-3">
                            <span class="status-badge status-200">HTTP 200 OK</span>
                            <span class="ms-2 text-muted small">Success Response</span>
                        </div>
                        <div class="code-block">
                            <div class="code-block-header">
                                <i class="ri-checkbox-circle-line me-1"></i>
                                JSON Response
                            </div>
                            <code>{{ json_encode($endpoint['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code>
                        </div>
                    </div>

                    <!-- Error Response Tab -->
                    <div class="tab-pane fade" id="error-{{ md5($endpoint['url']) }}">
                        @if(isset($endpoint['error_response']))
                        <div class="mb-3">
                            <span class="status-badge status-{{ isset($endpoint['error_response']['status']) ? $endpoint['error_response']['status'] : '400' }}">
                                HTTP {{ isset($endpoint['error_response']['status']) ? $endpoint['error_response']['status'] : '400/401/422' }}
                            </span>
                            <span class="ms-2 text-muted small">Error Response</span>
                        </div>
                        <div class="code-block">
                            <div class="code-block-header">
                                <i class="ri-error-warning-line me-1"></i>
                                JSON Error Response
                            </div>
                            <code>{{ json_encode($endpoint['error_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code>
                        </div>
                        @else
                        <p class="text-muted">No error examples available for this endpoint.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Copy to clipboard functionality
        $('.code-block').each(function() {
            const $block = $(this);
            const $header = $block.find('.code-block-header');
            
            // Add copy button
            const $copyBtn = $('<button class="btn btn-sm btn-link text-white-50 ms-auto" style="font-size: 11px;"><i class="ri-file-copy-line me-1"></i> Copy</button>');
            $header.append($copyBtn);
            
            $copyBtn.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const code = $block.find('code').text();
                
                // Fallback for older browsers
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(code).then(function() {
                        $copyBtn.html('<i class="ri-check-line me-1"></i> Copied!');
                        setTimeout(function() {
                            $copyBtn.html('<i class="ri-file-copy-line me-1"></i> Copy');
                        }, 2000);
                    }).catch(function(err) {
                        console.error('Failed to copy:', err);
                    });
                } else {
                    // Fallback: create temporary textarea
                    const textarea = document.createElement('textarea');
                    textarea.value = code;
                    textarea.style.position = 'fixed';
                    textarea.style.opacity = '0';
                    document.body.appendChild(textarea);
                    textarea.select();
                    try {
                        document.execCommand('copy');
                        $copyBtn.html('<i class="ri-check-line me-1"></i> Copied!');
                        setTimeout(function() {
                            $copyBtn.html('<i class="ri-file-copy-line me-1"></i> Copy');
                        }, 2000);
                    } catch (err) {
                        console.error('Fallback copy failed:', err);
                    }
                    document.body.removeChild(textarea);
                }
            });
        });
    });
</script>
@endpush