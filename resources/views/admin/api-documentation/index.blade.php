@extends('layouts.dashboard')

@section('title', 'API Documentation')

@push('styles')
<style>
    .endpoint-card {
        border-left: 4px solid;
    }
    .endpoint-card.method-get {
        border-left-color: #61affe;
    }
    .endpoint-card.method-post {
        border-left-color: #49cc90;
    }
    .endpoint-card.method-put {
        border-left-color: #fca130;
    }
    .endpoint-card.method-delete {
        border-left-color: #f93e3e;
    }
    .method-badge {
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        color: white;
    }
    .method-get { background-color: #61affe; }
    .method-post { background-color: #49cc90; }
    .method-put { background-color: #fca130; }
    .method-delete { background-color: #f93e3e; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    API Documentation
                </div>
                <div>
                    <a href="{{ route('admin.api-documentation.download') }}" class="btn btn-primary btn-sm" target="_blank">
                        <i class="ri-download-line me-1"></i> Download Postman Collection
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info" role="alert">
                    <h6 class="alert-heading"><i class="ri-information-line me-2"></i> API Base URL</h6>
                    <p class="mb-0">
                        <strong>{{ config('app.url') }}/api</strong>
                    </p>
                    <hr>
                    <p class="mb-0 small">
                        All authenticated requests require a <code>Bearer</code> token in the Authorization header.
                        The token is returned after successful login.
                    </p>
                </div>

                @foreach($endpoints as $group)
                <div class="mb-5">
                    <h4 class="fw-semibold mb-4">{{ $group['group'] }}</h4>
                    
                    @foreach($group['endpoints'] as $endpoint)
                    <div class="card endpoint-card method-{{ strtolower($endpoint['method']) }} mb-4">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="method-badge method-{{ strtolower($endpoint['method']) }}">
                                        {{ $endpoint['method'] }}
                                    </span>
                                    <h5 class="mb-0">{{ $endpoint['name'] }}</h5>
                                </div>
                                <code class="text-primary">{{ $endpoint['url'] }}</code>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">{{ $endpoint['description'] }}</p>
                            
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-2">
                                    <i class="ri-shield-keyhole-line me-1"></i> Authentication
                                </h6>
                                <p class="mb-0">
                                    @if($endpoint['auth'] === 'None')
                                        <span class="badge bg-secondary">No Authentication Required</span>
                                    @else
                                        <span class="badge bg-primary">{{ $endpoint['auth'] }}</span>
                                    @endif
                                </p>
                            </div>

                            @if(!empty($endpoint['parameters']['required']) || !empty($endpoint['parameters']['optional']))
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3">
                                    <i class="ri-input-method-line me-1"></i> Parameters
                                </h6>
                                
                                @if(!empty($endpoint['parameters']['required']))
                                <div class="mb-3">
                                    <h6 class="text-danger mb-2">Required Parameters</h6>
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Parameter</th>
                                                <th>Type</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($endpoint['parameters']['required'] as $param => $desc)
                                            <tr>
                                                <td><code>{{ $param }}</code></td>
                                                <td>{{ explode(' - ', $desc)[0] }}</td>
                                                <td>{{ isset(explode(' - ', $desc)[1]) ? explode(' - ', $desc)[1] : '' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif

                                @if(!empty($endpoint['parameters']['optional']))
                                <div>
                                    <h6 class="text-muted mb-2">Optional Parameters</h6>
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Parameter</th>
                                                <th>Type</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($endpoint['parameters']['optional'] as $param => $desc)
                                            <tr>
                                                <td><code>{{ $param }}</code></td>
                                                <td>{{ explode(' - ', $desc)[0] }}</td>
                                                <td>{{ isset(explode(' - ', $desc)[1]) ? explode(' - ', $desc)[1] : '' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>
                            @endif

                            <div>
                                <h6 class="fw-semibold mb-2">
                                    <i class="ri-code-s-slash-line me-1"></i> Response Example
                                </h6>
                                <pre class="bg-light p-3 rounded" style="max-height: 300px; overflow-y: auto;"><code>{{ json_encode($endpoint['response'], JSON_PRETTY_PRINT) }}</code></pre>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
