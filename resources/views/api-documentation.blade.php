<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-width="fullwidth" data-menu-styles="light" data-toggled="close">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>API Documentation - Eyecare Admin Panel</title>
    <meta name="Description" content="Complete API Documentation for Eyecare Admin Panel">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">
    
    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Style Css -->
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    
    <!-- Node Waves Css -->
    <link href="{{ asset('assets/libs/node-waves/waves.min.css') }}" rel="stylesheet">
    
    <!-- Simplebar Css -->
    <link href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">
    
    <!-- Prism CSS for code highlighting -->
    <style>
        .api-endpoint-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 2rem;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .api-endpoint-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 8px 8px 0 0;
        }
        .method-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.75rem;
            margin-right: 0.5rem;
        }
        .method-get { background: #10b981; color: white; }
        .method-post { background: #3b82f6; color: white; }
        .method-put { background: #f59e0b; color: white; }
        .method-delete { background: #ef4444; color: white; }
        .code-block {
            background: #1e293b;
            color: #e2e8f0;
            padding: 1.5rem;
            border-radius: 6px;
            overflow-x: auto;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.875rem;
            line-height: 1.6;
        }
        .code-block code {
            color: #e2e8f0;
            background: transparent;
            padding: 0;
        }
        .param-table {
            width: 100%;
        }
        .param-table th {
            background: #f8f9fa;
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }
        .param-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e9ecef;
        }
        .required-badge {
            background: #ef4444;
            color: white;
            padding: 0.125rem 0.5rem;
            border-radius: 3px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .optional-badge {
            background: #6b7280;
            color: white;
            padding: 0.125rem 0.5rem;
            border-radius: 3px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .section-nav {
            position: sticky;
            top: 20px;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
        }
        .endpoint-url {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            display: inline-block;
            margin-top: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="page">
        <!-- Page Header -->
        <div class="page-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="mb-2">
                            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-primary">
                                <i class="ri-arrow-left-line me-1"></i> Back to Admin Panel
                            </a>
                        </div>
                        <h1 class="page-title">API Documentation</h1>
                        <p class="text-muted mb-0">Complete reference for Eyecare Admin Panel API endpoints</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Sidebar Navigation -->
                    <div class="col-xl-3 col-lg-4">
                        <div class="card section-nav">
                            <div class="card-header">
                                <h5 class="card-title mb-0">API Sections</h5>
                            </div>
                            <div class="card-body p-0">
                                <ul class="nav nav-pills flex-column" id="api-sections-nav" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="pill" href="#section-authentication" role="tab">
                                            <i class="ri-shield-user-line me-2"></i> Authentication
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="pill" href="#section-stores" role="tab">
                                            <i class="ri-store-line me-2"></i> Stores
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="pill" href="#section-customers" role="tab">
                                            <i class="ri-user-line me-2"></i> Customers
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="pill" href="#section-examinations" role="tab">
                                            <i class="ri-eye-line me-2"></i> Eye Examinations
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="pill" href="#section-orders" role="tab">
                                            <i class="ri-shopping-cart-line me-2"></i> Orders
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="pill" href="#section-settings" role="tab">
                                            <i class="ri-settings-3-line me-2"></i> Settings
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="pill" href="#section-terms" role="tab">
                                            <i class="ri-file-text-line me-2"></i> Terms & Conditions
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="col-xl-9 col-lg-8">
                        <div class="tab-content" id="api-sections-content">
                            
                            <!-- Authentication Section -->
                            <div class="tab-pane fade show active" id="section-authentication" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Authentication Endpoints</h3>
                                        <p class="text-muted mb-0">User authentication, registration, password management, and email verification</p>
                                    </div>
                                    <div class="card-body">
                                        
                                        <!-- Login Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <span class="method-badge method-post">POST</span>
                                                        <h4 class="mb-0 text-white">Login</h4>
                                                        <div class="endpoint-url">/api/auth/login</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#login-overview">Overview</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#login-request">Request Payload</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#login-success">Success Response</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#login-error">Error Response</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#login-notes">Notes</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="login-overview">
                                                        <div class="mt-3">
                                                            <p>Authenticate a user and receive an access token. The endpoint creates or updates a device record for tracking user sessions across multiple devices.</p>
                                                            <h6 class="mt-4 mb-3">Parameters</h6>
                                                            <div class="table-responsive">
                                                                <table class="table param-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Parameter</th>
                                                                            <th>Type</th>
                                                                            <th>Required</th>
                                                                            <th>Description</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td><code>email</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="required-badge">Required</span></td>
                                                                            <td>User's email address</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><code>password</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="required-badge">Required</span></td>
                                                                            <td>User's password</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><code>device_id</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="optional-badge">Optional</span></td>
                                                                            <td>Unique device identifier (max 255 chars)</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><code>device_type</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="optional-badge">Optional</span></td>
                                                                            <td>Device type: <code>mobile</code>, <code>tablet</code>, or <code>desktop</code></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><code>device_name</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="optional-badge">Optional</span></td>
                                                                            <td>Human-readable device name (max 255 chars)</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><code>notification_token</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="optional-badge">Optional</span></td>
                                                                            <td>Push notification token (FCM, APNS, or web-push)</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><code>notification_platform</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="optional-badge">Optional</span></td>
                                                                            <td>Platform: <code>fcm</code>, <code>apns</code>, or <code>web-push</code></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="login-request">
                                                        <div class="mt-3">
                                                            <h6>Request Example (jQuery AJAX)</h6>
                                                            <div class="code-block">
<pre><code>$.ajax({
    url: '/api/auth/login',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
        email: 'john.doe@example.com',
        password: 'SecurePassword123!',
        device_id: 'device_abc123xyz',
        device_type: 'mobile',
        device_name: 'iPhone 14 Pro',
        os_name: 'iOS',
        os_version: '17.2',
        browser_name: 'Safari',
        browser_version: '17.2',
        notification_token: 'fcm_token_abc123xyz789',
        notification_platform: 'fcm'
    }),
    success: function(response) {
        console.log('Login successful:', response);
        // Store token for subsequent requests
        localStorage.setItem('api_token', response.data.token);
    },
    error: function(xhr) {
        console.error('Login failed:', xhr.responseJSON);
    }
});</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="login-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "user": {
            "id": 42,
            "name": "John Doe",
            "email": "john.doe@example.com",
            "email_verified_at": "2025-01-15T10:30:00.000000Z",
            "role": {
                "id": 2,
                "name": "Store Owner",
                "slug": "store-owner"
            },
            "permissions": [
                "customers.create",
                "customers.view",
                "orders.create",
                "eye-examinations.create"
            ]
        },
        "token": "1|aBcDeFgHiJkLmNoPqRsTuVwXyZ1234567890abcdefghijklmnopqrstuvwxyz",
        "device": {
            "id": 15,
            "device_id": "device_abc123xyz",
            "device_type": "mobile"
        }
    },
    "message": "Login successful"
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="login-error">
                                                        <div class="mt-3">
                                                            <h6>Error Response (422 Unprocessable Entity)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": false,
    "message": "The provided credentials are incorrect."
}</code></pre>
                                                            </div>
                                                            <h6 class="mt-4">Other Error Scenarios</h6>
                                                            <div class="code-block">
<pre><code>// Validation Error (422)
{
    "success": false,
    "message": "Email address is required."
}

// Blocked User (422)
{
    "success": false,
    "message": "Your account has been blocked. Please contact support."
}

// Server Error (500)
{
    "success": false,
    "message": "An unexpected error occurred. Please try again later."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="login-notes">
                                                        <div class="mt-3">
                                                            <h6>Important Notes</h6>
                                                            <ul>
                                                                <li>The access token should be included in the <code>Authorization</code> header for all protected endpoints: <code>Bearer {token}</code></li>
                                                                <li>If <code>device_id</code> is not provided, the system will generate one based on IP address and user agent</li>
                                                                <li>Device information is automatically detected if not provided</li>
                                                                <li>Tokens do not expire by default but can be revoked via logout</li>
                                                                <li>Multiple devices can be active simultaneously for the same user</li>
                                                                <li>Rate limiting applies: 60 requests per minute per IP address</li>
                                                                <li><strong>Blocked Users:</strong> If a user account is blocked by an admin, login will fail with a 422 error. Users must contact support to resolve account blocking issues.</li>
                                                            </ul>
                                                            <h6 class="mt-4">Common Error Cases</h6>
                                                            <ul>
                                                                <li><strong>Invalid Credentials:</strong> Email or password is incorrect</li>
                                                                <li><strong>Blocked Account:</strong> User account has been blocked by administrator</li>
                                                                <li><strong>Validation Errors:</strong> Missing required fields or invalid data format</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Register Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <span class="method-badge method-post">POST</span>
                                                        <h4 class="mb-0 text-white">Register</h4>
                                                        <div class="endpoint-url">/api/auth/register</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#register-overview">Overview</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#register-request">Request Payload</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#register-success">Success Response</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#register-error">Error Response</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#register-notes">Notes</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="register-overview">
                                                        <div class="mt-3">
                                                            <p>Register a new user account. A verification email is automatically sent upon successful registration.</p>
                                                            <h6 class="mt-4 mb-3">Parameters</h6>
                                                            <div class="table-responsive">
                                                                <table class="table param-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Parameter</th>
                                                                            <th>Type</th>
                                                                            <th>Required</th>
                                                                            <th>Description</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td><code>name</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="required-badge">Required</span></td>
                                                                            <td>Full name (max 255 characters)</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><code>email</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="required-badge">Required</span></td>
                                                                            <td>Valid email address (must be unique)</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><code>password</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="required-badge">Required</span></td>
                                                                            <td>Password (must be confirmed, min 8 characters)</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><code>password_confirmation</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="required-badge">Required</span></td>
                                                                            <td>Password confirmation (must match password)</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><code>role_id</code></td>
                                                                            <td>integer</td>
                                                                            <td><span class="optional-badge">Optional</span></td>
                                                                            <td>Role ID (defaults to "user" role if not provided)</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><code>device_name</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="optional-badge">Optional</span></td>
                                                                            <td>Device name for token creation (max 255 chars)</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="register-request">
                                                        <div class="mt-3">
                                                            <h6>Request Example (jQuery AJAX)</h6>
                                                            <div class="code-block">
<pre><code>$.ajax({
    url: '/api/auth/register',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
        name: 'Sarah Johnson',
        email: 'sarah.johnson@example.com',
        password: 'SecurePassword456!',
        password_confirmation: 'SecurePassword456!',
        role_id: null,
        device_name: 'Chrome Browser'
    }),
    success: function(response) {
        console.log('Registration successful:', response);
        alert('Registration successful! Please check your email to verify your account.');
        localStorage.setItem('api_token', response.data.token);
    },
    error: function(xhr) {
        if (xhr.status === 422) {
            console.error('Validation errors:', xhr.responseJSON);
        } else {
            console.error('Registration failed:', xhr.responseJSON);
        }
    }
});</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="register-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (201 Created)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "user": {
            "id": 43,
            "name": "Sarah Johnson",
            "email": "sarah.johnson@example.com",
            "email_verified_at": null,
            "role": {
                "id": 3,
                "name": "User",
                "slug": "user"
            },
            "permissions": []
        },
        "token": "2|XyZaBcDeFgHiJkLmNoPqRsTuVw1234567890abcdefghijklmnopqrstuvwxyz",
        "device": {
            "id": 16,
            "device_id": "device_xyz789abc",
            "device_type": "desktop"
        }
    },
    "message": "Registration successful. Please check your email to verify your account."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="register-error">
                                                        <div class="mt-3">
                                                            <h6>Error Response (422 Unprocessable Entity)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": false,
    "message": "A user with this email already exists."
}</code></pre>
                                                            </div>
                                                            <h6 class="mt-4">Other Error Scenarios</h6>
                                                            <div class="code-block">
<pre><code>// Password confirmation mismatch (422)
{
    "success": false,
    "message": "Password confirmation does not match."
}

// Invalid email format (422)
{
    "success": false,
    "message": "Please provide a valid email address."
}

// Weak password (422)
{
    "success": false,
    "message": "The password must be at least 8 characters."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="register-notes">
                                                        <div class="mt-3">
                                                            <h6>Important Notes</h6>
                                                            <ul>
                                                                <li>Email verification is required before accessing protected resources</li>
                                                                <li>Spam detection runs automatically after registration</li>
                                                                <li>If role_id is not provided, the system assigns the default "user" role</li>
                                                                <li>Verification email may take a few moments to arrive</li>
                                                                <li>If email sending fails, registration still succeeds but user must request resend</li>
                                                                <li>Password must meet Laravel's default password requirements (min 8 characters)</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Logout Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <span class="method-badge method-post">POST</span>
                                                        <h4 class="mb-0 text-white">Logout</h4>
                                                        <div class="endpoint-url">/api/auth/logout</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#logout-overview">Overview</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#logout-request">Request Payload</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#logout-success">Success Response</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#logout-error">Error Response</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#logout-notes">Notes</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="logout-overview">
                                                        <div class="mt-3">
                                                            <p>Logout the authenticated user and revoke the current access token. Optionally deactivates a specific device.</p>
                                                            <h6 class="mt-4 mb-3">Parameters</h6>
                                                            <div class="table-responsive">
                                                                <table class="table param-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Parameter</th>
                                                                            <th>Type</th>
                                                                            <th>Required</th>
                                                                            <th>Description</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td><code>device_id</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="optional-badge">Optional</span></td>
                                                                            <td>Device ID to deactivate (if not provided, only current token is revoked)</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="alert alert-info mt-3">
                                                                <strong>Authentication Required:</strong> This endpoint requires a valid Bearer token in the Authorization header.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="logout-request">
                                                        <div class="mt-3">
                                                            <h6>Request Example (jQuery AJAX)</h6>
                                                            <div class="code-block">
<pre><code>$.ajax({
    url: '/api/auth/logout',
    method: 'POST',
    contentType: 'application/json',
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('api_token')
    },
    data: JSON.stringify({
        device_id: 'device_abc123xyz'
    }),
    success: function(response) {
        console.log('Logout successful:', response);
        localStorage.removeItem('api_token');
        // Redirect to login page
        window.location.href = '/login';
    },
    error: function(xhr) {
        if (xhr.status === 401) {
            console.error('Unauthorized - token may be invalid');
            localStorage.removeItem('api_token');
        } else {
            console.error('Logout failed:', xhr.responseJSON);
        }
    }
});</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="logout-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Logged out successfully"
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="logout-error">
                                                        <div class="mt-3">
                                                            <h6>Error Response (401 Unauthorized)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": false,
    "message": "Unauthenticated."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="logout-notes">
                                                        <div class="mt-3">
                                                            <h6>Important Notes</h6>
                                                            <ul>
                                                                <li>Revokes only the current access token used for the request</li>
                                                                <li>If <code>device_id</code> is provided, that device is marked as inactive</li>
                                                                <li>Other devices remain active and can continue using their tokens</li>
                                                                <li>After logout, the token cannot be reused for authenticated requests</li>
                                                                <li>Client should remove the token from storage after successful logout</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Get Current User Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <span class="method-badge method-get">GET</span>
                                                        <h4 class="mb-0 text-white">Get Current User</h4>
                                                        <div class="endpoint-url">/api/auth/me</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#me-overview">Overview</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#me-success">Success Response</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#me-error">Error Response</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#me-notes">Notes</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="me-overview">
                                                        <div class="mt-3">
                                                            <p>Retrieve the currently authenticated user's information including role and permissions.</p>
                                                            <div class="alert alert-info mt-3">
                                                                <strong>Authentication Required:</strong> This endpoint requires a valid Bearer token in the Authorization header.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="me-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "user": {
            "id": 42,
            "name": "John Doe",
            "email": "john.doe@example.com",
            "email_verified": true,
            "email_verified_at": "2025-01-15T10:30:00.000000Z",
            "role": {
                "id": 2,
                "name": "Store Owner",
                "slug": "store-owner"
            },
            "permissions": [
                "customers.create",
                "customers.view",
                "customers.update",
                "customers.delete",
                "orders.create",
                "orders.view",
                "eye-examinations.create",
                "eye-examinations.view",
                "eye-examinations.update",
                "eye-examinations.delete"
            ]
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="me-error">
                                                        <div class="mt-3">
                                                            <h6>Error Response (401 Unauthorized)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": false,
    "message": "Unauthenticated."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="me-notes">
                                                        <div class="mt-3">
                                                            <h6>Important Notes</h6>
                                                            <ul>
                                                                <li>Use this endpoint to verify token validity and get current user context</li>
                                                                <li>Permissions array contains all permissions granted to the user (via role or direct assignment)</li>
                                                                <li>Email verification status is included for conditional UI rendering</li>
                                                                <li>This is a lightweight endpoint suitable for frequent polling</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Verify Email Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <span class="method-badge method-get">GET</span>
                                                        <h4 class="mb-0 text-white">Verify Email</h4>
                                                        <div class="endpoint-url">/api/auth/verify-email</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#verify-email-overview">Overview</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#verify-email-request">Request Payload</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#verify-email-success">Success Response</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#verify-email-error">Error Response</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#verify-email-notes">Notes</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="verify-email-overview">
                                                        <div class="mt-3">
                                                            <p>Verify a user's email address using the verification link sent via email. This is a public endpoint that does not require authentication.</p>
                                                            <h6 class="mt-4 mb-3">Query Parameters</h6>
                                                            <div class="table-responsive">
                                                                <table class="table param-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Parameter</th>
                                                                            <th>Type</th>
                                                                            <th>Required</th>
                                                                            <th>Description</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td><code>id</code></td>
                                                                            <td>integer</td>
                                                                            <td><span class="required-badge">Required</span></td>
                                                                            <td>User ID</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><code>hash</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="required-badge">Required</span></td>
                                                                            <td>Verification hash from email link</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="verify-email-request">
                                                        <div class="mt-3">
                                                            <h6>Request Example (jQuery AJAX)</h6>
                                                            <div class="code-block">
<pre><code>// Typically called from email link, but can be done via AJAX
$.ajax({
    url: '/api/auth/verify-email?id=42&hash=abc123def456ghi789',
    method: 'GET',
    success: function(response) {
        console.log('Email verified:', response);
        alert('Email verified successfully!');
    },
    error: function(xhr) {
        console.error('Verification failed:', xhr.responseJSON);
        alert('Email verification failed. Please request a new verification link.');
    }
});</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="verify-email-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Email verified successfully.",
    "data": {
        "email_verified": true,
        "email_verified_at": "2025-01-15T14:30:00.000000Z"
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="verify-email-error">
                                                        <div class="mt-3">
                                                            <h6>Error Response (400 Bad Request)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": false,
    "message": "Invalid verification link."
}</code></pre>
                                                            </div>
                                                            <h6 class="mt-4">Other Error Scenarios</h6>
                                                            <div class="code-block">
<pre><code>// Already verified (200 with message)
{
    "success": true,
    "message": "Email is already verified."
}

// Invalid hash (400)
{
    "success": false,
    "message": "The verification link is invalid or has expired."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="verify-email-notes">
                                                        <div class="mt-3">
                                                            <h6>Important Notes</h6>
                                                            <ul>
                                                                <li>Verification links expire after 24 hours by default</li>
                                                                <li>If already verified, returns success with appropriate message</li>
                                                                <li>Hash is generated using Laravel's signed URL mechanism</li>
                                                                <li>This endpoint is public and does not require authentication</li>
                                                                <li>After verification, user can access protected resources</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Forgot Password Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <span class="method-badge method-post">POST</span>
                                                        <h4 class="mb-0 text-white">Forgot Password</h4>
                                                        <div class="endpoint-url">/api/auth/forgot-password</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#forgot-password-overview">Overview</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#forgot-password-request">Request Payload</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#forgot-password-success">Success Response</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#forgot-password-error">Error Response</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#forgot-password-notes">Notes</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="forgot-password-overview">
                                                        <div class="mt-3">
                                                            <p>Request a password reset link to be sent to the user's email address. This is a public endpoint.</p>
                                                            <h6 class="mt-4 mb-3">Parameters</h6>
                                                            <div class="table-responsive">
                                                                <table class="table param-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Parameter</th>
                                                                            <th>Type</th>
                                                                            <th>Required</th>
                                                                            <th>Description</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td><code>email</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="required-badge">Required</span></td>
                                                                            <td>User's email address (max 255 characters)</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="forgot-password-request">
                                                        <div class="mt-3">
                                                            <h6>Request Example (jQuery AJAX)</h6>
                                                            <div class="code-block">
<pre><code>$.ajax({
    url: '/api/auth/forgot-password',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
        email: 'john.doe@example.com'
    }),
    success: function(response) {
        console.log('Reset link sent:', response);
        alert('Password reset link has been sent to your email.');
    },
    error: function(xhr) {
        if (xhr.status === 422) {
            console.error('Validation error:', xhr.responseJSON);
        } else {
            console.error('Request failed:', xhr.responseJSON);
        }
    }
});</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="forgot-password-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Password reset link has been sent to your email address."
}</code></pre>
                                                            </div>
                                                            <p class="mt-3"><strong>Note:</strong> The same response is returned whether the email exists or not (for security reasons).</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="forgot-password-error">
                                                        <div class="mt-3">
                                                            <h6>Error Response (422 Unprocessable Entity)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": false,
    "message": "Email address is required."
}</code></pre>
                                                            </div>
                                                            <h6 class="mt-4">Other Error Scenarios</h6>
                                                            <div class="code-block">
<pre><code>// Invalid email format (422)
{
    "success": false,
    "message": "Please provide a valid email address."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="forgot-password-notes">
                                                        <div class="mt-3">
                                                            <h6>Important Notes</h6>
                                                            <ul>
                                                                <li>For security, the response is the same whether the email exists or not</li>
                                                                <li>Password reset tokens expire after 60 minutes</li>
                                                                <li>Rate limiting applies to prevent abuse</li>
                                                                <li>Only one active reset token exists per user at a time</li>
                                                                <li>If email sending fails, the error is logged but not exposed to the client</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reset Password Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <span class="method-badge method-post">POST</span>
                                                        <h4 class="mb-0 text-white">Reset Password</h4>
                                                        <div class="endpoint-url">/api/auth/reset-password</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#reset-password-overview">Overview</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#reset-password-request">Request Payload</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#reset-password-success">Success Response</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#reset-password-error">Error Response</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#reset-password-notes">Notes</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="reset-password-overview">
                                                        <div class="mt-3">
                                                            <p>Reset a user's password using a valid reset token received via email.</p>
                                                            <h6 class="mt-4 mb-3">Parameters</h6>
                                                            <div class="table-responsive">
                                                                <table class="table param-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Parameter</th>
                                                                            <th>Type</th>
                                                                            <th>Required</th>
                                                                            <th>Description</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td><code>email</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="required-badge">Required</span></td>
                                                                            <td>User's email address</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><code>token</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="required-badge">Required</span></td>
                                                                            <td>Password reset token from email</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><code>password</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="required-badge">Required</span></td>
                                                                            <td>New password (min 8 characters)</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><code>password_confirmation</code></td>
                                                                            <td>string</td>
                                                                            <td><span class="required-badge">Required</span></td>
                                                                            <td>Password confirmation (must match password)</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="reset-password-request">
                                                        <div class="mt-3">
                                                            <h6>Request Example (jQuery AJAX)</h6>
                                                            <div class="code-block">
<pre><code>$.ajax({
    url: '/api/auth/reset-password',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
        email: 'john.doe@example.com',
        token: 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6',
        password: 'NewSecurePassword789!',
        password_confirmation: 'NewSecurePassword789!'
    }),
    success: function(response) {
        console.log('Password reset successful:', response);
        alert('Password has been reset successfully. Please login with your new password.');
        window.location.href = '/login';
    },
    error: function(xhr) {
        if (xhr.status === 422) {
            console.error('Validation error:', xhr.responseJSON);
        } else if (xhr.status === 400) {
            console.error('Invalid token:', xhr.responseJSON);
            alert('Invalid or expired reset token. Please request a new password reset link.');
        } else {
            console.error('Reset failed:', xhr.responseJSON);
        }
    }
});</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="reset-password-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Password has been reset successfully."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="reset-password-error">
                                                        <div class="mt-3">
                                                            <h6>Error Response (400 Bad Request)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": false,
    "message": "Invalid or expired reset token."
}</code></pre>
                                                            </div>
                                                            <h6 class="mt-4">Other Error Scenarios</h6>
                                                            <div class="code-block">
<pre><code>// Validation error (422)
{
    "success": false,
    "message": "Password confirmation does not match."
}

// Weak password (422)
{
    "success": false,
    "message": "The password must be at least 8 characters."
}

// Invalid token (400)
{
    "success": false,
    "message": "This password reset token is invalid."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="reset-password-notes">
                                                        <div class="mt-3">
                                                            <h6>Important Notes</h6>
                                                            <ul>
                                                                <li>Reset tokens are single-use and expire after 60 minutes</li>
                                                                <li>After successful reset, all existing sessions are invalidated</li>
                                                                <li>User must login again with the new password</li>
                                                                <li>Token is consumed immediately upon successful reset</li>
                                                                <li>If token is invalid or expired, user must request a new reset link</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Check Email Verification Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">Check Email Verification</h4>
                                                    <div class="endpoint-url">/api/auth/check-email-verification</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#check-email-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#check-email-success">Success</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#check-email-notes">Notes</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="check-email-overview">
                                                        <div class="mt-3">
                                                            <p>Check if the authenticated user's email is verified. This endpoint is optimized for frequent polling (cached for 3 seconds, rate limited to 60 requests/minute).</p>
                                                            <div class="alert alert-info mt-3"><strong>Authentication Required:</strong> Bearer token required.</div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="check-email-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "email_verified": true,
        "email_verified_at": "2025-01-15T10:30:00.000000Z",
        "email": "john.doe@example.com"
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="check-email-notes">
                                                        <div class="mt-3">
                                                            <h6>Important Notes</h6>
                                                            <ul>
                                                                <li>Response is cached for 3 seconds to reduce database load</li>
                                                                <li>Rate limited to 60 requests per minute</li>
                                                                <li>Designed for mobile apps that poll frequently</li>
                                                                <li>Returns current verification status and email address</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Update Notification Token Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-post">POST</span>
                                                    <h4 class="mb-0 text-white">Update Notification Token</h4>
                                                    <div class="endpoint-url">/api/auth/update-notification-token</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#update-token-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#update-token-request">Request</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#update-token-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="update-token-overview">
                                                        <div class="mt-3">
                                                            <p>Update the push notification token for a device.</p>
                                                            <h6 class="mt-4 mb-3">Parameters</h6>
                                                            <table class="table param-table">
                                                                <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                                                                <tbody>
                                                                    <tr><td><code>device_id</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Device ID</td></tr>
                                                                    <tr><td><code>notification_token</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Push notification token</td></tr>
                                                                    <tr><td><code>notification_platform</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Platform: fcm, apns, or web-push</td></tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="update-token-request">
                                                        <div class="mt-3">
                                                            <h6>Request Example (jQuery AJAX)</h6>
                                                            <div class="code-block">
<pre><code>$.ajax({
    url: '/api/auth/update-notification-token',
    method: 'POST',
    contentType: 'application/json',
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('api_token')
    },
    data: JSON.stringify({
        device_id: 'device_abc123xyz',
        notification_token: 'fcm_new_token_xyz789',
        notification_platform: 'fcm'
    }),
    success: function(response) {
        console.log('Token updated:', response);
    },
    error: function(xhr) {
        console.error('Error:', xhr.responseJSON);
    }
});</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="update-token-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Notification token updated successfully",
    "data": {
        "device": {
            "id": 15,
            "device_id": "device_abc123xyz"
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Resend Verification Email Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-post">POST</span>
                                                    <h4 class="mb-0 text-white">Resend Verification Email</h4>
                                                    <div class="endpoint-url">/api/auth/resend-verification-email</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#resend-verify-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#resend-verify-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="resend-verify-overview">
                                                        <div class="mt-3">
                                                            <p>Resend email verification notification to the authenticated user.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="resend-verify-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Verification email has been sent."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Stores Section -->
                            <div class="tab-pane fade" id="section-stores" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Store Endpoints</h3>
                                        <p class="text-muted mb-0">Manage store information and settings</p>
                                    </div>
                                    <div class="card-body">
                                        
                                        <!-- Create Store Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-post">POST</span>
                                                    <h4 class="mb-0 text-white">Create Store</h4>
                                                    <div class="endpoint-url">/api/stores</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#store-create-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#store-create-request">Request</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#store-create-success">Success</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#store-create-error">Error</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="store-create-overview">
                                                        <div class="mt-3">
                                                            <p>Create a new store for the authenticated user. Each user can have only one store.</p>
                                                            <h6 class="mt-4 mb-3">Parameters</h6>
                                                            <table class="table param-table">
                                                                <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                                                                <tbody>
                                                                    <tr><td><code>name</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Store name (max 255 chars)</td></tr>
                                                                    <tr><td><code>email</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Store email (unique, valid email)</td></tr>
                                                                    <tr><td><code>phone_number</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Phone number (7-15 digits, unique)</td></tr>
                                                                    <tr><td><code>address</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Store address</td></tr>
                                                                    <tr><td><code>logo</code></td><td>file</td><td><span class="optional-badge">Optional</span></td><td>Logo image (jpeg, png, jpg, gif, svg, max 2MB)</td></tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="store-create-request">
                                                        <div class="mt-3">
                                                            <h6>Request Example (jQuery AJAX with FormData)</h6>
                                                            <div class="code-block">
<pre><code>var formData = new FormData();
formData.append('name', 'Vision Care Optometry');
formData.append('email', 'info@visioncare.com');
formData.append('phone_number', '+1-555-123-4567');
formData.append('address', '123 Main Street, New York, NY 10001');
formData.append('logo', $('#logoFile')[0].files[0]); // Optional

$.ajax({
    url: '/api/stores',
    method: 'POST',
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('api_token')
    },
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
        console.log('Store created:', response);
    },
    error: function(xhr) {
        console.error('Error:', xhr.responseJSON);
    }
});</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="store-create-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (201 Created)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Store created successfully.",
    "data": {
        "store": {
            "id": 5,
            "name": "Vision Care Optometry",
            "email": "info@visioncare.com",
            "phone_number": "+1-555-123-4567",
            "address": "123 Main Street, New York, NY 10001",
            "logo_url": "http://example.com/storage/stores/5/logo.png",
            "created_at": "2025-01-15T10:30:00.000000Z",
            "updated_at": "2025-01-15T10:30:00.000000Z"
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="store-create-error">
                                                        <div class="mt-3">
                                                            <h6>Error Responses</h6>
                                                            <div class="code-block">
<pre><code>// Blocked User (403 Forbidden)
{
    "success": false,
    "message": "Your account has been blocked. Please contact support."
}

// Store Already Exists (409 Conflict)
{
    "success": false,
    "message": "User already has a store."
}

// Validation Error (422 Unprocessable Entity)
{
    "success": false,
    "message": "The email field is required."
}

// Unauthorized (401 Unauthorized)
{
    "success": false,
    "message": "Unauthenticated."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Get Store Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">Get Store</h4>
                                                    <div class="endpoint-url">/api/stores</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#store-get-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#store-get-success">Success</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#store-get-error">Error</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#store-get-notes">Notes</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="store-get-overview">
                                                        <div class="mt-3">
                                                            <p>Retrieve the authenticated user's store information. Returns 403 if user is blocked or store is inactive.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="store-get-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "store": {
            "id": 5,
            "user_id": 3,
            "name": "Vision Care Optometry",
            "email": "info@visioncare.com",
            "phone_number": "+1-555-123-4567",
            "address": "123 Main Street, New York, NY 10001",
            "logo": "http://example.com/storage/stores/logos/abc123.png",
            "is_active": true,
            "created_at": "2024-01-15T10:30:00Z",
            "updated_at": "2024-01-20T14:45:00Z"
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="store-get-error">
                                                        <div class="mt-3">
                                                            <h6>Error Responses</h6>
                                                            <div class="code-block">
<pre><code>// Blocked User (403 Forbidden)
{
    "success": false,
    "message": "Your account has been blocked. Please contact support."
}

// Store Not Found (404 Not Found)
{
    "success": false,
    "message": "Store not found. Please create a store first."
}

// Inactive Store (403 Forbidden)
{
    "success": false,
    "message": "Your store has been deactivated. Please contact support."
}

// Unauthorized (401 Unauthorized)
{
    "success": false,
    "message": "Unauthenticated."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="store-get-notes">
                                                        <div class="mt-3">
                                                            <h6>Important Notes</h6>
                                                            <ul>
                                                                <li>Requires authentication via Bearer token</li>
                                                                <li><strong>Blocked Users:</strong> If the user account is blocked, the API returns 403 Forbidden</li>
                                                                <li><strong>Inactive Stores:</strong> If the store is deactivated by admin, the API returns 403 Forbidden</li>
                                                                <li>Each user can only have one store</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Check Store Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">Check Store</h4>
                                                    <div class="endpoint-url">/api/stores/check</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#store-check-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#store-check-success">Success</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#store-check-error">Error</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#store-check-notes">Notes</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="store-check-overview">
                                                        <div class="mt-3">
                                                            <p>Check if the authenticated user has a store. Returns store existence status and active status.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="store-check-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "store_exists": true,
        "store_id": 5,
        "store_active": true
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="store-check-error">
                                                        <div class="mt-3">
                                                            <h6>Error Responses</h6>
                                                            <div class="code-block">
<pre><code>// Blocked User (403 Forbidden)
{
    "success": false,
    "message": "Your account has been blocked. Please contact support."
}

// Unauthorized (401 Unauthorized)
{
    "success": false,
    "message": "Unauthenticated."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="store-check-notes">
                                                        <div class="mt-3">
                                                            <h6>Important Notes</h6>
                                                            <ul>
                                                                <li>Requires authentication via Bearer token</li>
                                                                <li><strong>Blocked Users:</strong> If the user account is blocked, the API returns 403 Forbidden</li>
                                                                <li>Returns <code>store_active</code> status to indicate if store is active or inactive</li>
                                                                <li>Useful for checking store status before attempting store operations</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Update Store Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-put">PUT</span>
                                                    <h4 class="mb-0 text-white">Update Store</h4>
                                                    <div class="endpoint-url">/api/stores</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#store-update-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#store-update-request">Request</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#store-update-success">Success</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#store-update-error">Error</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#store-update-notes">Notes</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="store-update-overview">
                                                        <div class="mt-3">
                                                            <p>Update the authenticated user's store information.</p>
                                                            <h6 class="mt-4 mb-3">Parameters</h6>
                                                            <table class="table param-table">
                                                                <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                                                                <tbody>
                                                                    <tr><td><code>name</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Store name (max 255 chars)</td></tr>
                                                                    <tr><td><code>email</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Store email (unique, valid email)</td></tr>
                                                                    <tr><td><code>phone_number</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Phone number (7-15 digits, unique)</td></tr>
                                                                    <tr><td><code>address</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Store address</td></tr>
                                                                    <tr><td><code>logo</code></td><td>file</td><td><span class="optional-badge">Optional</span></td><td>Logo image (jpeg, png, jpg, gif, svg, max 2MB)</td></tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="store-update-request">
                                                        <div class="mt-3">
                                                            <h6>Request Example (jQuery AJAX with FormData)</h6>
                                                            <div class="code-block">
<pre><code>var formData = new FormData();
formData.append('name', 'Updated Vision Care');
formData.append('email', 'updated@visioncare.com');
formData.append('phone_number', '+1-555-999-8888');
formData.append('address', '456 New Street, New York, NY 10002');
formData.append('logo', $('#logoFile')[0].files[0]); // Optional

$.ajax({
    url: '/api/stores',
    method: 'PUT',
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('api_token')
    },
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
        console.log('Store updated:', response);
    },
    error: function(xhr) {
        console.error('Error:', xhr.responseJSON);
    }
});</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="store-update-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Store updated successfully.",
    "data": {
        "store": {
            "id": 5,
            "user_id": 3,
            "name": "Updated Vision Care",
            "email": "updated@visioncare.com",
            "phone_number": "+1-555-999-8888",
            "address": "456 New Street, New York, NY 10002",
            "logo": "http://example.com/storage/stores/logos/abc123.png",
            "is_active": true,
            "created_at": "2024-01-15T10:30:00Z",
            "updated_at": "2024-01-20T14:45:00Z"
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="store-update-error">
                                                        <div class="mt-3">
                                                            <h6>Error Responses</h6>
                                                            <div class="code-block">
<pre><code>// Blocked User (403 Forbidden)
{
    "success": false,
    "message": "Your account has been blocked. Please contact support."
}

// Store Not Found (404 Not Found)
{
    "success": false,
    "message": "Store not found. Please create a store first."
}

// Validation Error (422 Unprocessable Entity)
{
    "success": false,
    "message": "The email field is required."
}

// Unauthorized (401 Unauthorized)
{
    "success": false,
    "message": "Unauthenticated."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="store-update-notes">
                                                        <div class="mt-3">
                                                            <h6>Important Notes</h6>
                                                            <ul>
                                                                <li>Requires authentication via Bearer token</li>
                                                                <li><strong>Blocked Users:</strong> If the user account is blocked, the API returns 403 Forbidden</li>
                                                                <li>All fields are required except <code>logo</code></li>
                                                                <li>Logo upload is optional - if not provided, existing logo is retained</li>
                                                                <li>Email and phone number must be unique across all stores</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Customers Section -->
                            <div class="tab-pane fade" id="section-customers" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Customer Endpoints</h3>
                                        <p class="text-muted mb-0">Manage customer records</p>
                                    </div>
                                    <div class="card-body">
                                        
                                        <!-- List Customers Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">List Customers</h4>
                                                    <div class="endpoint-url">/api/customers</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#customers-list-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#customers-list-request">Query Params</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#customers-list-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="customers-list-overview">
                                                        <div class="mt-3">
                                                            <p>Retrieve a paginated list of customers for the authenticated user's store with filtering and sorting options.</p>
                                                            <h6 class="mt-4 mb-3">Query Parameters</h6>
                                                            <table class="table param-table">
                                                                <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                                                                <tbody>
                                                                    <tr><td><code>paginated</code></td><td>boolean</td><td><span class="optional-badge">Optional</span></td><td>Enable pagination (default: true)</td></tr>
                                                                    <tr><td><code>per_page</code></td><td>integer</td><td><span class="optional-badge">Optional</span></td><td>Items per page (default: 15, max: 100)</td></tr>
                                                                    <tr><td><code>search</code></td><td>string</td><td><span class="optional-badge">Optional</span></td><td>Search by name, email, or phone</td></tr>
                                                                    <tr><td><code>sort_by</code></td><td>string</td><td><span class="optional-badge">Optional</span></td><td>Sort field (name, email, created_at) - default: created_at</td></tr>
                                                                    <tr><td><code>sort_order</code></td><td>string</td><td><span class="optional-badge">Optional</span></td><td>Sort order (asc, desc) - default: desc</td></tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="customers-list-request">
                                                        <div class="mt-3">
                                                            <h6>Request Example (jQuery AJAX)</h6>
                                                            <div class="code-block">
<pre><code>$.ajax({
    url: '/api/customers?search=john&paginated=true&per_page=20&sort_by=name&sort_order=asc',
    method: 'GET',
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('api_token')
    },
    success: function(response) {
        console.log('Customers:', response.data.customers);
        console.log('Pagination:', response.data.pagination);
    },
    error: function(xhr) {
        console.error('Error:', xhr.responseJSON);
    }
});</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="customers-list-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "customers": [
            {
                "id": 12,
                "name": "John Smith",
                "email": "john.smith@example.com",
                "phone_number": "+1-555-987-6543",
                "address": "456 Oak Avenue, Los Angeles, CA 90001",
                "created_at": "2025-01-10T08:15:00.000000Z",
                "updated_at": "2025-01-10T08:15:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 3,
            "per_page": 20,
            "total": 45,
            "from": 1,
            "to": 20
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Create Customer Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-post">POST</span>
                                                    <h4 class="mb-0 text-white">Create Customer</h4>
                                                    <div class="endpoint-url">/api/customers</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#customer-create-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#customer-create-request">Request</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#customer-create-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="customer-create-overview">
                                                        <div class="mt-3">
                                                            <p>Create a new customer for the authenticated user's store.</p>
                                                            <h6 class="mt-4 mb-3">Parameters</h6>
                                                            <table class="table param-table">
                                                                <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                                                                <tbody>
                                                                    <tr><td><code>name</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Customer name (max 255 chars)</td></tr>
                                                                    <tr><td><code>email</code></td><td>string</td><td><span class="optional-badge">Optional</span></td><td>Email (unique per store, valid email)</td></tr>
                                                                    <tr><td><code>phone_number</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Phone (7-15 digits, unique per store)</td></tr>
                                                                    <tr><td><code>address</code></td><td>string</td><td><span class="optional-badge">Optional</span></td><td>Customer address</td></tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="customer-create-request">
                                                        <div class="mt-3">
                                                            <h6>Request Example (jQuery AJAX)</h6>
                                                            <div class="code-block">
<pre><code>$.ajax({
    url: '/api/customers',
    method: 'POST',
    contentType: 'application/json',
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('api_token')
    },
    data: JSON.stringify({
        name: 'Jane Doe',
        email: 'jane.doe@example.com',
        phone_number: '+1-555-234-5678',
        address: '789 Pine Street, Chicago, IL 60601'
    }),
    success: function(response) {
        console.log('Customer created:', response);
    },
    error: function(xhr) {
        console.error('Error:', xhr.responseJSON);
    }
});</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="customer-create-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (201 Created)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Customer created successfully.",
    "data": {
        "customer": {
            "id": 13,
            "name": "Jane Doe",
            "email": "jane.doe@example.com",
            "phone_number": "+1-555-234-5678",
            "address": "789 Pine Street, Chicago, IL 60601",
            "created_at": "2025-01-15T11:00:00.000000Z",
            "updated_at": "2025-01-15T11:00:00.000000Z"
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Get Customer Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">Get Customer</h4>
                                                    <div class="endpoint-url">/api/customers/{id}</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#customer-show-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#customer-show-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="customer-show-overview">
                                                        <div class="mt-3">
                                                            <p>Retrieve a specific customer by ID.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="customer-show-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "customer": {
            "id": 12,
            "name": "John Smith",
            "email": "john.smith@example.com",
            "phone_number": "+1-555-987-6543",
            "address": "456 Oak Avenue, Los Angeles, CA 90001",
            "created_at": "2025-01-10T08:15:00.000000Z",
            "updated_at": "2025-01-10T08:15:00.000000Z"
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Update Customer Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-put">PUT</span>
                                                    <h4 class="mb-0 text-white">Update Customer</h4>
                                                    <div class="endpoint-url">/api/customers/{id}</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#customer-update-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#customer-update-request">Request</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#customer-update-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="customer-update-overview">
                                                        <div class="mt-3">
                                                            <p>Update a customer's information. All fields are optional (use 'sometimes' validation).</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="customer-update-request">
                                                        <div class="mt-3">
                                                            <h6>Request Example (jQuery AJAX)</h6>
                                                            <div class="code-block">
<pre><code>$.ajax({
    url: '/api/customers/12',
    method: 'PUT',
    contentType: 'application/json',
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('api_token')
    },
    data: JSON.stringify({
        name: 'John Smith Updated',
        email: 'john.updated@example.com',
        address: '789 New Street, Los Angeles, CA 90002'
    }),
    success: function(response) {
        console.log('Customer updated:', response);
    },
    error: function(xhr) {
        console.error('Error:', xhr.responseJSON);
    }
});</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="customer-update-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Customer updated successfully.",
    "data": {
        "customer": {
            "id": 12,
            "name": "John Smith Updated",
            "email": "john.updated@example.com",
            "phone_number": "+1-555-987-6543",
            "address": "789 New Street, Los Angeles, CA 90002"
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Customer Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-delete">DELETE</span>
                                                    <h4 class="mb-0 text-white">Delete Customer</h4>
                                                    <div class="endpoint-url">/api/customers/{id}</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#customer-delete-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#customer-delete-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="customer-delete-overview">
                                                        <div class="mt-3">
                                                            <p>Delete a customer. This performs a soft delete.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="customer-delete-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Customer deleted successfully."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Eye Examinations Section -->
                            <div class="tab-pane fade" id="section-examinations" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Eye Examination Endpoints</h3>
                                        <p class="text-muted mb-0">Manage eye examination records and prescriptions</p>
                                    </div>
                                    <div class="card-body">
                                        
                                        <!-- Create Eye Examination Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-post">POST</span>
                                                    <h4 class="mb-0 text-white">Create Eye Examination</h4>
                                                    <div class="endpoint-url">/api/eye-examinations</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#exam-create-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#exam-create-request">Request</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#exam-create-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="exam-create-overview">
                                                        <div class="mt-3">
                                                            <p>Create a new eye examination record with prescription details.</p>
                                                            <h6 class="mt-4 mb-3">Key Parameters</h6>
                                                            <table class="table param-table">
                                                                <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                                                                <tbody>
                                                                    <tr><td><code>customer_id</code></td><td>integer</td><td><span class="required-badge">Required</span></td><td>Customer ID</td></tr>
                                                                    <tr><td><code>exam_date</code></td><td>date</td><td><span class="required-badge">Required</span></td><td>Examination date (not future)</td></tr>
                                                                    <tr><td><code>od_sphere</code></td><td>numeric</td><td><span class="required-badge">Required</span></td><td>OD sphere (-20.00 to +20.00)</td></tr>
                                                                    <tr><td><code>od_cylinder</code></td><td>numeric</td><td><span class="required-badge">Required</span></td><td>OD cylinder (-20.00 to +20.00)</td></tr>
                                                                    <tr><td><code>od_axis</code></td><td>integer</td><td><span class="optional-badge">Conditional</span></td><td>OD axis (0-180, required if cylinder ≠ 0)</td></tr>
                                                                    <tr><td><code>os_sphere</code></td><td>numeric</td><td><span class="required-badge">Required</span></td><td>OS sphere (-20.00 to +20.00)</td></tr>
                                                                    <tr><td><code>os_cylinder</code></td><td>numeric</td><td><span class="required-badge">Required</span></td><td>OS cylinder (-20.00 to +20.00)</td></tr>
                                                                    <tr><td><code>os_axis</code></td><td>integer</td><td><span class="optional-badge">Conditional</span></td><td>OS axis (0-180, required if cylinder ≠ 0)</td></tr>
                                                                    <tr><td><code>add_power</code></td><td>numeric</td><td><span class="optional-badge">Optional</span></td><td>Add power (0.00 to 3.50)</td></tr>
                                                                    <tr><td><code>pd_distance</code></td><td>numeric</td><td><span class="optional-badge">Optional</span></td><td>PD distance (40-80 mm)</td></tr>
                                                                    <tr><td><code>pd_near</code></td><td>numeric</td><td><span class="optional-badge">Optional</span></td><td>PD near (40-80 mm, must be < pd_distance)</td></tr>
                                                                    <tr><td><code>iop_od</code></td><td>integer</td><td><span class="optional-badge">Optional</span></td><td>OD IOP (5-60 mmHg)</td></tr>
                                                                    <tr><td><code>iop_os</code></td><td>integer</td><td><span class="optional-badge">Optional</span></td><td>OS IOP (5-60 mmHg)</td></tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="exam-create-request">
                                                        <div class="mt-3">
                                                            <h6>Request Example (jQuery AJAX)</h6>
                                                            <div class="code-block">
<pre><code>$.ajax({
    url: '/api/eye-examinations',
    method: 'POST',
    contentType: 'application/json',
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('api_token')
    },
    data: JSON.stringify({
        customer_id: 12,
        exam_date: '2025-01-15',
        od_sphere: -2.50,
        od_cylinder: -0.75,
        od_axis: 180,
        os_sphere: -2.25,
        os_cylinder: -0.50,
        os_axis: 175,
        add_power: 2.00,
        pd_distance: 64,
        pd_near: 62,
        iop_od: 15,
        iop_os: 16,
        chief_complaint: 'Blurred vision for distance',
        diagnosis: 'Myopia with astigmatism',
        management_plan: 'Prescribe corrective lenses'
    }),
    success: function(response) {
        console.log('Examination created:', response);
    },
    error: function(xhr) {
        console.error('Error:', xhr.responseJSON);
    }
});</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="exam-create-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (201 Created)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Eye examination created successfully.",
    "data": {
        "eye_examination": {
            "id": 8,
            "customer_id": 12,
            "exam_date": "2025-01-15",
            "od_sphere": -2.50,
            "od_cylinder": -0.75,
            "od_axis": 180,
            "os_sphere": -2.25,
            "os_cylinder": -0.50,
            "os_axis": 175,
            "add_power": 2.00,
            "pd_distance": 64,
            "pd_near": 62,
            "iop_od": 15,
            "iop_os": 16,
            "pdf_path": "eye_examinations/8/exam-2025-01-15.pdf",
            "created_at": "2025-01-15T11:30:00.000000Z"
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- List Eye Examinations Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">List Eye Examinations</h4>
                                                    <div class="endpoint-url">/api/eye-examinations</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#exam-list-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#exam-list-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="exam-list-overview">
                                                        <div class="mt-3">
                                                            <p>Retrieve paginated list of eye examinations with filtering options.</p>
                                                            <h6 class="mt-4 mb-3">Query Parameters</h6>
                                                            <table class="table param-table">
                                                                <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                                                                <tbody>
                                                                    <tr><td><code>paginated</code></td><td>boolean</td><td><span class="optional-badge">Optional</span></td><td>Enable pagination (default: true)</td></tr>
                                                                    <tr><td><code>per_page</code></td><td>integer</td><td><span class="optional-badge">Optional</span></td><td>Items per page (default: 15, max: 100)</td></tr>
                                                                    <tr><td><code>customer_id</code></td><td>integer</td><td><span class="optional-badge">Optional</span></td><td>Filter by customer ID</td></tr>
                                                                    <tr><td><code>exam_date_from</code></td><td>date</td><td><span class="optional-badge">Optional</span></td><td>Filter from date (YYYY-MM-DD)</td></tr>
                                                                    <tr><td><code>exam_date_to</code></td><td>date</td><td><span class="optional-badge">Optional</span></td><td>Filter to date (YYYY-MM-DD)</td></tr>
                                                                    <tr><td><code>sort_by</code></td><td>string</td><td><span class="optional-badge">Optional</span></td><td>Sort field (exam_date, created_at) - default: exam_date</td></tr>
                                                                    <tr><td><code>sort_order</code></td><td>string</td><td><span class="optional-badge">Optional</span></td><td>Sort order (asc, desc) - default: desc</td></tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="exam-list-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "eye_examinations": [
            {
                "id": 8,
                "customer_id": 12,
                "exam_date": "2025-01-15",
                "od_sphere": -2.50,
                "od_cylinder": -0.75,
                "od_axis": 180,
                "created_at": "2025-01-15T11:30:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 15,
            "total": 1
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Get Eye Examination Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">Get Eye Examination</h4>
                                                    <div class="endpoint-url">/api/eye-examinations/{id}</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#exam-show-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#exam-show-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="exam-show-overview">
                                                        <div class="mt-3">
                                                            <p>Retrieve a specific eye examination by ID with customer details.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="exam-show-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "eye_examination": {
            "id": 8,
            "customer_id": 12,
            "customer": {
                "id": 12,
                "name": "John Smith",
                "email": "john.smith@example.com",
                "phone_number": "+1-555-987-6543"
            },
            "exam_date": "2025-01-15",
            "od_sphere": -2.50,
            "od_cylinder": -0.75,
            "od_axis": 180,
            "os_sphere": -2.25,
            "os_cylinder": -0.50,
            "os_axis": 175,
            "pdf_path": "eye_examinations/8/exam-2025-01-15.pdf"
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Get Previous Prescription Date Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">Get Previous Prescription Date</h4>
                                                    <div class="endpoint-url">/api/eye-examinations/customer/{customerId}/previous-prescription</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#exam-prev-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#exam-prev-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="exam-prev-overview">
                                                        <div class="mt-3">
                                                            <p>Get the date of the previous prescription for a customer.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="exam-prev-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "previous_prescription_date": "2024-12-10",
        "has_previous": true
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Download PDF Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">Download Examination PDF</h4>
                                                    <div class="endpoint-url">/api/eye-examinations/{id}/download-pdf</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#exam-pdf-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#exam-pdf-notes">Notes</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="exam-pdf-overview">
                                                        <div class="mt-3">
                                                            <p>Download the PDF file for an eye examination. PDF is generated automatically if it doesn't exist.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="exam-pdf-notes">
                                                        <div class="mt-3">
                                                            <h6>Important Notes</h6>
                                                            <ul>
                                                                <li>Returns PDF file directly for download</li>
                                                                <li>PDF is automatically generated if it doesn't exist</li>
                                                                <li>File name format: eye-examination-{id}-{exam_date}.pdf</li>
                                                                <li>Content-Type: application/pdf</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Update Eye Examination Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-put">PUT</span>
                                                    <h4 class="mb-0 text-white">Update Eye Examination</h4>
                                                    <div class="endpoint-url">/api/eye-examinations/{id}</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#exam-update-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#exam-update-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="exam-update-overview">
                                                        <div class="mt-3">
                                                            <p>Update an eye examination. All fields are optional (use 'sometimes' validation).</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="exam-update-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Eye examination updated successfully.",
    "data": {
        "eye_examination": {
            "id": 8,
            "exam_date": "2025-01-15",
            "od_sphere": -2.75,
            "od_cylinder": -0.75,
            "od_axis": 180
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Eye Examination Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-delete">DELETE</span>
                                                    <h4 class="mb-0 text-white">Delete Eye Examination</h4>
                                                    <div class="endpoint-url">/api/eye-examinations/{id}</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#exam-delete-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#exam-delete-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="exam-delete-overview">
                                                        <div class="mt-3">
                                                            <p>Delete an eye examination. This performs a soft delete.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="exam-delete-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Eye examination deleted successfully."
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Orders Section -->
                            <div class="tab-pane fade" id="section-orders" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Order Endpoints</h3>
                                        <p class="text-muted mb-0">Manage orders and invoices</p>
                                    </div>
                                    <div class="card-body">
                                        
                                        <!-- Create Order Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-post">POST</span>
                                                    <h4 class="mb-0 text-white">Create Order</h4>
                                                    <div class="endpoint-url">/api/orders</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#order-create-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#order-create-request">Request</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#order-create-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="order-create-overview">
                                                        <div class="mt-3">
                                                            <p>Create a new order with frame photo upload. Invoice PDF is automatically generated.</p>
                                                            <h6 class="mt-4 mb-3">Parameters</h6>
                                                            <table class="table param-table">
                                                                <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                                                                <tbody>
                                                                    <tr><td><code>customer_id</code></td><td>integer</td><td><span class="required-badge">Required</span></td><td>Customer ID</td></tr>
                                                                    <tr><td><code>eye_examination_id</code></td><td>integer</td><td><span class="optional-badge">Optional</span></td><td>Related examination ID</td></tr>
                                                                    <tr><td><code>frame_photo</code></td><td>file</td><td><span class="optional-badge">Optional</span></td><td>Frame photo (jpeg, png, webp, max 5MB)</td></tr>
                                                                    <tr><td><code>glass_details</code></td><td>string</td><td><span class="optional-badge">Optional</span></td><td>Glass/lens details (max 5000 chars)</td></tr>
                                                                    <tr><td><code>total_price</code></td><td>numeric</td><td><span class="required-badge">Required</span></td><td>Total price (0 to 999999.99)</td></tr>
                                                                    <tr><td><code>expected_completion_date</code></td><td>date</td><td><span class="required-badge">Required</span></td><td>Expected completion (today or later)</td></tr>
                                                                    <tr><td><code>status</code></td><td>string</td><td><span class="optional-badge">Optional</span></td><td>Status: pending, processing, completed, cancelled</td></tr>
                                                                    <tr><td><code>notes</code></td><td>string</td><td><span class="optional-badge">Optional</span></td><td>Order notes (max 2000 chars)</td></tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="order-create-request">
                                                        <div class="mt-3">
                                                            <h6>Request Example (jQuery AJAX with FormData)</h6>
                                                            <div class="code-block">
<pre><code>var formData = new FormData();
formData.append('customer_id', 12);
formData.append('eye_examination_id', 8);
formData.append('glass_details', 'Progressive lenses, anti-glare coating, blue light filter');
formData.append('total_price', 2500.00);
formData.append('expected_completion_date', '2025-02-01');
formData.append('status', 'pending');
formData.append('notes', 'Customer prefers thinner frames');
formData.append('frame_photo', $('#framePhoto')[0].files[0]);

$.ajax({
    url: '/api/orders',
    method: 'POST',
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('api_token')
    },
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
        console.log('Order created:', response);
    },
    error: function(xhr) {
        console.error('Error:', xhr.responseJSON);
    }
});</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="order-create-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (201 Created)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Order created successfully and invoice generated.",
    "data": {
        "order": {
            "id": 3,
            "invoice_number": "INV-VCO-202501-0003",
            "customer": {
                "id": 12,
                "name": "John Smith",
                "email": "john.smith@example.com"
            },
            "eye_examination": {
                "id": 8,
                "exam_date": "2025-01-15"
            },
            "frame_photo": "http://example.com/storage/orders/3/frame.jpg",
            "glass_details": "Progressive lenses, anti-glare coating",
            "total_price": 2500.00,
            "expected_completion_date": "2025-02-01",
            "status": "pending",
            "invoice_pdf_url": "http://example.com/storage/invoices/3/invoice.pdf",
            "created_at": "2025-01-15T12:00:00.000000Z"
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- List Orders Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">List Orders</h4>
                                                    <div class="endpoint-url">/api/orders</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#order-list-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#order-list-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="order-list-overview">
                                                        <div class="mt-3">
                                                            <p>Retrieve paginated list of orders with filtering and sorting options.</p>
                                                            <h6 class="mt-4 mb-3">Query Parameters</h6>
                                                            <table class="table param-table">
                                                                <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                                                                <tbody>
                                                                    <tr><td><code>paginated</code></td><td>boolean</td><td><span class="optional-badge">Optional</span></td><td>Enable pagination (default: true)</td></tr>
                                                                    <tr><td><code>per_page</code></td><td>integer</td><td><span class="optional-badge">Optional</span></td><td>Items per page (default: 15, max: 100)</td></tr>
                                                                    <tr><td><code>customer_id</code></td><td>integer</td><td><span class="optional-badge">Optional</span></td><td>Filter by customer ID</td></tr>
                                                                    <tr><td><code>status</code></td><td>string</td><td><span class="optional-badge">Optional</span></td><td>Filter by status (pending, processing, completed, cancelled)</td></tr>
                                                                    <tr><td><code>date_from</code></td><td>date</td><td><span class="optional-badge">Optional</span></td><td>Filter from date (YYYY-MM-DD)</td></tr>
                                                                    <tr><td><code>date_to</code></td><td>date</td><td><span class="optional-badge">Optional</span></td><td>Filter to date (YYYY-MM-DD)</td></tr>
                                                                    <tr><td><code>sort_by</code></td><td>string</td><td><span class="optional-badge">Optional</span></td><td>Sort field (created_at, expected_completion_date, total_price, status)</td></tr>
                                                                    <tr><td><code>sort_order</code></td><td>string</td><td><span class="optional-badge">Optional</span></td><td>Sort order (asc, desc) - default: desc</td></tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="order-list-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "orders": [
            {
                "id": 3,
                "invoice_number": "INV-VCO-202501-0003",
                "customer": {
                    "id": 12,
                    "name": "John Smith"
                },
                "total_price": 2500.00,
                "status": "pending",
                "created_at": "2025-01-15T12:00:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 15,
            "total": 1
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Get Order Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">Get Order</h4>
                                                    <div class="endpoint-url">/api/orders/{id}</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#order-show-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#order-show-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="order-show-overview">
                                                        <div class="mt-3">
                                                            <p>Retrieve a specific order by ID with customer and examination details.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="order-show-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "order": {
            "id": 3,
            "invoice_number": "INV-VCO-202501-0003",
            "customer": {
                "id": 12,
                "name": "John Smith",
                "email": "john.smith@example.com"
            },
            "eye_examination": {
                "id": 8,
                "exam_date": "2025-01-15"
            },
            "total_price": 2500.00,
            "status": "pending",
            "invoice_pdf_url": "http://example.com/storage/invoices/3/invoice.pdf"
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Download Invoice Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">Download Invoice</h4>
                                                    <div class="endpoint-url">/api/orders/{id}/download-invoice</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#order-invoice-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#order-invoice-notes">Notes</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="order-invoice-overview">
                                                        <div class="mt-3">
                                                            <p>Download the invoice PDF for an order.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="order-invoice-notes">
                                                        <div class="mt-3">
                                                            <h6>Important Notes</h6>
                                                            <ul>
                                                                <li>Returns PDF file directly for download</li>
                                                                <li>File name format: invoice-{invoice_number}.pdf</li>
                                                                <li>Content-Type: application/pdf</li>
                                                                <li>Returns 404 if invoice PDF not found</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Settings Section -->
                            <div class="tab-pane fade" id="section-settings" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Settings Endpoints</h3>
                                        <p class="text-muted mb-0">Manage application settings (Admin only)</p>
                                    </div>
                                    <div class="card-body">
                                        
                                        <!-- List Settings Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">List Settings</h4>
                                                    <div class="endpoint-url">/api/settings</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#settings-list-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#settings-list-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="settings-list-overview">
                                                        <div class="mt-3">
                                                            <p>Retrieve all settings with pagination and filtering. Public settings are accessible without authentication.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="settings-list-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "settings": [
            {
                "id": 1,
                "key": "app_name",
                "value": "Eyecare",
                "type": "string",
                "group": "general",
                "is_public": true,
                "description": "Application name"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 15,
            "total": 1
        }
    },
    "message": "Settings retrieved successfully"
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Create Setting Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-post">POST</span>
                                                    <h4 class="mb-0 text-white">Create Setting</h4>
                                                    <div class="endpoint-url">/api/settings</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#setting-create-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#setting-create-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="setting-create-overview">
                                                        <div class="mt-3">
                                                            <p>Create a new application setting. Admin only.</p>
                                                            <h6 class="mt-4 mb-3">Parameters</h6>
                                                            <table class="table param-table">
                                                                <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                                                                <tbody>
                                                                    <tr><td><code>key</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Setting key (unique, alphanumeric and underscores)</td></tr>
                                                                    <tr><td><code>value</code></td><td>string</td><td><span class="optional-badge">Optional</span></td><td>Setting value</td></tr>
                                                                    <tr><td><code>type</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Type: string, integer, boolean, json, text, float</td></tr>
                                                                    <tr><td><code>group</code></td><td>string</td><td><span class="required-badge">Required</span></td><td>Setting group (max 100 chars)</td></tr>
                                                                    <tr><td><code>description</code></td><td>string</td><td><span class="optional-badge">Optional</span></td><td>Description (max 500 chars)</td></tr>
                                                                    <tr><td><code>is_public</code></td><td>boolean</td><td><span class="optional-badge">Optional</span></td><td>Whether setting is publicly accessible</td></tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="setting-create-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (201 Created)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Setting created successfully",
    "data": {
        "setting": {
            "id": 2,
            "key": "app_version",
            "value": "1.0.0",
            "type": "string",
            "group": "general",
            "is_public": true,
            "description": "Application version"
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Get Setting by Group Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">Get Settings by Group</h4>
                                                    <div class="endpoint-url">/api/settings/group/{group}</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#setting-group-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#setting-group-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="setting-group-overview">
                                                        <div class="mt-3">
                                                            <p>Retrieve all settings belonging to a specific group.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="setting-group-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "settings": [
            {
                "id": 1,
                "key": "app_name",
                "value": "Eyecare",
                "type": "string",
                "group": "general",
                "is_public": true
            }
        ],
        "group": "general"
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Get Setting Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">Get Setting</h4>
                                                    <div class="endpoint-url">/api/settings/{setting}</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#setting-show-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#setting-show-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="setting-show-overview">
                                                        <div class="mt-3">
                                                            <p>Retrieve a single setting by ID. Public settings are accessible without authentication.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="setting-show-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "setting": {
            "id": 1,
            "key": "app_name",
            "value": "Eyecare",
            "type": "string",
            "group": "general",
            "is_public": true,
            "description": "Application name"
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Update Setting Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-put">PUT</span>
                                                    <h4 class="mb-0 text-white">Update Setting</h4>
                                                    <div class="endpoint-url">/api/settings/{setting}</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#setting-update-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#setting-update-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="setting-update-overview">
                                                        <div class="mt-3">
                                                            <p>Update an existing setting. All fields are optional. Admin only.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="setting-update-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Setting updated successfully",
    "data": {
        "setting": {
            "id": 1,
            "key": "app_name",
            "value": "Eyecare Pro",
            "type": "string",
            "group": "general"
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Setting Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-delete">DELETE</span>
                                                    <h4 class="mb-0 text-white">Delete Setting</h4>
                                                    <div class="endpoint-url">/api/settings/{setting}</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#setting-delete-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#setting-delete-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="setting-delete-overview">
                                                        <div class="mt-3">
                                                            <p>Permanently delete a setting. Admin only.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="setting-delete-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Setting deleted successfully"
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Terms Section -->
                            <div class="tab-pane fade" id="section-terms" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Terms & Conditions Endpoints</h3>
                                        <p class="text-muted mb-0">Manage terms and conditions acceptance</p>
                                    </div>
                                    <div class="card-body">
                                        
                                        <!-- Get Latest Terms Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">Get Latest Terms</h4>
                                                    <div class="endpoint-url">/api/terms/latest</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#terms-latest-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#terms-latest-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="terms-latest-overview">
                                                        <div class="mt-3">
                                                            <p>Retrieve the latest active terms and conditions. Public endpoint - no authentication required.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="terms-latest-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "terms": {
            "id": 2,
            "title": "Terms and Conditions v2.0",
            "content": "Full terms and conditions content...",
            "version": "2.0",
            "created_at": "2025-01-01 00:00:00",
            "updated_at": "2025-01-01 00:00:00"
        },
        "has_accepted": false
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Accept Terms Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-post">POST</span>
                                                    <h4 class="mb-0 text-white">Accept Terms</h4>
                                                    <div class="endpoint-url">/api/terms/accept</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#terms-accept-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#terms-accept-request">Request</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#terms-accept-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="terms-accept-overview">
                                                        <div class="mt-3">
                                                            <p>Record user acceptance of terms and conditions.</p>
                                                            <h6 class="mt-4 mb-3">Parameters</h6>
                                                            <table class="table param-table">
                                                                <thead><tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr></thead>
                                                                <tbody>
                                                                    <tr><td><code>terms_and_condition_id</code></td><td>integer</td><td><span class="required-badge">Required</span></td><td>Terms ID (must be active)</td></tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="terms-accept-request">
                                                        <div class="mt-3">
                                                            <h6>Request Example (jQuery AJAX)</h6>
                                                            <div class="code-block">
<pre><code>$.ajax({
    url: '/api/terms/accept',
    method: 'POST',
    contentType: 'application/json',
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('api_token')
    },
    data: JSON.stringify({
        terms_and_condition_id: 2
    }),
    success: function(response) {
        console.log('Terms accepted:', response);
    },
    error: function(xhr) {
        console.error('Error:', xhr.responseJSON);
    }
});</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="terms-accept-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "message": "Terms and conditions accepted successfully.",
    "data": {
        "has_accepted": true
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Check Terms Acceptance Endpoint -->
                                        <div class="api-endpoint-card">
                                            <div class="api-endpoint-header">
                                                <div>
                                                    <span class="method-badge method-get">GET</span>
                                                    <h4 class="mb-0 text-white">Check Terms Acceptance</h4>
                                                    <div class="endpoint-url">/api/terms/check</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#terms-check-overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#terms-check-success">Success</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="terms-check-overview">
                                                        <div class="mt-3">
                                                            <p>Check if the authenticated user has accepted the latest terms and conditions.</p>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="terms-check-success">
                                                        <div class="mt-3">
                                                            <h6>Success Response (200 OK)</h6>
                                                            <div class="code-block">
<pre><code>{
    "success": true,
    "data": {
        "has_accepted_latest": false,
        "latest_terms": {
            "id": 2,
            "version": "2.0",
            "updated_at": "2025-01-01 00:00:00"
        },
        "last_accepted": {
            "terms_id": 1,
            "accepted_at": "2024-12-01 10:00:00"
        }
    }
}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- jQuery -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    
    <!-- Simplebar JS -->
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    
    <!-- Node Waves JS -->
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    
    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

</body>
</html>

