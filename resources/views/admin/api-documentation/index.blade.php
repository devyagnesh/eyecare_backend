@extends('layouts.dashboard')

@section('title', 'API Documentation')

@push('styles')
<style>
    .code-block {
        max-height: 500px;
        overflow-y: auto;
    }
    .endpoint-item {
        transition: box-shadow 0.2s ease;
    }
    .endpoint-item:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div class="row">
    <!-- Header Card -->
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2 class="mb-1">📚 API Documentation</h2>
                        <p class="mb-0 opacity-90">Complete API reference for Eyecare Management System</p>
                        <p class="mb-0 mt-2 opacity-75 fs-12">
                            <i class="bx bx-calendar me-1"></i>Last Updated: {{ $lastUpdated ?? 'N/A' }} | 
                            <i class="bx bx-code-alt me-1"></i>Version: {{ $apiVersion ?? '1.0.0' }}
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="{{ route('admin.api-documentation.download') }}" class="btn btn-light" target="_blank">
                            <i class="bx bx-download me-2"></i>Download Postman Collection
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- API Base URL Info -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="avatar avatar-md bg-info-transparent me-3">
                        <i class="bx bx-info-circle fs-20 text-info"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-2 fw-semibold">API Base URL</h5>
                        <p class="mb-2">
                            <code class="bg-light px-2 py-1 rounded">{{ config('app.url') }}/api</code>
                        </p>
                        <p class="text-muted mb-0 fs-12">
                            Authenticated requests require: <code class="bg-light px-1 py-0 rounded fs-11">Authorization: Bearer {token}</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Box -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bx bx-search"></i>
                    </span>
                    <input 
                        type="text" 
                        id="apiSearch" 
                        class="form-control" 
                        placeholder="Search APIs by name, method, or URL..."
                    >
                </div>
            </div>
        </div>
    </div>

    <!-- Module Tabs and Content -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs flex-wrap" id="moduleTabs" role="tablist">
                    @foreach($endpoints as $index => $group)
                        @php
                            $moduleId = 'module-' . strtolower(str_replace([' ', '_'], ['-', '-'], $group['group']));
                            $iconKey = strtolower($group['group']);
                        @endphp
                        <li class="nav-item" role="presentation">
                            <button 
                                class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                id="{{ $moduleId }}-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#{{ $moduleId }}" 
                                type="button" 
                                role="tab"
                            >
                                @if($iconKey === 'authentication')
                                    <i class="bx bx-lock me-1"></i>
                                @elseif($iconKey === 'users')
                                    <i class="bx bx-user me-1"></i>
                                @elseif($iconKey === 'roles')
                                    <i class="bx bx-shield me-1"></i>
                                @elseif($iconKey === 'permissions')
                                    <i class="bx bx-check-circle me-1"></i>
                                @else
                                    <i class="bx bx-code-alt me-1"></i>
                                @endif
                                {{ $group['group'] }}
                                <span class="badge bg-secondary ms-2">{{ count($group['endpoints']) }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="moduleTabsContent">
                    @foreach($endpoints as $index => $group)
                        @php
                            $moduleId = 'module-' . strtolower(str_replace([' ', '_'], ['-', '-'], $group['group']));
                        @endphp
                        <div 
                            class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                            id="{{ $moduleId }}" 
                            role="tabpanel"
                        >
                            <div class="mb-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md bg-primary-transparent me-3">
                                        <i class="bx bx-code-alt fs-20 text-primary"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1 fw-semibold">{{ $group['group'] }}</h5>
                                        <p class="text-muted mb-0 fs-12">{{ count($group['endpoints']) }} endpoints available</p>
                                    </div>
                                </div>
                            </div>

                            <div class="endpoints-list">
                                @foreach($group['endpoints'] as $endpoint)
                                <div class="card border mb-4 endpoint-item" 
                                     data-endpoint="{{ strtolower($endpoint['name'] . ' ' . $endpoint['method'] . ' ' . $endpoint['url']) }}">
                                    <div class="card-body">
                                        <!-- Endpoint Header -->
                                        <div class="mb-3">
                                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                                @php
                                                    $methodColors = [
                                                        'GET' => 'bg-primary',
                                                        'POST' => 'bg-success',
                                                        'PUT' => 'bg-warning',
                                                        'PATCH' => 'bg-warning',
                                                        'DELETE' => 'bg-danger'
                                                    ];
                                                    $color = $methodColors[$endpoint['method']] ?? 'bg-secondary';
                                                @endphp
                                                <span class="badge {{ $color }} text-white">{{ $endpoint['method'] }}</span>
                                                <h6 class="mb-0 fw-semibold">{{ $endpoint['name'] }}</h6>
                                                @if($endpoint['auth'] !== 'None')
                                                    <span class="badge bg-info-transparent">
                                                        <i class="bx bx-lock me-1"></i>Auth Required
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary-transparent">No Auth</span>
                                                @endif
                                            </div>
                                            <code class="d-block bg-light p-2 rounded mb-2 fs-13">{{ $endpoint['url'] }}</code>
                                            @if($endpoint['description'])
                                                <p class="text-muted mb-0 fs-13">{{ $endpoint['description'] }}</p>
                                            @endif
                                        </div>

                                        <!-- Request/Response Tabs -->
                                        <ul class="nav nav-tabs mb-3" role="tablist">
                                            @if(isset($endpoint['request_payload']) && $endpoint['request_payload'])
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-bs-toggle="tab" href="#req-{{ md5($endpoint['url']) }}">Request</a>
                                                </li>
                                            @endif
                                            @if(isset($endpoint['response']) && $endpoint['response'])
                                                <li class="nav-item">
                                                    <a class="nav-link {{ !isset($endpoint['request_payload']) ? 'active' : '' }}" data-bs-toggle="tab" href="#res-{{ md5($endpoint['url']) }}">Success Response</a>
                                                </li>
                                            @endif
                                            @if(isset($endpoint['error_response']) && $endpoint['error_response'])
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#err-{{ md5($endpoint['url']) }}">Error Response</a>
                                                </li>
                                            @endif
                                        </ul>

                                        <div class="tab-content">
                                            @if(isset($endpoint['request_payload']) && $endpoint['request_payload'])
                                            <div class="tab-pane fade show active" id="req-{{ md5($endpoint['url']) }}">
                                                <div class="border rounded">
                                                    <div class="bg-light p-2 border-bottom d-flex justify-content-between align-items-center">
                                                        <span class="fw-semibold fs-13">Request Payload</span>
                                                        <button onclick="copyToClipboard(this)" 
                                                                data-code="{{ htmlspecialchars(json_encode($endpoint['request_payload'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), ENT_QUOTES) }}"
                                                                class="btn btn-sm btn-light"
                                                                title="Copy to clipboard">
                                                            <i class="bx bx-copy"></i>
                                                        </button>
                                                    </div>
                                                    <div class="bg-dark p-3 code-block">
                                                        <pre class="text-white mb-0 fs-12"><code>{{ json_encode($endpoint['request_payload'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                                    </div>
                                                </div>

                                                @if(isset($endpoint['parameters']) && (isset($endpoint['parameters']['required']) || isset($endpoint['parameters']['optional'])))
                                                <div class="row mt-3">
                                                    @if(isset($endpoint['parameters']['required']) && !empty($endpoint['parameters']['required']))
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card border-danger">
                                                            <div class="card-header bg-danger-transparent">
                                                                <h6 class="mb-0 text-danger">
                                                                    <i class="bx bx-error-circle me-1"></i>Required Parameters
                                                                </h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <ul class="list-unstyled mb-0">
                                                                    @foreach($endpoint['parameters']['required'] as $param => $desc)
                                                                    <li class="mb-2">
                                                                        <code class="text-primary">{{ $param }}</code>
                                                                        <span class="text-muted fs-13"> - {{ $desc }}</span>
                                                                    </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if(isset($endpoint['parameters']['optional']) && !empty($endpoint['parameters']['optional']))
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card border">
                                                            <div class="card-header bg-light">
                                                                <h6 class="mb-0">
                                                                    <i class="bx bx-check-circle me-1"></i>Optional Parameters
                                                                </h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <ul class="list-unstyled mb-0">
                                                                    @foreach($endpoint['parameters']['optional'] as $param => $desc)
                                                                    <li class="mb-2">
                                                                        <code class="text-primary">{{ $param }}</code>
                                                                        <span class="text-muted fs-13"> - {{ $desc }}</span>
                                                                    </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endif
                                            </div>
                                            @endif

                                            @if(isset($endpoint['response']) && $endpoint['response'])
                                            @php
                                                $statusCode = $endpoint['response']['status'] ?? ($endpoint['method'] === 'POST' ? 201 : 200);
                                                $responseData = $endpoint['response'];
                                                unset($responseData['status']);
                                                $responseJson = json_encode($responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                                            @endphp
                                            <div class="tab-pane fade {{ !isset($endpoint['request_payload']) ? 'show active' : '' }}" id="res-{{ md5($endpoint['url']) }}">
                                                <div class="border border-success rounded">
                                                    <div class="bg-success-transparent p-2 border-bottom d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <span class="badge bg-success me-2">HTTP {{ $statusCode }}</span>
                                                            <span class="fw-semibold fs-13">Success Response</span>
                                                        </div>
                                                        <button onclick="copyToClipboard(this)" 
                                                                data-code="{{ htmlspecialchars($responseJson, ENT_QUOTES) }}"
                                                                class="btn btn-sm btn-success"
                                                                title="Copy to clipboard">
                                                            <i class="bx bx-copy"></i>
                                                        </button>
                                                    </div>
                                                    <div class="bg-dark p-3 code-block">
                                                        <pre class="text-white mb-0 fs-12"><code>{{ $responseJson }}</code></pre>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if(isset($endpoint['error_response']) && $endpoint['error_response'])
                                            @php
                                                $errorStatus = $endpoint['error_response']['status'] ?? 400;
                                                $errorJson = json_encode($endpoint['error_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                                            @endphp
                                            <div class="tab-pane fade" id="err-{{ md5($endpoint['url']) }}">
                                                <div class="border border-danger rounded">
                                                    <div class="bg-danger-transparent p-2 border-bottom d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <span class="badge bg-danger me-2">HTTP {{ $errorStatus }}</span>
                                                            <span class="fw-semibold fs-13">Error Response</span>
                                                        </div>
                                                        <button onclick="copyToClipboard(this)" 
                                                                data-code="{{ htmlspecialchars($errorJson, ENT_QUOTES) }}"
                                                                class="btn btn-sm btn-danger"
                                                                title="Copy to clipboard">
                                                            <i class="bx bx-copy"></i>
                                                        </button>
                                                    </div>
                                                    <div class="bg-dark p-3 code-block">
                                                        <pre class="text-white mb-0 fs-12"><code>{{ $errorJson }}</code></pre>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="col-12" style="display: none;">
        <div class="card text-center">
            <div class="card-body py-5">
                <i class="bx bx-search fs-48 text-muted mb-3 d-block"></i>
                <h5 class="mb-2">No APIs found</h5>
                <p class="text-muted mb-0">Try searching with different keywords</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Copy to clipboard function
    function copyToClipboard(button) {
        let textToCopy = button.getAttribute('data-code') || '';
        
        // Decode HTML entities if present
        if (textToCopy) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = textToCopy;
            textToCopy = tempDiv.textContent || tempDiv.innerText || textToCopy;
        }
        
        if (!textToCopy) {
            const codeElement = button.closest('.border').querySelector('pre code');
            if (codeElement) {
                // Get plain text content, not HTML
                textToCopy = codeElement.textContent || codeElement.innerText;
                // Replace any non-breaking spaces with regular spaces
                textToCopy = textToCopy.replace(/\u00A0/g, ' ').replace(/&nbsp;/g, ' ');
            }
        } else {
            // Replace any non-breaking spaces with regular spaces
            textToCopy = textToCopy.replace(/\u00A0/g, ' ').replace(/&nbsp;/g, ' ');
        }

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(textToCopy).then(function() {
                showCopySuccess(button);
            }).catch(function(err) {
                console.error('Failed to copy:', err);
                fallbackCopy(textToCopy, button);
            });
        } else {
            fallbackCopy(textToCopy, button);
        }
    }

    function showCopySuccess(button) {
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="bx bx-check"></i>';
        button.classList.add('btn-success');
        button.classList.remove('btn-light', 'btn-danger');
        button.setAttribute('title', 'Copied!');
        
        setTimeout(function() {
            button.innerHTML = originalHTML;
            button.classList.remove('btn-success');
            button.setAttribute('title', 'Copy to clipboard');
        }, 2000);
    }

    function fallbackCopy(text, button) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        textarea.style.left = '-9999px';
        document.body.appendChild(textarea);
        
        try {
            textarea.focus();
            textarea.select();
            const successful = document.execCommand('copy');
            if (successful) {
                showCopySuccess(button);
            }
        } catch (err) {
            console.error('Fallback copy failed:', err);
            if (typeof showErrorToast !== 'undefined') {
                showErrorToast('Failed to copy. Please select and copy manually.');
            } else {
                alert('Failed to copy. Please select and copy manually.');
            }
        } finally {
            document.body.removeChild(textarea);
        }
    }

    // Handle manual text selection to clean HTML entities
    document.addEventListener('copy', function(e) {
        const selection = window.getSelection();
        if (selection && selection.toString().length > 0) {
            let selectedText = selection.toString();
            
            // Check if selection is from a code block
            let isFromCodeBlock = false;
            if (selection.anchorNode) {
                let node = selection.anchorNode;
                // If it's a text node, get the parent element
                if (node.nodeType === Node.TEXT_NODE) {
                    node = node.parentElement;
                }
                // Check if we're inside a code block
                if (node) {
                    const codeBlock = node.closest ? node.closest('pre code, .code-block, pre') : null;
                    isFromCodeBlock = !!codeBlock;
                }
            }
            
            if (isFromCodeBlock) {
                // Replace non-breaking spaces and HTML entities with regular spaces
                selectedText = selectedText.replace(/\u00A0/g, ' ').replace(/&nbsp;/gi, ' ');
                // Replace other common HTML entities
                selectedText = selectedText.replace(/&amp;/g, '&')
                                           .replace(/&lt;/g, '<')
                                           .replace(/&gt;/g, '>')
                                           .replace(/&quot;/g, '"')
                                           .replace(/&#39;/g, "'");
                
                e.clipboardData.setData('text/plain', selectedText);
                e.preventDefault();
            }
        }
    });

    // Search functionality
    $(document).ready(function() {
        const searchInput = $('#apiSearch');
        if (searchInput.length) {
            let searchTimeout;
            
            searchInput.on('input', function() {
                clearTimeout(searchTimeout);
                const term = $(this).val().toLowerCase().trim();
                searchTimeout = setTimeout(function() {
                    performSearch(term);
                }, 300);
            });
        }
    });

    function performSearch(searchTerm) {
        let hasResults = false;
        
        $('.endpoint-item').each(function() {
            const endpointText = $(this).data('endpoint') || '';
            if (endpointText.includes(searchTerm) || searchTerm === '') {
                $(this).show();
                hasResults = true;
            } else {
                $(this).hide();
            }
        });

        const noResults = $('#noResults');
        const apiContent = $('.card-header-tabs').closest('.card');
        
        if (!hasResults && searchTerm !== '') {
            noResults.show();
            apiContent.hide();
        } else {
            noResults.hide();
            apiContent.show();
        }
    }
</script>
@endpush
