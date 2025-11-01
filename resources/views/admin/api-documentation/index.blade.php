@extends('layouts.dashboard')

@section('title', 'API Documentation')

@section('content')
<style>
    /* Custom styles for professional tabs */
    .module-tab {
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .module-tab.active {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .endpoint-tab {
        position: relative;
        transition: all 0.2s ease;
    }
    
    .endpoint-tab.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background: currentColor;
        animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
        from {
            transform: scaleX(0);
        }
        to {
            transform: scaleX(1);
        }
    }
    
    .code-block {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .code-block::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .code-block::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 4px;
    }
    
    .code-block::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
    }
    
    .code-block::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    
    .fade-enter-active, .fade-leave-active {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    
    .fade-enter-from {
        opacity: 0;
        transform: translateY(-10px);
    }
    
    .fade-leave-to {
        opacity: 0;
        transform: translateY(10px);
    }
</style>

<div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
    <!-- Header Card -->
    <div class="card bg-gradient-to-r from-primary to-accent text-white">
        <div class="flex flex-col items-start justify-between p-6 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-2xl font-bold">📚 API Documentation</h2>
                <p class="mt-1 text-sm opacity-90">Complete API reference for Eyecare Management System</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.api-documentation.download') }}" class="btn bg-white/20 font-medium text-white hover:bg-white/30 focus:bg-white/30 active:bg-white/40 border-white/30 shadow-lg shadow-white/10" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download Postman Collection
                </a>
            </div>
        </div>
    </div>

    <!-- API Base URL Info -->
    <div class="card shadow-sm">
        <div class="p-4 sm:p-5">
            <div class="flex items-start space-x-3">
                <div class="flex size-10 items-center justify-center rounded-lg bg-info/10 text-info shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-slate-700 dark:text-navy-100">API Base URL</h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-navy-300">
                        <code class="rounded bg-slate-100 px-2 py-1 text-sm text-primary dark:bg-navy-700 dark:text-accent font-mono">{{ config('app.url') }}/api</code>
                    </p>
                    <p class="mt-2 text-xs text-slate-500 dark:text-navy-400">
                        Authenticated requests require: <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs dark:bg-navy-700 font-mono">Authorization: Bearer {token}</code>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Box -->
    <div class="card shadow-sm">
        <div class="p-4 sm:p-5">
            <label class="block">
                <span class="relative flex">
                    <input type="text" 
                           id="apiSearch" 
                           class="form-input peer w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 pl-10 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent transition-colors" 
                           placeholder="Search APIs by name, method, or URL..." />
                    <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                </span>
            </label>
        </div>
    </div>

    <!-- Module Tabs and Content -->
    <div x-data="{ 
        activeModule: '{{ strtolower(str_replace([' ', '_'], ['-', '-'], $endpoints[0]['group'] ?? '')) }}',
        init() {
            // Scroll to top when module changes
            this.$watch('activeModule', () => {
                this.$nextTick(() => {
                    const cardElement = this.$el;
                    if (cardElement) {
                        cardElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
        }
    }" class="card shadow-sm">
        <!-- Module Tabs Navigation -->
        <div class="border-b border-slate-200 dark:border-navy-500 bg-slate-50/50 dark:bg-navy-800/30">
            <div class="flex flex-wrap gap-2 p-4 sm:p-5 sm:gap-3 overflow-x-auto">
                @foreach($endpoints as $index => $group)
                    @php
                        $moduleId = strtolower(str_replace([' ', '_'], ['-', '-'], $group['group']));
                        $iconKey = strtolower($group['group']);
                    @endphp
                    <button @click="activeModule = '{{ $moduleId }}'" 
                            :class="activeModule === '{{ $moduleId }}' 
                                ? 'module-tab active bg-primary text-white border-primary shadow-md' 
                                : 'module-tab bg-white text-slate-700 border-slate-200 hover:bg-slate-50 hover:border-slate-300 dark:bg-navy-700 dark:text-navy-100 dark:border-navy-500 dark:hover:bg-navy-600'"
                            class="px-4 py-2.5 rounded-lg border text-sm font-medium transition-all duration-300 flex items-center space-x-2 min-w-max">
                        @if($iconKey === 'authentication')
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        @elseif($iconKey === 'users')
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        @elseif($iconKey === 'roles')
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        @elseif($iconKey === 'permissions')
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        @endif
                        <span>{{ $group['group'] }}</span>
                        <span class="badge px-2 py-0.5 text-xs font-semibold rounded-full" 
                              :class="activeModule === '{{ $moduleId }}' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600 dark:bg-navy-600 dark:text-navy-100'">
                            {{ count($group['endpoints']) }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Module Content -->
        <div class="p-4 sm:p-5">
            @foreach($endpoints as $group)
                @php
                    $moduleId = strtolower(str_replace([' ', '_'], ['-', '-'], $group['group']));
                @endphp
                <div x-show="activeModule === '{{ $moduleId }}'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform translate-y-4"
                     class="space-y-6">
                    <!-- Module Header -->
                    <div class="flex items-center space-x-3 pb-4 border-b border-slate-200 dark:border-navy-500">
                        <div class="flex size-12 items-center justify-center rounded-lg bg-primary/10 text-primary shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-700 dark:text-navy-100">{{ $group['group'] }}</h3>
                            <p class="text-xs text-slate-400 dark:text-navy-300">{{ count($group['endpoints']) }} endpoints available</p>
                        </div>
                    </div>

                    <!-- Endpoints List -->
                    <div class="space-y-6">
                        @foreach($group['endpoints'] as $endpoint)
                        <div class="endpoint-item rounded-lg border border-slate-200 dark:border-navy-500 p-5 shadow-sm hover:shadow-md transition-shadow duration-200" 
                             data-endpoint="{{ strtolower($endpoint['name'] . ' ' . $endpoint['method'] . ' ' . $endpoint['url']) }}">
                            <!-- Endpoint Header -->
                            <div class="mb-4">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                                            @php
                                                $methodColors = [
                                                    'GET' => 'bg-primary',
                                                    'POST' => 'bg-success',
                                                    'PUT' => 'bg-warning',
                                                    'PATCH' => 'bg-warning',
                                                    'DELETE' => 'bg-error'
                                                ];
                                                $color = $methodColors[$endpoint['method']] ?? 'bg-slate-500';
                                            @endphp
                                            <span class="badge {{ $color }} text-white font-semibold px-3 py-1 text-xs shadow-sm">{{ $endpoint['method'] }}</span>
                                            <h4 class="text-base font-semibold text-slate-700 dark:text-navy-100">{{ $endpoint['name'] }}</h4>
                                            @if($endpoint['auth'] !== 'None')
                                            <span class="badge bg-info/10 text-info border border-info/20 text-xs">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                Auth Required
                                            </span>
                                            @else
                                            <span class="badge bg-slate-150 text-slate-600 dark:bg-navy-500 dark:text-navy-100 text-xs">No Auth</span>
                                            @endif
                                        </div>
                                        <code class="block text-sm text-slate-600 dark:text-navy-300 font-mono mt-2 p-2 rounded bg-slate-50 dark:bg-navy-800 border border-slate-200 dark:border-navy-500">{{ $endpoint['url'] }}</code>
                                        @if($endpoint['description'])
                                        <p class="mt-2 text-sm text-slate-600 dark:text-navy-300 leading-relaxed">{{ $endpoint['description'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Request/Response Tabs -->
                            <div x-data="{ activeTab: 'request' }" class="mt-4">
                                <!-- Tab Buttons -->
                                <div class="flex space-x-1 border-b border-slate-200 dark:border-navy-500">
                                    @if(isset($endpoint['request_payload']) && $endpoint['request_payload'])
                                    <button @click="activeTab = 'request'" 
                                            :class="activeTab === 'request' ? 'endpoint-tab active border-b-2 border-primary text-primary dark:text-accent font-semibold' : 'endpoint-tab text-slate-600 dark:text-navy-300 hover:text-primary dark:hover:text-accent'"
                                            class="px-4 py-2.5 text-sm font-medium transition-all duration-200 relative">
                                        Request
                                    </button>
                                    @endif
                                    @if(isset($endpoint['response']) && $endpoint['response'])
                                    <button @click="activeTab = 'success'" 
                                            :class="activeTab === 'success' ? 'endpoint-tab active border-b-2 border-success text-success font-semibold' : 'endpoint-tab text-slate-600 dark:text-navy-300 hover:text-success'"
                                            class="px-4 py-2.5 text-sm font-medium transition-all duration-200 relative">
                                        Success Response
                                    </button>
                                    @endif
                                    @if(isset($endpoint['error_response']) && $endpoint['error_response'])
                                    <button @click="activeTab = 'error'" 
                                            :class="activeTab === 'error' ? 'endpoint-tab active border-b-2 border-error text-error font-semibold' : 'endpoint-tab text-slate-600 dark:text-navy-300 hover:text-error'"
                                            class="px-4 py-2.5 text-sm font-medium transition-all duration-200 relative">
                                        Error Response
                                    </button>
                                    @endif
                                </div>

                                <!-- Tab Content -->
                                <div class="mt-4">
                                    <!-- Request Tab -->
                                    @if(isset($endpoint['request_payload']) && $endpoint['request_payload'])
                                    <div x-show="activeTab === 'request'" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="space-y-4">
                                        <div class="rounded-lg border border-slate-200 dark:border-navy-500 overflow-hidden shadow-sm">
                                            <div class="flex items-center justify-between bg-slate-50 dark:bg-navy-700 px-4 py-3 border-b border-slate-200 dark:border-navy-500">
                                                <span class="text-sm font-semibold text-slate-700 dark:text-navy-100">Request Payload</span>
                                                <button onclick="copyToClipboard(this)" 
                                                        data-code="{{ htmlspecialchars(json_encode($endpoint['request_payload'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), ENT_QUOTES) }}"
                                                        class="btn size-7 rounded-lg p-0 hover:bg-slate-200 dark:hover:bg-navy-600 text-slate-600 dark:text-navy-300 transition-colors"
                                                        title="Copy to clipboard">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="bg-slate-900 dark:bg-navy-900 p-4 overflow-x-auto code-block">
                                                <pre class="text-xs text-slate-300 font-mono leading-relaxed"><code>{{ json_encode($endpoint['request_payload'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                            </div>
                                        </div>

                                        <!-- Parameters -->
                                        @if(isset($endpoint['parameters']) && (isset($endpoint['parameters']['required']) || isset($endpoint['parameters']['optional'])))
                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                            @if(isset($endpoint['parameters']['required']) && !empty($endpoint['parameters']['required']))
                                            <div class="rounded-lg border border-error/20 dark:border-error/30 p-4 bg-error/5 dark:bg-error/10">
                                                <h5 class="mb-3 text-sm font-semibold text-error flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Required Parameters
                                                </h5>
                                                <ul class="space-y-2">
                                                    @foreach($endpoint['parameters']['required'] as $param => $desc)
                                                    <li class="text-sm">
                                                        <code class="text-primary dark:text-accent font-semibold">{{ $param }}</code>
                                                        <span class="text-slate-600 dark:text-navy-300"> - {{ $desc }}</span>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif

                                            @if(isset($endpoint['parameters']['optional']) && !empty($endpoint['parameters']['optional']))
                                            <div class="rounded-lg border border-slate-200 dark:border-navy-500 p-4 bg-slate-50 dark:bg-navy-800/50">
                                                <h5 class="mb-3 text-sm font-semibold text-slate-600 dark:text-navy-300 flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Optional Parameters
                                                </h5>
                                                <ul class="space-y-2">
                                                    @foreach($endpoint['parameters']['optional'] as $param => $desc)
                                                    <li class="text-sm">
                                                        <code class="text-primary dark:text-accent font-semibold">{{ $param }}</code>
                                                        <span class="text-slate-600 dark:text-navy-300"> - {{ $desc }}</span>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                    @endif

                                    <!-- Success Response Tab -->
                                    @if(isset($endpoint['response']) && $endpoint['response'])
                                    <div x-show="activeTab === 'success'" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="space-y-4">
                                        @php
                                            $statusCode = $endpoint['response']['status'] ?? ($endpoint['method'] === 'POST' ? 201 : ($endpoint['method'] === 'PUT' || $endpoint['method'] === 'PATCH' ? 200 : 200));
                                            $responseData = $endpoint['response'];
                                            unset($responseData['status']);
                                            $responseJson = json_encode($responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                                        @endphp
                                        <div class="rounded-lg border border-success/20 dark:border-success/30 overflow-hidden shadow-sm">
                                            <div class="flex items-center justify-between bg-success/10 dark:bg-success/20 px-4 py-3 border-b border-success/20 dark:border-success/30">
                                                <div class="flex items-center space-x-2">
                                                    <span class="badge bg-success text-white text-xs font-semibold shadow-sm">HTTP {{ $statusCode }}</span>
                                                    <span class="text-sm font-semibold text-slate-700 dark:text-navy-100">Success Response</span>
                                                </div>
                                                <button onclick="copyToClipboard(this)" 
                                                        data-code="{{ htmlspecialchars($responseJson, ENT_QUOTES) }}"
                                                        class="btn size-7 rounded-lg p-0 hover:bg-success/20 text-success transition-colors"
                                                        title="Copy to clipboard">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="bg-slate-900 dark:bg-navy-900 p-4 overflow-x-auto code-block">
                                                <pre class="text-xs text-slate-300 font-mono leading-relaxed"><code>{{ $responseJson }}</code></pre>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Error Response Tab -->
                                    @if(isset($endpoint['error_response']) && $endpoint['error_response'])
                                    <div x-show="activeTab === 'error'" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="space-y-4">
                                        @php
                                            $errorStatus = $endpoint['error_response']['status'] ?? 400;
                                            $errorJson = json_encode($endpoint['error_response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                                        @endphp
                                        <div class="rounded-lg border border-error/20 dark:border-error/30 overflow-hidden shadow-sm">
                                            <div class="flex items-center justify-between bg-error/10 dark:bg-error/20 px-4 py-3 border-b border-error/20 dark:border-error/30">
                                                <div class="flex items-center space-x-2">
                                                    <span class="badge bg-error text-white text-xs font-semibold shadow-sm">HTTP {{ $errorStatus }}</span>
                                                    <span class="text-sm font-semibold text-slate-700 dark:text-navy-100">Error Response</span>
                                                </div>
                                                <button onclick="copyToClipboard(this)" 
                                                        data-code="{{ htmlspecialchars($errorJson, ENT_QUOTES) }}"
                                                        class="btn size-7 rounded-lg p-0 hover:bg-error/20 text-error transition-colors"
                                                        title="Copy to clipboard">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="bg-slate-900 dark:bg-navy-900 p-4 overflow-x-auto code-block">
                                                <pre class="text-xs text-slate-300 font-mono leading-relaxed"><code>{{ $errorJson }}</code></pre>
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

    <!-- No Results Message -->
    <div id="noResults" class="card text-center shadow-sm" style="display: none;">
        <div class="p-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto size-16 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <h4 class="mt-4 text-lg font-semibold text-slate-700 dark:text-navy-100">No APIs found</h4>
            <p class="mt-2 text-sm text-slate-400 dark:text-navy-300">Try searching with different keywords</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Copy to clipboard function with enhanced error handling
    function copyToClipboard(button) {
        let textToCopy = '';
        
        // Try to get from data-code attribute first
        if (button.getAttribute('data-code')) {
            textToCopy = button.getAttribute('data-code');
        } else {
            // Fallback: get from code element
            const codeElement = button.closest('.rounded-lg').querySelector('pre code');
            if (codeElement) {
                textToCopy = codeElement.textContent || codeElement.innerText;
            } else {
                return;
            }
        }

        // Decode HTML entities if needed
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = textToCopy;
        textToCopy = tempDiv.textContent || tempDiv.innerText || textToCopy;

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
        button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
        button.classList.add('text-success');
        button.setAttribute('title', 'Copied!');
        button.style.pointerEvents = 'none';
        
        setTimeout(function() {
            button.innerHTML = originalHTML;
            button.classList.remove('text-success');
            button.setAttribute('title', 'Copy to clipboard');
            button.style.pointerEvents = '';
        }, 2000);
    }

    function fallbackCopy(text, button) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        textarea.style.left = '-9999px';
        textarea.style.top = '0';
        document.body.appendChild(textarea);
        
        try {
            textarea.focus();
            textarea.select();
            const successful = document.execCommand('copy');
            
            if (successful) {
                showCopySuccess(button);
            } else {
                throw new Error('Copy command failed');
            }
        } catch (err) {
            console.error('Fallback copy failed:', err);
            alert('Failed to copy. Please select and copy manually.');
        } finally {
            document.body.removeChild(textarea);
        }
    }

    // Search functionality with debounce
    let searchTimeout;
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('apiSearch');
        if (!searchInput) return;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(this.value);
            }, 300);
        });

        searchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            performSearch(this.value);
        });
    });

    function performSearch(searchTerm) {
        const term = searchTerm.toLowerCase().trim();
        let hasResults = false;

        // Hide/show endpoint items
        document.querySelectorAll('.endpoint-item').forEach(function(item) {
            const endpointText = item.getAttribute('data-endpoint') || '';
            if (endpointText.includes(term) || term === '') {
                item.style.display = 'block';
                hasResults = true;
            } else {
                item.style.display = 'none';
            }
        });

        // Show/hide no results message
        const noResults = document.getElementById('noResults');
        const apiTables = document.querySelector('.card[x-data]');
        if (!hasResults && term !== '') {
            if (noResults) noResults.style.display = 'block';
            if (apiTables) apiTables.style.display = 'none';
        } else {
            if (noResults) noResults.style.display = 'none';
            if (apiTables) apiTables.style.display = 'block';
        }
    }
</script>
@endpush
