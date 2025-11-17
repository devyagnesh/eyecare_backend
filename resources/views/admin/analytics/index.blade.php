@extends('layouts.dashboard')

@section('title', 'Analytics')

@section('page-header')
<div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <h1 class="page-title fw-medium fs-18 mb-2">Analytics Dashboard</h1>
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@endsection

@push('styles')
@if(file_exists(public_path('assets/libs/apexcharts/apexcharts.min.js')))
<!-- ApexCharts CSS is included in main theme -->
@endif
@if(file_exists(public_path('assets/libs/leaflet/leaflet.css')))
<link rel="stylesheet" href="{{ asset('assets/libs/leaflet/leaflet.css') }}">
@endif
<style>
    #user-location-map {
        height: 500px;
        width: 100%;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<!-- Start:: Filters -->
<div class="row mb-4">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.analytics.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $filters['start_date'] }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $filters['end_date'] }}">
                    </div>
                    <div class="col-md-2">
                        <label for="limit" class="form-label">Store Limit</label>
                        <input type="number" class="form-control" id="limit" name="limit" value="{{ $filters['limit'] }}" min="5" max="50">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="ri-filter-line me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.analytics.index') }}" class="btn btn-secondary">
                            <i class="ri-refresh-line me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End:: Filters -->

<!-- Start:: row-1 - Overview Cards -->
<div class="row">
    <!-- Signups Card -->
    <div class="col-xl-3">
        <div class="card custom-card main-card-item primary">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3 flex-wrap">
                    <div>
                        <span class="d-block mb-3 fw-medium">Total Signups</span>
                        <h3 class="fw-semibold lh-1 mb-0">{{ number_format($analytics['signups']['total']) }}</h3>
                    </div>
                    <div class="text-end">
                        <div class="mb-4">
                            <span class="avatar avatar-md bg-primary svg-white avatar-rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><circle cx="128" cy="96" r="64" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M31,216a112,112,0,0,1,194,0" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-muted fs-13">This month: {{ $analytics['signups']['this_month'] }}</span>
                    @if($analytics['signups']['growth'] > 0)
                        <span class="text-success fw-semibold"><i class="ti ti-arrow-narrow-up"></i>{{ abs($analytics['signups']['growth']) }}%</span>
                    @elseif($analytics['signups']['growth'] < 0)
                        <span class="text-danger fw-semibold"><i class="ti ti-arrow-narrow-down"></i>{{ abs($analytics['signups']['growth']) }}%</span>
                    @else
                        <span class="text-muted fw-semibold">0%</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stores Card -->
    <div class="col-xl-3">
        <div class="card custom-card main-card-item">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3 flex-wrap">
                    <div>
                        <span class="d-block mb-3 fw-medium">Total Stores</span>
                        <h3 class="fw-semibold lh-1 mb-0">{{ number_format($analytics['stores']['total']) }}</h3>
                    </div>
                    <div class="text-end">
                        <div class="mb-4">
                            <span class="avatar avatar-md bg-secondary svg-white avatar-rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M32,208V64a8,8,0,0,1,8-8H216a8,8,0,0,1,8,8V208a8,8,0,0,1-8,8H40A8,8,0,0,1,32,208Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="32" y1="96" x2="224" y2="96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-muted fs-13">This month: {{ $analytics['stores']['this_month'] }}</span>
                    @if($analytics['stores']['growth'] > 0)
                        <span class="text-success fw-semibold"><i class="ti ti-arrow-narrow-up"></i>{{ abs($analytics['stores']['growth']) }}%</span>
                    @elseif($analytics['stores']['growth'] < 0)
                        <span class="text-danger fw-semibold"><i class="ti ti-arrow-narrow-down"></i>{{ abs($analytics['stores']['growth']) }}%</span>
                    @else
                        <span class="text-muted fw-semibold">0%</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Spam Accounts Card -->
    <div class="col-xl-3">
        <div class="card custom-card main-card-item">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3 flex-wrap">
                    <div>
                        <span class="d-block mb-3 fw-medium">Spam Accounts</span>
                        <h3 class="fw-semibold lh-1 mb-0">{{ number_format($analytics['spam']['total']) }}</h3>
                    </div>
                    <div class="text-end">
                        <div class="mb-4">
                            <span class="avatar avatar-md bg-danger svg-white avatar-rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><circle cx="128" cy="128" r="96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="160" y1="96" x2="96" y2="160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="160" y1="160" x2="96" y2="96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-muted fs-13">{{ $analytics['spam']['percentage'] }}% of total users</span>
                    <span class="text-muted fs-13">This month: {{ $analytics['spam']['this_month'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Stores Card -->
    <div class="col-xl-3">
        <div class="card custom-card main-card-item">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3 flex-wrap">
                    <div>
                        <span class="d-block mb-3 fw-medium">Active Stores</span>
                        <h3 class="fw-semibold lh-1 mb-0">{{ $analytics['performance']->where('customers_count', '>', 0)->count() }}</h3>
                    </div>
                    <div class="text-end">
                        <div class="mb-4">
                            <span class="avatar avatar-md bg-success svg-white avatar-rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><polyline points="172 104 113.3 160 84 132" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><circle cx="128" cy="128" r="96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-muted fs-13">Stores with customers</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End:: row-1 -->

<!-- Start:: row-2 - Charts -->
<div class="row">
    <div class="col-xl-6">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Signups Trend</div>
            </div>
            <div class="card-body">
                <div id="signups-chart" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Stores Created Trend</div>
            </div>
            <div class="card-body">
                <div id="stores-chart" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>
</div>
<!-- End:: row-2 -->

<!-- Start:: row-3 - Spam Detection Info & Chart -->
<div class="row">
    <div class="col-xl-6">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Spam Accounts Trend</div>
            </div>
            <div class="card-body">
                <div id="spam-chart" style="min-height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Spam Detection Criteria</div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6 class="alert-heading mb-3">Automatic Spam Detection Rules:</h6>
                    <ul class="mb-0 ps-3">
                        <li>Email not verified after <strong>7 days</strong> (+2 points)</li>
                        <li>No store created after <strong>14 days</strong> (+3 points)</li>
                        <li>Suspicious name patterns (test, demo, fake, etc.) (+2 points)</li>
                        <li>Suspicious email domains (tempmail, guerrillamail, etc.) (+3 points)</li>
                        <li>Name too short (less than 3 characters) (+2 points)</li>
                        <li>No login activity for <strong>30 days</strong> (+1 point)</li>
                        <li>No device registered after <strong>3 days</strong> (+1 point)</li>
                        <li>Multiple accounts from same IP (more than 3 in 24 hours) (+4 points)</li>
                    </ul>
                    <hr class="my-3">
                    <p class="mb-0"><strong>Threshold:</strong> Users with spam score ≥ 5 are automatically marked as spam.</p>
                    <p class="mb-0 mt-2"><small class="text-muted">Note: Admins can manually mark/unmark users as spam from the user edit page.</small></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End:: row-3 -->

<!-- Start:: row-4 - Store Performance Table -->
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Store Performance</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Store Name</th>
                                <th>Owner</th>
                                <th>Created At</th>
                                <th>Customers</th>
                                <th>Examinations</th>
                                <th>Orders</th>
                                <th>Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($analytics['performance'] as $store)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm avatar-rounded bg-primary text-white me-2">
                                            {{ substr($store['name'], 0, 1) }}
                                        </div>
                                        {{ $store['name'] }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-medium">{{ $store['user_name'] }}</div>
                                        <div class="text-muted fs-12">{{ $store['user_email'] }}</div>
                                    </div>
                                </td>
                                <td>{{ $store['created_at']->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-info-transparent">{{ $store['customers_count'] }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success-transparent">{{ $store['examinations_count'] }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-transparent">{{ $store['orders_count'] }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold">₹{{ number_format($store['total_revenue'], 2) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No stores found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End:: row-4 -->

<!-- Start:: row-5 - Spam Accounts Table -->
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Recent Spam Accounts</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Email Verified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($analytics['spam']['recent'] as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm avatar-rounded bg-danger text-white me-2">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role)
                                        <span class="badge bg-primary-transparent">{{ $user->role->name }}</span>
                                    @else
                                        <span class="badge bg-secondary-transparent">No Role</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success-transparent">Verified</span>
                                    @else
                                        <span class="badge bg-warning-transparent">Not Verified</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info-light">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No spam accounts found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End:: row-5 -->

<!-- Start:: row-6 - User Location Map -->
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">User Locations Map</div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-0">
                            <strong>Total Locations:</strong> {{ $analytics['location_data']['total_locations'] ?? 0 }} | 
                            <strong>Countries:</strong> {{ $analytics['location_data']['total_countries'] ?? 0 }}
                        </p>
                    </div>
                </div>
                <div id="user-location-map"></div>
            </div>
        </div>
    </div>
</div>
<!-- End:: row-6 -->

<!-- Start:: row-7 - Country Statistics -->
@if(isset($analytics['location_data']['country_stats']) && count($analytics['location_data']['country_stats']) > 0)
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Users by Country</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Users</th>
                                <th>Devices</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalUsers = array_sum(array_column($analytics['location_data']['country_stats'], 'user_count'));
                            @endphp
                            @foreach($analytics['location_data']['country_stats'] as $stat)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-medium">{{ $stat['country'] ?? 'Unknown' }}</span>
                                        @if(isset($stat['country_code']))
                                            <span class="badge bg-info-transparent ms-2">{{ strtoupper($stat['country_code']) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-transparent">{{ number_format($stat['user_count']) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success-transparent">{{ number_format($stat['device_count']) }}</span>
                                </td>
                                <td>
                                    @if($totalUsers > 0)
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ ($stat['user_count'] / $totalUsers) * 100 }}%" 
                                                 aria-valuenow="{{ ($stat['user_count'] / $totalUsers) * 100 }}" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format(($stat['user_count'] / $totalUsers) * 100, 1) }}%
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">0%</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- End:: row-7 -->
@endsection

@push('scripts')
@if(file_exists(public_path('assets/libs/apexcharts/apexcharts.min.js')))
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
@endif
@if(file_exists(public_path('assets/libs/leaflet/leaflet.js')))
<script src="{{ asset('assets/libs/leaflet/leaflet.js') }}"></script>
@endif

<script>
(function() {
    "use strict";
    
    // Chart data from backend
    const chartData = @json($analytics['chart_data'] ?? []);
    
    // Signups Chart
    if (chartData.signups && typeof ApexCharts !== 'undefined') {
        var signupsOptions = {
            series: [{
                name: 'Signups',
                data: chartData.signups.data || []
            }],
            chart: {
                height: 300,
                type: 'line',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#735dff'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2,
            },
            grid: {
                borderColor: '#f2f5f7',
            },
            xaxis: {
                categories: chartData.signups.labels || [],
                labels: {
                    style: {
                        colors: "#8c9097",
                        fontSize: '11px',
                        fontWeight: 600,
                    },
                }
            },
            yaxis: {
                min: 0,
                labels: {
                    style: {
                        colors: "#8c9097",
                        fontSize: '11px',
                        fontWeight: 600,
                    },
                }
            }
        };
        var signupsChart = new ApexCharts(document.querySelector("#signups-chart"), signupsOptions);
        signupsChart.render();
    }
    
    // Stores Chart
    if (chartData.stores && typeof ApexCharts !== 'undefined') {
        var storesOptions = {
            series: [{
                name: 'Stores',
                data: chartData.stores.data || []
            }],
            chart: {
                height: 300,
                type: 'line',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#28a745'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2,
            },
            grid: {
                borderColor: '#f2f5f7',
            },
            xaxis: {
                categories: chartData.stores.labels || [],
                labels: {
                    style: {
                        colors: "#8c9097",
                        fontSize: '11px',
                        fontWeight: 600,
                    },
                }
            },
            yaxis: {
                min: 0,
                labels: {
                    style: {
                        colors: "#8c9097",
                        fontSize: '11px',
                        fontWeight: 600,
                    },
                }
            }
        };
        var storesChart = new ApexCharts(document.querySelector("#stores-chart"), storesOptions);
        storesChart.render();
    }
    
    // Spam Chart
    if (chartData.spam && typeof ApexCharts !== 'undefined') {
        var spamOptions = {
            series: [{
                name: 'Spam Accounts',
                data: chartData.spam.data || []
            }],
            chart: {
                height: 300,
                type: 'line',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#dc3545'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2,
            },
            grid: {
                borderColor: '#f2f5f7',
            },
            xaxis: {
                categories: chartData.spam.labels || [],
                labels: {
                    style: {
                        colors: "#8c9097",
                        fontSize: '11px',
                        fontWeight: 600,
                    },
                }
            },
            yaxis: {
                min: 0,
                labels: {
                    style: {
                        colors: "#8c9097",
                        fontSize: '11px',
                        fontWeight: 600,
                    },
                }
            }
        };
        var spamChart = new ApexCharts(document.querySelector("#spam-chart"), spamOptions);
        spamChart.render();
    }
    
    // Initialize Leaflet Map
    if (typeof L !== 'undefined' && document.getElementById('user-location-map')) {
        const locationData = @json($analytics['location_data']['locations'] ?? []);
        
        // Initialize map centered on world
        const map = L.map('user-location-map').setView([20, 0], 2);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Add markers for each location
        if (locationData && locationData.length > 0) {
            locationData.forEach(function(location) {
                const popupContent = `
                    <div style="min-width: 150px;">
                        <strong>${location.city || 'Unknown City'}</strong><br>
                        ${location.region ? location.region + '<br>' : ''}
                        ${location.country || 'Unknown Country'}<br>
                        <small class="text-muted">${location.device_count} device(s)</small>
                    </div>
                `;
                
                const marker = L.marker([location.lat, location.lng])
                    .addTo(map)
                    .bindPopup(popupContent);
                
                // Add circle marker for device count visualization
                if (location.device_count > 1) {
                    const radius = Math.min(location.device_count * 2, 20);
                    L.circleMarker([location.lat, location.lng], {
                        radius: radius,
                        fillColor: '#735dff',
                        color: '#735dff',
                        weight: 1,
                        opacity: 0.5,
                        fillOpacity: 0.2
                    }).addTo(map);
                }
            });
            
            // Fit map bounds to show all markers
            if (locationData.length > 0) {
                const bounds = locationData.map(loc => [loc.lat, loc.lng]);
                map.fitBounds(bounds, { padding: [50, 50] });
            }
        } else {
            // Show message if no locations
            map.setView([20, 0], 2);
            L.popup()
                .setLatLng([20, 0])
                .setContent('<div class="text-center"><p class="mb-0">No location data available</p><small class="text-muted">Location data will appear as users log in</small></div>')
                .openOn(map);
        }
    }
})();
</script>
@endpush

