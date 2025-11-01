<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ApiDocumentationController extends Controller
{
    /**
     * Display API documentation page.
     */
    public function index()
    {
        $endpoints = $this->getApiEndpoints();
        return view('admin.api-documentation.index', compact('endpoints'));
    }

    /**
     * Download Postman collection.
     */
    public function downloadPostmanCollection()
    {
        $collection = $this->generatePostmanCollection();
        
        return response()->streamDownload(function () use ($collection) {
            echo json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }, 'eyecare-api-collection.json', [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Get all API endpoints with details.
     */
    private function getApiEndpoints()
    {
        $baseUrl = config('app.url') . '/api';
        
        return [
            [
                'group' => 'Authentication',
                'endpoints' => [
                    [
                        'method' => 'POST',
                        'url' => $baseUrl . '/auth/login',
                        'name' => 'Login',
                        'description' => 'Authenticate user and create device record. Returns authentication token and user information.',
                        'auth' => 'None',
                        'parameters' => [
                            'required' => [
                                'email' => 'string - User email address',
                                'password' => 'string - User password',
                            ],
                            'optional' => [
                                'device_id' => 'string - Unique device identifier',
                                'device_type' => 'string - Device type (mobile, tablet, desktop)',
                                'device_name' => 'string - Device name/model',
                                'os_name' => 'string - Operating system name',
                                'os_version' => 'string - Operating system version',
                                'browser_name' => 'string - Browser name',
                                'browser_version' => 'string - Browser version',
                                'notification_token' => 'string - Push notification token (FCM/APNS)',
                                'notification_platform' => 'string - Notification platform (fcm, apns, web-push)',
                            ],
                        ],
                        'request_payload' => [
                            'email' => 'admin@gmail.com',
                            'password' => 'password',
                            'device_id' => 'device-abc123',
                            'device_type' => 'mobile',
                            'device_name' => 'iPhone 13 Pro',
                            'os_name' => 'iOS',
                            'os_version' => '16.0',
                            'browser_name' => 'Safari',
                            'browser_version' => '16.0',
                            'notification_token' => 'fcm_token_example_123456789',
                            'notification_platform' => 'fcm',
                        ],
                        'response' => [
                            'success' => true,
                            'data' => [
                                'user' => [
                                    'id' => 1,
                                    'name' => 'Admin User',
                                    'email' => 'admin@gmail.com',
                                    'email_verified_at' => '2024-01-15T10:30:00.000000Z',
                                    'role' => [
                                        'id' => 1,
                                        'name' => 'Administrator',
                                        'slug' => 'admin',
                                    ],
                                    'permissions' => [
                                        'view-users',
                                        'create-users',
                                        'edit-users',
                                        'delete-users',
                                    ],
                                ],
                                'token' => '1|aBc123XyZ456DeF789GhI012JkL345MnO678PqR901StU234VwX567',
                                'device' => [
                                    'id' => 1,
                                    'device_id' => 'device-abc123',
                                    'device_type' => 'mobile',
                                ],
                            ],
                            'message' => 'Login successful',
                        ],
                        'error_response' => [
                            'status' => 422,
                            'success' => false,
                            'message' => 'The provided credentials are incorrect.',
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'url' => $baseUrl . '/auth/register',
                        'name' => 'Register',
                        'description' => 'Register a new user account. Automatically creates device record and returns authentication token. If role_id is not provided, user will be assigned the default "user" role.',
                        'auth' => 'None',
                        'parameters' => [
                            'required' => [
                                'name' => 'string - Full name',
                                'email' => 'string - Email address (must be unique)',
                                'password' => 'string - Password (min 8 characters)',
                                'password_confirmation' => 'string - Password confirmation (must match password)',
                            ],
                            'optional' => [
                                'role_id' => 'integer - Role ID (must exist in roles table). If not provided, defaults to "user" role.',
                                'device_id' => 'string - Unique device identifier',
                                'device_type' => 'string - Device type (mobile, tablet, desktop)',
                                'device_name' => 'string - Device name/model',
                                'os_name' => 'string - Operating system name',
                                'os_version' => 'string - Operating system version',
                                'browser_name' => 'string - Browser name',
                                'browser_version' => 'string - Browser version',
                                'notification_token' => 'string - Push notification token (FCM/APNS)',
                                'notification_platform' => 'string - Notification platform (fcm, apns, web-push)',
                            ],
                        ],
                        'request_payload' => [
                            'name' => 'John Doe',
                            'email' => 'john.doe@example.com',
                            'password' => 'SecurePass123!',
                            'password_confirmation' => 'SecurePass123!',
                            'device_id' => 'device-xyz789',
                            'device_type' => 'desktop',
                            'device_name' => 'MacBook Pro',
                            'os_name' => 'macOS',
                            'os_version' => '13.0',
                            'browser_name' => 'Chrome',
                            'browser_version' => '120.0',
                        ],
                        'response' => [
                            'success' => true,
                            'data' => [
                                'user' => [
                                    'id' => 2,
                                    'name' => 'John Doe',
                                    'email' => 'john.doe@example.com',
                                    'email_verified_at' => null,
                                    'role' => [
                                        'id' => 2,
                                        'name' => 'User',
                                        'slug' => 'user',
                                    ],
                                    'permissions' => [
                                        'view-users',
                                        'view-roles',
                                        'view-permissions',
                                    ],
                                ],
                                'token' => '2|mNo456PqR789StU012VwX345YzA678BcD901EfG234HiJ567KlM890',
                                'device' => [
                                    'id' => 2,
                                    'device_id' => 'device-xyz789',
                                    'device_type' => 'desktop',
                                ],
                            ],
                            'message' => 'Registration successful. Please check your email to verify your account.',
                        ],
                        'error_response' => [
                            'status' => 422,
                            'success' => false,
                            'message' => 'The email has already been taken.',
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'url' => $baseUrl . '/auth/logout',
                        'name' => 'Logout',
                        'description' => 'Logout user and revoke the current access token. Optionally deactivates a specific device.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'optional' => [
                                'device_id' => 'string - Device ID to deactivate (if not provided, only token is revoked)',
                            ],
                        ],
                        'request_payload' => [
                            'device_id' => 'device-abc123',
                        ],
                        'response' => [
                            'success' => true,
                            'message' => 'Logged out successfully',
                        ],
                        'error_response' => [
                            'status' => 401,
                            'message' => 'Unauthenticated.',
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'url' => $baseUrl . '/auth/me',
                        'name' => 'Get Authenticated User',
                        'description' => 'Get current authenticated user information including role, permissions, and email verification status.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [],
                        'request_payload' => null,
                        'response' => [
                            'success' => true,
                            'data' => [
                                'user' => [
                                    'id' => 1,
                                    'name' => 'Admin User',
                                    'email' => 'admin@gmail.com',
                                    'email_verified' => true,
                                    'email_verified_at' => '2024-01-15T10:30:00.000000Z',
                                    'role' => [
                                        'id' => 1,
                                        'name' => 'Administrator',
                                        'slug' => 'admin',
                                    ],
                                    'permissions' => [
                                        'view-users',
                                        'create-users',
                                        'edit-users',
                                        'delete-users',
                                        'view-roles',
                                        'create-roles',
                                        'edit-roles',
                                        'delete-roles',
                                        'view-permissions',
                                        'create-permissions',
                                        'edit-permissions',
                                        'delete-permissions',
                                    ],
                                ],
                            ],
                        ],
                        'error_response' => [
                            'status' => 401,
                            'message' => 'Unauthenticated.',
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'url' => $baseUrl . '/auth/check-email-verification',
                        'name' => 'Check Email Verification',
                        'description' => 'Check if the authenticated user\'s email address is verified. Returns a boolean indicating verification status.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [],
                        'request_payload' => null,
                        'response' => [
                            'success' => true,
                            'data' => [
                                'email_verified' => true,
                                'email' => 'user@example.com',
                                'email_verified_at' => '2024-01-15T10:30:00.000000Z',
                            ],
                        ],
                        'error_response' => [
                            'status' => 401,
                            'message' => 'Unauthenticated.',
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'url' => $baseUrl . '/auth/verify-email',
                        'name' => 'Verify Email',
                        'description' => 'Verify user email address via API. Requires id and hash parameters from the verification email link.',
                        'auth' => 'None',
                        'parameters' => [
                            'required' => [
                                'id' => 'integer - User ID',
                                'hash' => 'string - Email hash from verification link',
                            ],
                        ],
                        'request_payload' => [
                            'id' => 1,
                            'hash' => 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6',
                        ],
                        'response' => [
                            'success' => true,
                            'message' => 'Email verified successfully.',
                            'data' => [
                                'user' => [
                                    'id' => 1,
                                    'name' => 'John Doe',
                                    'email' => 'user@example.com',
                                    'email_verified_at' => '2024-01-15T10:30:00.000000Z',
                                ],
                            ],
                        ],
                        'error_response' => [
                            'status' => 403,
                            'success' => false,
                            'message' => 'Invalid verification link.',
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'url' => $baseUrl . '/auth/resend-verification-email',
                        'name' => 'Resend Verification Email',
                        'description' => 'Resend email verification notification to the authenticated user. Only works if email is not already verified.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [],
                        'request_payload' => null,
                        'response' => [
                            'success' => true,
                            'message' => 'Verification email has been sent. Please check your inbox.',
                        ],
                        'error_response' => [
                            'status' => 400,
                            'success' => false,
                            'message' => 'Email already verified.',
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'url' => $baseUrl . '/auth/update-notification-token',
                        'name' => 'Update Notification Token',
                        'description' => 'Update push notification token for a registered device. Device must exist for the authenticated user.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'required' => [
                                'device_id' => 'string - Device identifier (must match existing device)',
                                'notification_token' => 'string - New notification token from FCM/APNS/Web Push',
                                'notification_platform' => 'string - Platform (fcm, apns, web-push)',
                            ],
                        ],
                        'request_payload' => [
                            'device_id' => 'device-abc123',
                            'notification_token' => 'new_fcm_token_987654321',
                            'notification_platform' => 'fcm',
                        ],
                        'response' => [
                            'success' => true,
                            'message' => 'Notification token updated successfully',
                            'data' => [
                                'device' => [
                                    'id' => 1,
                                    'device_id' => 'device-abc123',
                                ],
                            ],
                        ],
                        'error_response' => [
                            'status' => 404,
                            'success' => false,
                            'message' => 'Device not found',
                        ],
                    ],
                ],
            ],
            [
                'group' => 'Stores',
                'endpoints' => [
                    [
                        'method' => 'GET',
                        'url' => $baseUrl . '/stores/check',
                        'name' => 'Check Store Exists',
                        'description' => 'Check if the authenticated user has a store. Returns a boolean indicating if a store exists and the store ID if it exists.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [],
                        'request_payload' => null,
                        'response' => [
                            'success' => true,
                            'data' => [
                                'store_exists' => true,
                                'store_id' => 1,
                            ],
                        ],
                        'error_response' => [
                            'status' => 401,
                            'success' => false,
                            'message' => 'Unauthenticated.',
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'url' => $baseUrl . '/stores',
                        'name' => 'Get Store',
                        'description' => 'Get the authenticated user\'s store information. Returns store details including logo, email, phone number, and address. Each user can have only one store.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [],
                        'request_payload' => null,
                        'response' => [
                            'success' => true,
                            'data' => [
                                'store' => [
                                    'id' => 1,
                                    'logo' => 'http://example.com/storage/stores/logos/logo.jpg',
                                    'email' => 'store@example.com',
                                    'phone_number' => '+1234567890',
                                    'address' => '123 Main Street, City, State 12345',
                                    'created_at' => '2024-01-15T10:30:00.000000Z',
                                    'updated_at' => '2024-01-15T10:30:00.000000Z',
                                ],
                            ],
                        ],
                        'error_response' => [
                            'status' => 401,
                            'success' => false,
                            'message' => 'Unauthenticated.',
                        ],
                        'error_response_2' => [
                            'status' => 404,
                            'success' => false,
                            'message' => 'Store not found. Please create a store first.',
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'url' => $baseUrl . '/stores',
                        'name' => 'Create Store',
                        'description' => 'Create a new store for the authenticated user. Requires email verification. Each user can create only one store. If a store already exists, use the update endpoint instead.',
                        'auth' => 'Bearer Token (Required) + Email Verification',
                        'parameters' => [
                            'required' => [
                                'email' => 'string - Store email address',
                                'phone_number' => 'string - Store phone number',
                                'address' => 'string - Store physical address',
                            ],
                            'optional' => [
                                'logo' => 'file - Store logo image (max 2MB, formats: jpeg, png, jpg, gif, svg)',
                            ],
                        ],
                        'request_payload' => [
                            'logo' => '(multipart/form-data) - Image file',
                            'email' => 'store@example.com',
                            'phone_number' => '+1234567890',
                            'address' => '123 Main Street, City, State 12345',
                        ],
                        'response' => [
                            'success' => true,
                            'message' => 'Store created successfully.',
                            'data' => [
                                'store' => [
                                    'id' => 1,
                                    'logo' => 'http://example.com/storage/stores/logos/logo.jpg',
                                    'email' => 'store@example.com',
                                    'phone_number' => '+1234567890',
                                    'address' => '123 Main Street, City, State 12345',
                                    'created_at' => '2024-01-15T10:30:00.000000Z',
                                    'updated_at' => '2024-01-15T10:30:00.000000Z',
                                ],
                            ],
                        ],
                        'error_response' => [
                            'status' => 401,
                            'success' => false,
                            'message' => 'Unauthenticated.',
                        ],
                        'error_response_2' => [
                            'status' => 403,
                            'success' => false,
                            'message' => 'Please verify your email address before creating a store.',
                        ],
                        'error_response_3' => [
                            'status' => 409,
                            'success' => false,
                            'message' => 'Store already exists. Use the update endpoint to modify your store.',
                        ],
                        'error_response_4' => [
                            'status' => 422,
                            'success' => false,
                            'message' => 'The email field is required.',
                        ],
                    ],
                    [
                        'method' => 'PUT',
                        'url' => $baseUrl . '/stores',
                        'name' => 'Update Store',
                        'description' => 'Update the authenticated user\'s existing store. Can update any or all fields including logo. Old logo is automatically deleted when a new one is uploaded.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'optional' => [
                                'logo' => 'file - Store logo image (max 2MB, formats: jpeg, png, jpg, gif, svg)',
                                'email' => 'string - Store email address',
                                'phone_number' => 'string - Store phone number',
                                'address' => 'string - Store physical address',
                            ],
                        ],
                        'request_payload' => [
                            'logo' => '(multipart/form-data) - Image file',
                            'email' => 'newstore@example.com',
                            'phone_number' => '+9876543210',
                            'address' => '456 Oak Avenue, City, State 67890',
                        ],
                        'response' => [
                            'success' => true,
                            'message' => 'Store updated successfully.',
                            'data' => [
                                'store' => [
                                    'id' => 1,
                                    'logo' => 'http://example.com/storage/stores/logos/new-logo.jpg',
                                    'email' => 'newstore@example.com',
                                    'phone_number' => '+9876543210',
                                    'address' => '456 Oak Avenue, City, State 67890',
                                    'created_at' => '2024-01-15T10:30:00.000000Z',
                                    'updated_at' => '2024-01-15T11:45:00.000000Z',
                                ],
                            ],
                        ],
                        'error_response' => [
                            'status' => 401,
                            'success' => false,
                            'message' => 'Unauthenticated.',
                        ],
                        'error_response_2' => [
                            'status' => 404,
                            'success' => false,
                            'message' => 'Store not found. Please create a store first.',
                        ],
                        'error_response_3' => [
                            'status' => 422,
                            'success' => false,
                            'message' => 'The email must be a valid email address.',
                        ],
                    ],
                ],
            ],
            [
                'group' => 'Customers',
                'endpoints' => [
                    [
                        'method' => 'GET',
                        'url' => $baseUrl . '/customers',
                        'name' => 'Get All Customers',
                        'description' => 'Get all customers for the authenticated user\'s store. Returns paginated list of customers.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'optional' => [
                                'page' => 'integer - Page number for pagination (default: 1)',
                            ],
                        ],
                        'request_payload' => null,
                        'response' => [
                            'success' => true,
                            'data' => [
                                'customers' => [
                                    [
                                        'id' => 1,
                                        'store_id' => 1,
                                        'name' => 'John Doe',
                                        'email' => 'john@example.com',
                                        'phone_number' => '+1234567890',
                                        'address' => '123 Main Street, City, State',
                                        'created_at' => '2024-01-15T10:30:00.000000Z',
                                        'updated_at' => '2024-01-15T10:30:00.000000Z',
                                    ],
                                ],
                                'pagination' => [
                                    'current_page' => 1,
                                    'last_page' => 5,
                                    'per_page' => 15,
                                    'total' => 67,
                                ],
                            ],
                        ],
                        'error_response' => [
                            'status' => 401,
                            'success' => false,
                            'message' => 'Unauthenticated.',
                        ],
                        'error_response_2' => [
                            'status' => 404,
                            'success' => false,
                            'message' => 'Store not found. Please create a store first.',
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'url' => $baseUrl . '/customers',
                        'name' => 'Create Customer',
                        'description' => 'Create a new customer for the authenticated user\'s store. Store ID is automatically set from the authenticated user\'s store.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'required' => [
                                'name' => 'string - Customer name',
                                'phone_number' => 'string - Customer phone number',
                            ],
                            'optional' => [
                                'email' => 'string - Customer email address',
                                'address' => 'string - Customer address',
                            ],
                        ],
                        'request_payload' => [
                            'name' => 'John Doe',
                            'email' => 'john@example.com',
                            'phone_number' => '+1234567890',
                            'address' => '123 Main Street, City, State',
                        ],
                        'response' => [
                            'success' => true,
                            'message' => 'Customer created successfully.',
                            'data' => [
                                'customer' => [
                                    'id' => 1,
                                    'store_id' => 1,
                                    'name' => 'John Doe',
                                    'email' => 'john@example.com',
                                    'phone_number' => '+1234567890',
                                    'address' => '123 Main Street, City, State',
                                    'created_at' => '2024-01-15T10:30:00.000000Z',
                                    'updated_at' => '2024-01-15T10:30:00.000000Z',
                                ],
                            ],
                        ],
                        'error_response' => [
                            'status' => 401,
                            'success' => false,
                            'message' => 'Unauthenticated.',
                        ],
                        'error_response_2' => [
                            'status' => 404,
                            'success' => false,
                            'message' => 'Store not found. Please create a store first.',
                        ],
                        'error_response_3' => [
                            'status' => 422,
                            'success' => false,
                            'message' => 'The name field is required.',
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'url' => $baseUrl . '/customers/{id}',
                        'name' => 'Get Customer',
                        'description' => 'Get a specific customer by ID. Only returns customers belonging to the authenticated user\'s store.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'required' => [
                                'id' => 'integer - Customer ID (route parameter)',
                            ],
                        ],
                        'request_payload' => null,
                        'response' => [
                            'success' => true,
                            'data' => [
                                'customer' => [
                                    'id' => 1,
                                    'store_id' => 1,
                                    'name' => 'John Doe',
                                    'email' => 'john@example.com',
                                    'phone_number' => '+1234567890',
                                    'address' => '123 Main Street, City, State',
                                    'created_at' => '2024-01-15T10:30:00.000000Z',
                                    'updated_at' => '2024-01-15T10:30:00.000000Z',
                                ],
                            ],
                        ],
                        'error_response' => [
                            'status' => 401,
                            'success' => false,
                            'message' => 'Unauthenticated.',
                        ],
                        'error_response_2' => [
                            'status' => 404,
                            'success' => false,
                            'message' => 'Store not found. Please create a store first.',
                        ],
                        'error_response_3' => [
                            'status' => 404,
                            'success' => false,
                            'message' => 'Customer not found.',
                        ],
                    ],
                    [
                        'method' => 'PUT',
                        'url' => $baseUrl . '/customers/{id}',
                        'name' => 'Update Customer',
                        'description' => 'Update an existing customer. Only customers belonging to the authenticated user\'s store can be updated.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'required' => [
                                'id' => 'integer - Customer ID (route parameter)',
                            ],
                            'optional' => [
                                'name' => 'string - Customer name',
                                'email' => 'string - Customer email address',
                                'phone_number' => 'string - Customer phone number',
                                'address' => 'string - Customer address',
                            ],
                        ],
                        'request_payload' => [
                            'name' => 'Jane Doe',
                            'email' => 'jane@example.com',
                            'phone_number' => '+9876543210',
                            'address' => '456 Oak Avenue, City, State',
                        ],
                        'response' => [
                            'success' => true,
                            'message' => 'Customer updated successfully.',
                            'data' => [
                                'customer' => [
                                    'id' => 1,
                                    'store_id' => 1,
                                    'name' => 'Jane Doe',
                                    'email' => 'jane@example.com',
                                    'phone_number' => '+9876543210',
                                    'address' => '456 Oak Avenue, City, State',
                                    'created_at' => '2024-01-15T10:30:00.000000Z',
                                    'updated_at' => '2024-01-15T11:45:00.000000Z',
                                ],
                            ],
                        ],
                        'error_response' => [
                            'status' => 401,
                            'success' => false,
                            'message' => 'Unauthenticated.',
                        ],
                        'error_response_2' => [
                            'status' => 404,
                            'success' => false,
                            'message' => 'Store not found. Please create a store first.',
                        ],
                        'error_response_3' => [
                            'status' => 404,
                            'success' => false,
                            'message' => 'Customer not found.',
                        ],
                        'error_response_4' => [
                            'status' => 422,
                            'success' => false,
                            'message' => 'The email must be a valid email address.',
                        ],
                    ],
                    [
                        'method' => 'DELETE',
                        'url' => $baseUrl . '/customers/{id}',
                        'name' => 'Delete Customer',
                        'description' => 'Delete a customer. Only customers belonging to the authenticated user\'s store can be deleted.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'required' => [
                                'id' => 'integer - Customer ID (route parameter)',
                            ],
                        ],
                        'request_payload' => null,
                        'response' => [
                            'success' => true,
                            'message' => 'Customer deleted successfully.',
                        ],
                        'error_response' => [
                            'status' => 401,
                            'success' => false,
                            'message' => 'Unauthenticated.',
                        ],
                        'error_response_2' => [
                            'status' => 404,
                            'success' => false,
                            'message' => 'Store not found. Please create a store first.',
                        ],
                        'error_response_3' => [
                            'status' => 404,
                            'success' => false,
                            'message' => 'Customer not found.',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Generate Postman collection JSON.
     */
    private function generatePostmanCollection()
    {
        $baseUrl = config('app.url') . '/api';
        
        return [
            'info' => [
                'name' => 'Eyecare API Collection',
                'description' => 'API collection for Eyecare application',
                'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
                '_exporter_id' => 'eyecare-api',
            ],
            'item' => [
                [
                    'name' => 'Authentication',
                    'item' => [
                        [
                            'name' => 'Login',
                            'request' => [
                                'method' => 'POST',
                                'header' => [
                                    [
                                        'key' => 'Content-Type',
                                        'value' => 'application/json',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'body' => [
                                    'mode' => 'raw',
                                    'raw' => json_encode([
                                        'email' => 'admin@gmail.com',
                                        'password' => 'password',
                                        'device_id' => 'device-123',
                                        'device_type' => 'mobile',
                                        'device_name' => 'iPhone 13',
                                        'os_name' => 'iOS',
                                        'os_version' => '16.0',
                                        'browser_name' => 'Safari',
                                        'notification_token' => 'your-fcm-token-here',
                                        'notification_platform' => 'fcm',
                                    ], JSON_PRETTY_PRINT),
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/auth/login',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['auth', 'login'],
                                ],
                            ],
                            'event' => [
                                [
                                    'listen' => 'test',
                                    'script' => [
                                        'exec' => [
                                            'if (pm.response.code === 200) {',
                                            '    var jsonData = pm.response.json();',
                                            '    if (jsonData.success && jsonData.data.token) {',
                                            '        pm.collectionVariables.set("auth_token", jsonData.data.token);',
                                            '        console.log("Auth token saved to collection variable");',
                                            '    }',
                                            '}',
                                        ],
                                        'type' => 'text/javascript',
                                    ],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Register',
                            'request' => [
                                'method' => 'POST',
                                'header' => [
                                    [
                                        'key' => 'Content-Type',
                                        'value' => 'application/json',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'body' => [
                                    'mode' => 'raw',
                                    'raw' => json_encode([
                                        'name' => 'John Doe',
                                        'email' => 'john@example.com',
                                        'password' => 'password123',
                                        'password_confirmation' => 'password123',
                                        'role_id' => 2,
                                        'device_id' => 'device-123',
                                        'device_type' => 'mobile',
                                    ], JSON_PRETTY_PRINT),
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/auth/register',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['auth', 'register'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Logout',
                            'request' => [
                                'method' => 'POST',
                                'header' => [
                                    [
                                        'key' => 'Authorization',
                                        'value' => 'Bearer {{auth_token}}',
                                        'type' => 'text',
                                    ],
                                    [
                                        'key' => 'Content-Type',
                                        'value' => 'application/json',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'body' => [
                                    'mode' => 'raw',
                                    'raw' => json_encode([
                                        'device_id' => 'device-123',
                                    ], JSON_PRETTY_PRINT),
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/auth/logout',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['auth', 'logout'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Get Authenticated User',
                            'request' => [
                                'method' => 'GET',
                                'header' => [
                                    [
                                        'key' => 'Authorization',
                                        'value' => 'Bearer {{auth_token}}',
                                        'type' => 'text',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/auth/me',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['auth', 'me'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Check Email Verification',
                            'request' => [
                                'method' => 'GET',
                                'header' => [
                                    [
                                        'key' => 'Authorization',
                                        'value' => 'Bearer {{auth_token}}',
                                        'type' => 'text',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/auth/check-email-verification',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['auth', 'check-email-verification'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Verify Email',
                            'request' => [
                                'method' => 'GET',
                                'header' => [
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/auth/verify-email?id=1&hash=your-email-hash',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['auth', 'verify-email'],
                                    'query' => [
                                        [
                                            'key' => 'id',
                                            'value' => '1',
                                        ],
                                        [
                                            'key' => 'hash',
                                            'value' => 'your-email-hash',
                                            'description' => 'Hash from verification email link',
                                        ],
                                    ],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Resend Verification Email',
                            'request' => [
                                'method' => 'POST',
                                'header' => [
                                    [
                                        'key' => 'Authorization',
                                        'value' => 'Bearer {{auth_token}}',
                                        'type' => 'text',
                                    ],
                                    [
                                        'key' => 'Content-Type',
                                        'value' => 'application/json',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/auth/resend-verification-email',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['auth', 'resend-verification-email'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Update Notification Token',
                            'request' => [
                                'method' => 'POST',
                                'header' => [
                                    [
                                        'key' => 'Authorization',
                                        'value' => 'Bearer {{auth_token}}',
                                        'type' => 'text',
                                    ],
                                    [
                                        'key' => 'Content-Type',
                                        'value' => 'application/json',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'body' => [
                                    'mode' => 'raw',
                                    'raw' => json_encode([
                                        'device_id' => 'device-123',
                                        'notification_token' => 'new-fcm-token-here',
                                        'notification_platform' => 'fcm',
                                    ], JSON_PRETTY_PRINT),
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/auth/update-notification-token',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['auth', 'update-notification-token'],
                                ],
                            ],
                            'response' => [],
                        ],
                    ],
                ],
                [
                    'name' => 'Stores',
                    'item' => [
                        [
                            'name' => 'Check Store Exists',
                            'request' => [
                                'method' => 'GET',
                                'header' => [
                                    [
                                        'key' => 'Authorization',
                                        'value' => 'Bearer {{auth_token}}',
                                        'type' => 'text',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/stores/check',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['stores', 'check'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Get Store',
                            'request' => [
                                'method' => 'GET',
                                'header' => [
                                    [
                                        'key' => 'Authorization',
                                        'value' => 'Bearer {{auth_token}}',
                                        'type' => 'text',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/stores',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['stores'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Create Store',
                            'request' => [
                                'method' => 'POST',
                                'header' => [
                                    [
                                        'key' => 'Authorization',
                                        'value' => 'Bearer {{auth_token}}',
                                        'type' => 'text',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'body' => [
                                    'mode' => 'formdata',
                                    'formdata' => [
                                        [
                                            'key' => 'logo',
                                            'type' => 'file',
                                            'src' => [],
                                            'description' => 'Store logo image (max 2MB)',
                                        ],
                                        [
                                            'key' => 'email',
                                            'value' => 'store@example.com',
                                            'type' => 'text',
                                            'description' => 'Store email address',
                                        ],
                                        [
                                            'key' => 'phone_number',
                                            'value' => '+1234567890',
                                            'type' => 'text',
                                            'description' => 'Store phone number',
                                        ],
                                        [
                                            'key' => 'address',
                                            'value' => '123 Main Street, City, State 12345',
                                            'type' => 'text',
                                            'description' => 'Store physical address',
                                        ],
                                    ],
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/stores',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['stores'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Update Store',
                            'request' => [
                                'method' => 'PUT',
                                'header' => [
                                    [
                                        'key' => 'Authorization',
                                        'value' => 'Bearer {{auth_token}}',
                                        'type' => 'text',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'body' => [
                                    'mode' => 'formdata',
                                    'formdata' => [
                                        [
                                            'key' => 'logo',
                                            'type' => 'file',
                                            'src' => [],
                                            'description' => 'Store logo image (max 2MB) - Optional',
                                        ],
                                        [
                                            'key' => 'email',
                                            'value' => 'newstore@example.com',
                                            'type' => 'text',
                                            'description' => 'Store email address - Optional',
                                        ],
                                        [
                                            'key' => 'phone_number',
                                            'value' => '+9876543210',
                                            'type' => 'text',
                                            'description' => 'Store phone number - Optional',
                                        ],
                                        [
                                            'key' => 'address',
                                            'value' => '456 Oak Avenue, City, State 67890',
                                            'type' => 'text',
                                            'description' => 'Store physical address - Optional',
                                        ],
                                    ],
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/stores',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['stores'],
                                ],
                            ],
                            'response' => [],
                        ],
                    ],
                ],
                [
                    'name' => 'Customers',
                    'item' => [
                        [
                            'name' => 'Get All Customers',
                            'request' => [
                                'method' => 'GET',
                                'header' => [
                                    [
                                        'key' => 'Authorization',
                                        'value' => 'Bearer {{auth_token}}',
                                        'type' => 'text',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/customers',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['customers'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Create Customer',
                            'request' => [
                                'method' => 'POST',
                                'header' => [
                                    [
                                        'key' => 'Authorization',
                                        'value' => 'Bearer {{auth_token}}',
                                        'type' => 'text',
                                    ],
                                    [
                                        'key' => 'Content-Type',
                                        'value' => 'application/json',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'body' => [
                                    'mode' => 'raw',
                                    'raw' => json_encode([
                                        'name' => 'John Doe',
                                        'email' => 'john@example.com',
                                        'phone_number' => '+1234567890',
                                        'address' => '123 Main Street, City, State',
                                    ], JSON_PRETTY_PRINT),
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/customers',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['customers'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Get Customer',
                            'request' => [
                                'method' => 'GET',
                                'header' => [
                                    [
                                        'key' => 'Authorization',
                                        'value' => 'Bearer {{auth_token}}',
                                        'type' => 'text',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/customers/1',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['customers', '1'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Update Customer',
                            'request' => [
                                'method' => 'PUT',
                                'header' => [
                                    [
                                        'key' => 'Authorization',
                                        'value' => 'Bearer {{auth_token}}',
                                        'type' => 'text',
                                    ],
                                    [
                                        'key' => 'Content-Type',
                                        'value' => 'application/json',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'body' => [
                                    'mode' => 'raw',
                                    'raw' => json_encode([
                                        'name' => 'Jane Doe',
                                        'email' => 'jane@example.com',
                                        'phone_number' => '+9876543210',
                                        'address' => '456 Oak Avenue, City, State',
                                    ], JSON_PRETTY_PRINT),
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/customers/1',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['customers', '1'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Delete Customer',
                            'request' => [
                                'method' => 'DELETE',
                                'header' => [
                                    [
                                        'key' => 'Authorization',
                                        'value' => 'Bearer {{auth_token}}',
                                        'type' => 'text',
                                    ],
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/json',
                                    ],
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/customers/1',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['customers', '1'],
                                ],
                            ],
                            'response' => [],
                        ],
                    ],
                ],
            ],
            'variable' => [
                [
                    'key' => 'base_url',
                    'value' => str_replace('/api', '', config('app.url')) . '/api',
                    'type' => 'string',
                ],
                [
                    'key' => 'auth_token',
                    'value' => '',
                    'type' => 'string',
                ],
            ],
        ];
    }
}