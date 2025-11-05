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
                        'description' => 'Get the authenticated user\'s store information. Returns store details including name, logo, email, phone number, and address. Each user can have only one store.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [],
                        'request_payload' => null,
                        'response' => [
                            'success' => true,
                            'data' => [
                                'store' => [
                                    'id' => 1,
                                    'name' => 'My Store',
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
                        'description' => 'Create a new store for the authenticated user. Requires email verification. Each user can create only one store. Email and phone number must be unique across all stores. If a store already exists, use the update endpoint instead.',
                        'auth' => 'Bearer Token (Required) + Email Verification',
                        'parameters' => [
                            'required' => [
                                'name' => 'string - Store name (max 255 characters)',
                                'email' => 'string - Store email address (must be unique)',
                                'phone_number' => 'string - Store phone number (must be unique)',
                                'address' => 'string - Store physical address',
                            ],
                            'optional' => [
                                'logo' => 'file - Store logo image (max 2MB, formats: jpeg, png, jpg, gif, svg)',
                            ],
                        ],
                        'request_payload' => [
                            'name' => 'My Store',
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
                                    'name' => 'My Store',
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
                            'message' => 'The name field is required.',
                        ],
                        'error_response_5' => [
                            'status' => 422,
                            'success' => false,
                            'message' => 'The email has already been taken.',
                        ],
                        'error_response_6' => [
                            'status' => 422,
                            'success' => false,
                            'message' => 'The phone number has already been taken.',
                        ],
                    ],
                    [
                        'method' => 'PUT',
                        'url' => $baseUrl . '/stores',
                        'name' => 'Update Store',
                        'description' => 'Update the authenticated user\'s existing store. Can update any or all fields including logo. Old logo is automatically deleted when a new one is uploaded. Email and phone number must be unique across all stores (excluding the current store).',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'optional' => [
                                'name' => 'string - Store name (max 255 characters)',
                                'logo' => 'file - Store logo image (max 2MB, formats: jpeg, png, jpg, gif, svg)',
                                'email' => 'string - Store email address (must be unique)',
                                'phone_number' => 'string - Store phone number (must be unique)',
                                'address' => 'string - Store physical address',
                            ],
                        ],
                        'request_payload' => [
                            'name' => 'Updated Store Name',
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
                                    'name' => 'Updated Store Name',
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
                        'error_response_4' => [
                            'status' => 422,
                            'success' => false,
                            'message' => 'The email has already been taken.',
                        ],
                        'error_response_5' => [
                            'status' => 422,
                            'success' => false,
                            'message' => 'The phone number has already been taken.',
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
                        'description' => 'Get all customers for the authenticated user\'s store. Supports pagination toggle, search functionality (searches across name, email, phone_number), multiple filters (name, email, phone_number, date range), and sorting options. Use paginated=false to get all results without pagination.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'optional' => [
                                'paginated' => 'boolean - Enable/disable pagination (default: true). Set to false to get all results without pagination.',
                                'per_page' => 'integer - Number of items per page when paginated=true (default: 15, max: 100). Only applicable when paginated=true.',
                                'page' => 'integer - Page number for pagination (default: 1). Only applicable when paginated=true.',
                                'search' => 'string - Search by name, email, or phone_number using partial match (case-insensitive). Searches across all three fields.',
                                'name' => 'string - Filter by exact name match (case-sensitive)',
                                'email' => 'string - Filter by exact email match (case-sensitive)',
                                'phone_number' => 'string - Filter by exact phone_number match (case-sensitive)',
                                'created_from' => 'date - Filter customers created from this date onwards (YYYY-MM-DD format)',
                                'created_to' => 'date - Filter customers created up to this date (YYYY-MM-DD format)',
                                'sort_by' => 'string - Sort field: name, email, phone_number, created_at, updated_at (default: created_at)',
                                'sort_order' => 'string - Sort order: asc (ascending), desc (descending) (default: desc)',
                            ],
                        ],
                        'request_payload' => null,
                        'request_example_1' => 'GET /api/customers?paginated=true&per_page=20&page=1&sort_by=name&sort_order=asc',
                        'request_example_2' => 'GET /api/customers?paginated=false&search=john&sort_by=created_at&sort_order=desc',
                        'request_example_3' => 'GET /api/customers?paginated=true&per_page=10&email=john@example.com&created_from=2024-01-01&created_to=2024-01-31',
                        'response' => [
                            'status' => 200,
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
                                    [
                                        'id' => 2,
                                        'store_id' => 1,
                                        'name' => 'Jane Smith',
                                        'email' => 'jane@example.com',
                                        'phone_number' => '+9876543210',
                                        'address' => '456 Oak Avenue, City, State',
                                        'created_at' => '2024-01-14T09:20:00.000000Z',
                                        'updated_at' => '2024-01-14T09:20:00.000000Z',
                                    ],
                                ],
                                'pagination' => [
                                    'current_page' => 1,
                                    'last_page' => 5,
                                    'per_page' => 15,
                                    'total' => 67,
                                    'from' => 1,
                                    'to' => 15,
                                ],
                            ],
                            'description' => 'Response when paginated=true (default)',
                        ],
                        'response_2' => [
                            'status' => 200,
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
                                    [
                                        'id' => 2,
                                        'store_id' => 1,
                                        'name' => 'Jane Smith',
                                        'email' => 'jane@example.com',
                                        'phone_number' => '+9876543210',
                                        'address' => '456 Oak Avenue, City, State',
                                        'created_at' => '2024-01-14T09:20:00.000000Z',
                                        'updated_at' => '2024-01-14T09:20:00.000000Z',
                                    ],
                                ],
                                'total' => 2,
                            ],
                            'description' => 'Response when paginated=false (no pagination object, only total count)',
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
                        'description' => 'Create a new customer for the authenticated user\'s store. Store ID is automatically set from the authenticated user\'s store. Phone number must be unique across all customers.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'required' => [
                                'name' => 'string - Customer name',
                                'phone_number' => 'string - Customer phone number (must be unique)',
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
                        'error_response_4' => [
                            'status' => 422,
                            'success' => false,
                            'message' => 'The phone number has already been taken.',
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
                        'description' => 'Update an existing customer. Only customers belonging to the authenticated user\'s store can be updated. Phone number must be unique across all customers (excluding the current customer).',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'required' => [
                                'id' => 'integer - Customer ID (route parameter)',
                            ],
                            'optional' => [
                                'name' => 'string - Customer name',
                                'email' => 'string - Customer email address',
                                'phone_number' => 'string - Customer phone number (must be unique)',
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
                        'error_response_5' => [
                            'status' => 422,
                            'success' => false,
                            'message' => 'The phone number has already been taken.',
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
            [
                'group' => 'Eye Examinations',
                'endpoints' => [
                    [
                        'method' => 'GET',
                        'url' => $baseUrl . '/eye-examinations',
                        'name' => 'Get All Eye Examinations',
                        'description' => 'Get all eye examinations for the authenticated user\'s store. Supports pagination, filtering, and sorting. Each examination in the response includes both authenticated PDF download URL (requires authentication) and public PDF download URL (signed URL, no authentication required). The public download URL is always available, even if the PDF hasn\'t been generated yet (it will be generated on-demand when accessed).',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'optional' => [
                                'paginated' => 'boolean - Enable/disable pagination (default: true). Set to false to get all results.',
                                'per_page' => 'integer - Number of items per page when paginated=true (default: 15, max: 100)',
                                'page' => 'integer - Page number for pagination (default: 1)',
                                'customer_id' => 'integer - Filter by customer ID',
                                'exam_date_from' => 'date - Filter examinations from this date (YYYY-MM-DD)',
                                'exam_date_to' => 'date - Filter examinations up to this date (YYYY-MM-DD)',
                                'sort_by' => 'string - Sort field: exam_date, created_at (default: exam_date)',
                                'sort_order' => 'string - Sort order: asc, desc (default: desc)',
                            ],
                        ],
                        'request_payload' => null,
                        'response' => [
                            'success' => true,
                            'data' => [
                                'eye_examinations' => [
                                    [
                                        'id' => 1,
                                        'customer_id' => 1,
                                        'customer' => [
                                            'id' => 1,
                                            'name' => 'John Doe',
                                            'email' => 'john@example.com',
                                            'phone_number' => '+1234567890',
                                        ],
                                        'store_id' => 1,
                                        'exam_date' => '2024-01-15',
                                        'chief_complaint' => 'Blurry vision',
                                        'old_rx_date' => '2023-06-15',
                                        'od_va_unaided' => '6/60',
                                        'os_va_unaided' => '6/60',
                                        'od_sphere' => '-2.50',
                                        'od_cylinder' => '-0.75',
                                        'od_axis' => 90,
                                        'os_sphere' => '-2.25',
                                        'os_cylinder' => '-0.50',
                                        'os_axis' => 85,
                                        'add_power' => '2.00',
                                        'pd_distance' => '62.00',
                                        'pd_near' => '60.00',
                                        'od_bcva' => '6/6',
                                        'os_bcva' => '6/6',
                                        'iop_od' => 15,
                                        'iop_os' => 16,
                                        'fundus_notes' => 'Normal fundus',
                                        'diagnosis' => 'Myopia',
                                        'management_plan' => 'New glasses prescribed',
                                        'next_recall_date' => '2024-07-15',
                                        'pdf_download_url' => 'http://localhost/api/eye-examinations/1/download-pdf',
                                        'pdf_public_download_url' => 'http://localhost/download/eye-examination/1?signature=abc123xyz...',
                                        'has_pdf' => true,
                                        'created_at' => '2024-01-15T10:30:00.000000Z',
                                        'updated_at' => '2024-01-15T10:30:00.000000Z',
                                    ],
                                ],
                                'pagination' => [
                                    'current_page' => 1,
                                    'last_page' => 3,
                                    'per_page' => 15,
                                    'total' => 42,
                                    'from' => 1,
                                    'to' => 15,
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
                        'url' => $baseUrl . '/eye-examinations',
                        'name' => 'Create Eye Examination',
                        'description' => 'Create a new eye examination record for a customer. Store ID is automatically set from the authenticated user\'s store. A PDF report is automatically generated and includes store details, doctor information, patient information, and all examination data. Both authenticated PDF download URL and public PDF download URL (signed URL, no authentication required) are returned in the response.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'required' => [
                                'customer_id' => 'integer - Customer ID (must belong to your store)',
                                'exam_date' => 'date - Date of examination (YYYY-MM-DD)',
                            ],
                            'optional' => [
                                'chief_complaint' => 'string - Primary reason for visit',
                                'old_rx_date' => 'date - Date of previous prescription (YYYY-MM-DD)',
                                'od_va_unaided' => 'string - Right eye unaided visual acuity (max 255 chars)',
                                'os_va_unaided' => 'string - Left eye unaided visual acuity (max 255 chars)',
                                'od_sphere' => 'numeric - Right eye spherical power',
                                'od_cylinder' => 'numeric - Right eye cylindrical power',
                                'od_axis' => 'integer - Right eye axis (0-180 degrees)',
                                'os_sphere' => 'numeric - Left eye spherical power',
                                'os_cylinder' => 'numeric - Left eye cylindrical power',
                                'os_axis' => 'integer - Left eye axis (0-180 degrees)',
                                'add_power' => 'numeric - Reading addition power',
                                'pd_distance' => 'numeric - Distance pupillary distance in mm (min: 0)',
                                'pd_near' => 'numeric - Near pupillary distance in mm (min: 0)',
                                'od_bcva' => 'string - Right eye best corrected visual acuity (max 255 chars)',
                                'os_bcva' => 'string - Left eye best corrected visual acuity (max 255 chars)',
                                'iop_od' => 'integer - Right eye intraocular pressure in mmHg (min: 0)',
                                'iop_os' => 'integer - Left eye intraocular pressure in mmHg (min: 0)',
                                'fundus_notes' => 'string - Notes on retina/optic nerve health',
                                'diagnosis' => 'string - Clinical findings/diagnosis',
                                'management_plan' => 'string - What was advised',
                                'next_recall_date' => 'date - Recommended date for next check-up (YYYY-MM-DD)',
                            ],
                        ],
                        'request_payload' => [
                            'customer_id' => 1,
                            'exam_date' => '2024-01-15',
                            'chief_complaint' => 'Blurry vision',
                            'old_rx_date' => '2023-06-15',
                            'od_va_unaided' => '6/60',
                            'os_va_unaided' => '6/60',
                            'od_sphere' => -2.50,
                            'od_cylinder' => -0.75,
                            'od_axis' => 90,
                            'os_sphere' => -2.25,
                            'os_cylinder' => -0.50,
                            'os_axis' => 85,
                            'add_power' => 2.00,
                            'pd_distance' => 62.00,
                            'pd_near' => 60.00,
                            'od_bcva' => '6/6',
                            'os_bcva' => '6/6',
                            'iop_od' => 15,
                            'iop_os' => 16,
                            'fundus_notes' => 'Normal fundus',
                            'diagnosis' => 'Myopia',
                            'management_plan' => 'New glasses prescribed',
                            'next_recall_date' => '2024-07-15',
                        ],
                        'response' => [
                            'success' => true,
                            'message' => 'Eye examination created successfully.',
                            'data' => [
                                'eye_examination' => [
                                    'id' => 1,
                                    'customer_id' => 1,
                                    'customer' => [
                                        'id' => 1,
                                        'name' => 'John Doe',
                                        'email' => 'john@example.com',
                                        'phone_number' => '+1234567890',
                                    ],
                                    'store_id' => 1,
                                    'exam_date' => '2024-01-15',
                                    'chief_complaint' => 'Blurry vision',
                                    'old_rx_date' => '2023-06-15',
                                    'od_va_unaided' => '6/60',
                                    'os_va_unaided' => '6/60',
                                    'od_sphere' => '-2.50',
                                    'od_cylinder' => '-0.75',
                                    'od_axis' => 90,
                                    'os_sphere' => '-2.25',
                                    'os_cylinder' => '-0.50',
                                    'os_axis' => 85,
                                    'add_power' => '2.00',
                                    'pd_distance' => '62.00',
                                    'pd_near' => '60.00',
                                    'od_bcva' => '6/6',
                                    'os_bcva' => '6/6',
                                    'iop_od' => 15,
                                    'iop_os' => 16,
                                    'fundus_notes' => 'Normal fundus',
                                    'diagnosis' => 'Myopia',
                                    'management_plan' => 'New glasses prescribed',
                                    'next_recall_date' => '2024-07-15',
                                    'pdf_download_url' => 'http://localhost/api/eye-examinations/1/download-pdf',
                                    'pdf_public_download_url' => 'http://localhost/download/eye-examination/1?signature=abc123xyz...',
                                    'has_pdf' => true,
                                    'created_at' => '2024-01-15T10:30:00.000000Z',
                                    'updated_at' => '2024-01-15T10:30:00.000000Z',
                                ],
                                'pdf_download_url' => 'http://localhost/api/eye-examinations/1/download-pdf',
                                'pdf_public_download_url' => 'http://localhost/download/eye-examination/1?signature=abc123xyz...',
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
                            'message' => 'Customer not found or does not belong to your store.',
                        ],
                        'error_response_4' => [
                            'status' => 422,
                            'success' => false,
                            'message' => 'The customer_id field is required.',
                        ],
                        'error_response_5' => [
                            'status' => 422,
                            'success' => false,
                            'message' => 'The od_axis must be between 0 and 180.',
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'url' => $baseUrl . '/eye-examinations/{id}',
                        'name' => 'Get Eye Examination',
                        'description' => 'Get a specific eye examination by ID. Only returns examinations belonging to the authenticated user\'s store. The response includes both authenticated PDF download URL and public PDF download URL (signed URL, no authentication required).',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'required' => [
                                'id' => 'integer - Eye Examination ID (route parameter)',
                            ],
                        ],
                        'request_payload' => null,
                        'response' => [
                            'success' => true,
                            'data' => [
                                'eye_examination' => [
                                    'id' => 1,
                                    'customer_id' => 1,
                                    'customer' => [
                                        'id' => 1,
                                        'name' => 'John Doe',
                                        'email' => 'john@example.com',
                                        'phone_number' => '+1234567890',
                                    ],
                                    'store_id' => 1,
                                    'exam_date' => '2024-01-15',
                                    'chief_complaint' => 'Blurry vision',
                                    'old_rx_date' => '2023-06-15',
                                    'od_va_unaided' => '6/60',
                                    'os_va_unaided' => '6/60',
                                    'od_sphere' => '-2.50',
                                    'od_cylinder' => '-0.75',
                                    'od_axis' => 90,
                                    'os_sphere' => '-2.25',
                                    'os_cylinder' => '-0.50',
                                    'os_axis' => 85,
                                    'add_power' => '2.00',
                                    'pd_distance' => '62.00',
                                    'pd_near' => '60.00',
                                    'od_bcva' => '6/6',
                                    'os_bcva' => '6/6',
                                    'iop_od' => 15,
                                    'iop_os' => 16,
                                    'fundus_notes' => 'Normal fundus',
                                    'diagnosis' => 'Myopia',
                                    'management_plan' => 'New glasses prescribed',
                                    'next_recall_date' => '2024-07-15',
                                    'pdf_download_url' => 'http://localhost/api/eye-examinations/1/download-pdf',
                                    'pdf_public_download_url' => 'http://localhost/download/eye-examination/1?signature=abc123xyz...',
                                    'has_pdf' => true,
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
                            'message' => 'Eye examination not found.',
                        ],
                    ],
                    [
                        'method' => 'PUT',
                        'url' => $baseUrl . '/eye-examinations/{id}',
                        'name' => 'Update Eye Examination',
                        'description' => 'Update an existing eye examination. Only examinations belonging to the authenticated user\'s store can be updated. The response includes both authenticated PDF download URL and public PDF download URL.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'required' => [
                                'id' => 'integer - Eye Examination ID (route parameter)',
                            ],
                            'optional' => [
                                'customer_id' => 'integer - Customer ID (must belong to your store)',
                                'exam_date' => 'date - Date of examination (YYYY-MM-DD)',
                                'chief_complaint' => 'string - Primary reason for visit',
                                'old_rx_date' => 'date - Date of previous prescription (YYYY-MM-DD)',
                                'od_va_unaided' => 'string - Right eye unaided visual acuity',
                                'os_va_unaided' => 'string - Left eye unaided visual acuity',
                                'od_sphere' => 'numeric - Right eye spherical power',
                                'od_cylinder' => 'numeric - Right eye cylindrical power',
                                'od_axis' => 'integer - Right eye axis (0-180 degrees)',
                                'os_sphere' => 'numeric - Left eye spherical power',
                                'os_cylinder' => 'numeric - Left eye cylindrical power',
                                'os_axis' => 'integer - Left eye axis (0-180 degrees)',
                                'add_power' => 'numeric - Reading addition power',
                                'pd_distance' => 'numeric - Distance pupillary distance in mm',
                                'pd_near' => 'numeric - Near pupillary distance in mm',
                                'od_bcva' => 'string - Right eye best corrected visual acuity',
                                'os_bcva' => 'string - Left eye best corrected visual acuity',
                                'iop_od' => 'integer - Right eye intraocular pressure in mmHg',
                                'iop_os' => 'integer - Left eye intraocular pressure in mmHg',
                                'fundus_notes' => 'string - Notes on retina/optic nerve health',
                                'diagnosis' => 'string - Clinical findings/diagnosis',
                                'management_plan' => 'string - What was advised',
                                'next_recall_date' => 'date - Recommended date for next check-up',
                            ],
                        ],
                        'request_payload' => [
                            'diagnosis' => 'Myopia with Astigmatism',
                            'management_plan' => 'Updated prescription glasses',
                            'next_recall_date' => '2024-08-15',
                        ],
                        'response' => [
                            'success' => true,
                            'message' => 'Eye examination updated successfully.',
                            'data' => [
                                'eye_examination' => [
                                    'id' => 1,
                                    'customer_id' => 1,
                                    'customer' => [
                                        'id' => 1,
                                        'name' => 'John Doe',
                                        'email' => 'john@example.com',
                                        'phone_number' => '+1234567890',
                                    ],
                                    'store_id' => 1,
                                    'exam_date' => '2024-01-15',
                                    'chief_complaint' => 'Blurry vision',
                                    'old_rx_date' => '2023-06-15',
                                    'od_va_unaided' => '6/60',
                                    'os_va_unaided' => '6/60',
                                    'od_sphere' => '-2.50',
                                    'od_cylinder' => '-0.75',
                                    'od_axis' => 90,
                                    'os_sphere' => '-2.25',
                                    'os_cylinder' => '-0.50',
                                    'os_axis' => 85,
                                    'add_power' => '2.00',
                                    'pd_distance' => '62.00',
                                    'pd_near' => '60.00',
                                    'od_bcva' => '6/6',
                                    'os_bcva' => '6/6',
                                    'iop_od' => 15,
                                    'iop_os' => 16,
                                    'fundus_notes' => 'Normal fundus',
                                    'diagnosis' => 'Myopia with Astigmatism',
                                    'management_plan' => 'Updated prescription glasses',
                                    'next_recall_date' => '2024-08-15',
                                    'pdf_download_url' => 'http://localhost/api/eye-examinations/1/download-pdf',
                                    'pdf_public_download_url' => 'http://localhost/download/eye-examination/1?signature=abc123xyz...',
                                    'has_pdf' => true,
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
                            'message' => 'Eye examination not found.',
                        ],
                        'error_response_4' => [
                            'status' => 404,
                            'success' => false,
                            'message' => 'Customer not found or does not belong to your store.',
                        ],
                        'error_response_5' => [
                            'status' => 422,
                            'success' => false,
                            'message' => 'The exam_date must be a valid date.',
                        ],
                    ],
                    [
                        'method' => 'DELETE',
                        'url' => $baseUrl . '/eye-examinations/{id}',
                        'name' => 'Delete Eye Examination',
                        'description' => 'Delete an eye examination. Only examinations belonging to the authenticated user\'s store can be deleted.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'required' => [
                                'id' => 'integer - Eye Examination ID (route parameter)',
                            ],
                        ],
                        'request_payload' => null,
                        'response' => [
                            'success' => true,
                            'message' => 'Eye examination deleted successfully.',
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
                            'message' => 'Eye examination not found.',
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'url' => $baseUrl . '/eye-examinations/{id}/download-pdf',
                        'name' => 'Download Eye Examination PDF (Authenticated)',
                        'description' => 'Download the PDF report for an eye examination (requires authentication). The PDF includes store details, doctor information, patient information, and all examination data. PDF is automatically generated when creating an examination. If PDF doesn\'t exist, it will be generated on the fly.',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'required' => [
                                'id' => 'integer - Eye Examination ID (route parameter)',
                            ],
                        ],
                        'request_payload' => null,
                        'response' => [
                            'type' => 'file',
                            'content_type' => 'application/pdf',
                            'description' => 'Returns a PDF file download. The file name format is: eye-examination-{id}-{exam_date}.pdf',
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
                            'message' => 'Eye examination not found.',
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'url' => config('app.url') . '/download/eye-examination/{id}',
                        'name' => 'Download Eye Examination PDF (Public)',
                        'description' => 'Download the PDF report for an eye examination using a signed URL (no authentication required). This endpoint allows public access to PDF downloads via a secure signed URL. The URL is signed to prevent tampering and can be shared publicly. The PDF is automatically generated if it doesn\'t exist. Note: The URL must include a valid signature parameter generated by the API.',
                        'auth' => 'None (Signed URL Required)',
                        'parameters' => [
                            'required' => [
                                'id' => 'integer - Eye Examination ID (route parameter)',
                                'signature' => 'string - URL signature for security (automatically included in signed URLs)',
                            ],
                        ],
                        'request_payload' => null,
                        'response' => [
                            'type' => 'file',
                            'content_type' => 'application/pdf',
                            'description' => 'Returns a PDF file download. The file name format is: eye-examination-{id}-{exam_date}.pdf',
                        ],
                        'error_response' => [
                            'status' => 403,
                            'success' => false,
                            'message' => 'Invalid signature.',
                            'description' => 'This error occurs when the URL signature is invalid or has been tampered with.',
                        ],
                        'error_response_2' => [
                            'status' => 404,
                            'success' => false,
                            'message' => 'Eye examination not found.',
                        ],
                        'note' => 'To generate a public download URL, use the pdf_public_download_url field from any eye examination API response. The signed URL ensures security while allowing public access.',
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
                                            'key' => 'name',
                                            'value' => 'My Store',
                                            'type' => 'text',
                                            'description' => 'Store name (required, max 255 characters)',
                                        ],
                                        [
                                            'key' => 'logo',
                                            'type' => 'file',
                                            'src' => [],
                                            'description' => 'Store logo image (max 2MB) - Optional',
                                        ],
                                        [
                                            'key' => 'email',
                                            'value' => 'store@example.com',
                                            'type' => 'text',
                                            'description' => 'Store email address (required, must be unique)',
                                        ],
                                        [
                                            'key' => 'phone_number',
                                            'value' => '+1234567890',
                                            'type' => 'text',
                                            'description' => 'Store phone number (required, must be unique)',
                                        ],
                                        [
                                            'key' => 'address',
                                            'value' => '123 Main Street, City, State 12345',
                                            'type' => 'text',
                                            'description' => 'Store physical address (required)',
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
                                            'key' => 'name',
                                            'value' => 'Updated Store Name',
                                            'type' => 'text',
                                            'description' => 'Store name (optional, max 255 characters)',
                                        ],
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
                                            'description' => 'Store email address (optional, must be unique)',
                                        ],
                                        [
                                            'key' => 'phone_number',
                                            'value' => '+9876543210',
                                            'type' => 'text',
                                            'description' => 'Store phone number (optional, must be unique)',
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
                                    'raw' => '{{base_url}}/customers?paginated=true&per_page=15&page=1&search=&name=&email=&phone_number=&created_from=&created_to=&sort_by=created_at&sort_order=desc',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['customers'],
                                    'query' => [
                                        [
                                            'key' => 'paginated',
                                            'value' => 'true',
                                            'description' => 'Enable/disable pagination (default: true). Set to false for all results.',
                                        ],
                                        [
                                            'key' => 'per_page',
                                            'value' => '15',
                                            'description' => 'Items per page when paginated=true (default: 15, max: 100)',
                                        ],
                                        [
                                            'key' => 'page',
                                            'value' => '1',
                                            'description' => 'Page number for pagination (default: 1)',
                                        ],
                                        [
                                            'key' => 'search',
                                            'value' => '',
                                            'description' => 'Search by name, email, or phone_number (partial match, case-insensitive)',
                                            'disabled' => true,
                                        ],
                                        [
                                            'key' => 'name',
                                            'value' => '',
                                            'description' => 'Filter by exact name match (case-sensitive)',
                                            'disabled' => true,
                                        ],
                                        [
                                            'key' => 'email',
                                            'value' => '',
                                            'description' => 'Filter by exact email match (case-sensitive)',
                                            'disabled' => true,
                                        ],
                                        [
                                            'key' => 'phone_number',
                                            'value' => '',
                                            'description' => 'Filter by exact phone_number match (case-sensitive)',
                                            'disabled' => true,
                                        ],
                                        [
                                            'key' => 'created_from',
                                            'value' => '',
                                            'description' => 'Filter customers created from this date onwards (YYYY-MM-DD)',
                                            'disabled' => true,
                                        ],
                                        [
                                            'key' => 'created_to',
                                            'value' => '',
                                            'description' => 'Filter customers created up to this date (YYYY-MM-DD)',
                                            'disabled' => true,
                                        ],
                                        [
                                            'key' => 'sort_by',
                                            'value' => 'created_at',
                                            'description' => 'Sort field: name, email, phone_number, created_at, updated_at (default: created_at)',
                                        ],
                                        [
                                            'key' => 'sort_order',
                                            'value' => 'desc',
                                            'description' => 'Sort order: asc (ascending), desc (descending) (default: desc)',
                                        ],
                                    ],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Get All Customers (Non-Paginated)',
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
                                    'raw' => '{{base_url}}/customers?paginated=false&search=john&sort_by=name&sort_order=asc',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['customers'],
                                    'query' => [
                                        [
                                            'key' => 'paginated',
                                            'value' => 'false',
                                            'description' => 'Get all results without pagination',
                                        ],
                                        [
                                            'key' => 'search',
                                            'value' => 'john',
                                            'description' => 'Search by name, email, or phone_number',
                                        ],
                                        [
                                            'key' => 'sort_by',
                                            'value' => 'name',
                                            'description' => 'Sort field',
                                        ],
                                        [
                                            'key' => 'sort_order',
                                            'value' => 'asc',
                                            'description' => 'Sort order',
                                        ],
                                    ],
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
                [
                    'name' => 'Eye Examinations',
                    'item' => [
                        [
                            'name' => 'Get All Eye Examinations',
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
                                    'raw' => '{{base_url}}/eye-examinations?paginated=true&per_page=15&page=1&customer_id=&exam_date_from=&exam_date_to=&sort_by=exam_date&sort_order=desc',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['eye-examinations'],
                                    'query' => [
                                        [
                                            'key' => 'paginated',
                                            'value' => 'true',
                                            'description' => 'Enable/disable pagination',
                                        ],
                                        [
                                            'key' => 'per_page',
                                            'value' => '15',
                                            'description' => 'Items per page',
                                        ],
                                        [
                                            'key' => 'page',
                                            'value' => '1',
                                            'description' => 'Page number',
                                        ],
                                        [
                                            'key' => 'customer_id',
                                            'value' => '',
                                            'description' => 'Filter by customer ID',
                                            'disabled' => true,
                                        ],
                                        [
                                            'key' => 'exam_date_from',
                                            'value' => '',
                                            'description' => 'Filter from date (YYYY-MM-DD)',
                                            'disabled' => true,
                                        ],
                                        [
                                            'key' => 'exam_date_to',
                                            'value' => '',
                                            'description' => 'Filter to date (YYYY-MM-DD)',
                                            'disabled' => true,
                                        ],
                                        [
                                            'key' => 'sort_by',
                                            'value' => 'exam_date',
                                            'description' => 'Sort field: exam_date, created_at',
                                        ],
                                        [
                                            'key' => 'sort_order',
                                            'value' => 'desc',
                                            'description' => 'Sort order: asc, desc',
                                        ],
                                    ],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Create Eye Examination',
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
                                        'customer_id' => 1,
                                        'exam_date' => '2024-01-15',
                                        'chief_complaint' => 'Blurry vision',
                                        'old_rx_date' => '2023-06-15',
                                        'od_va_unaided' => '6/60',
                                        'os_va_unaided' => '6/60',
                                        'od_sphere' => -2.50,
                                        'od_cylinder' => -0.75,
                                        'od_axis' => 90,
                                        'os_sphere' => -2.25,
                                        'os_cylinder' => -0.50,
                                        'os_axis' => 85,
                                        'add_power' => 2.00,
                                        'pd_distance' => 62.00,
                                        'pd_near' => 60.00,
                                        'od_bcva' => '6/6',
                                        'os_bcva' => '6/6',
                                        'iop_od' => 15,
                                        'iop_os' => 16,
                                        'fundus_notes' => 'Normal fundus',
                                        'diagnosis' => 'Myopia',
                                        'management_plan' => 'New glasses prescribed',
                                        'next_recall_date' => '2024-07-15',
                                    ], JSON_PRETTY_PRINT),
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/eye-examinations',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['eye-examinations'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Get Eye Examination',
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
                                    'raw' => '{{base_url}}/eye-examinations/1',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['eye-examinations', '1'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Update Eye Examination',
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
                                        'diagnosis' => 'Myopia with Astigmatism',
                                        'management_plan' => 'Updated prescription glasses',
                                        'next_recall_date' => '2024-08-15',
                                    ], JSON_PRETTY_PRINT),
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/eye-examinations/1',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['eye-examinations', '1'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Delete Eye Examination',
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
                                    'raw' => '{{base_url}}/eye-examinations/1',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['eye-examinations', '1'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Download Eye Examination PDF (Authenticated)',
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
                                        'value' => 'application/pdf',
                                    ],
                                ],
                                'url' => [
                                    'raw' => '{{base_url}}/eye-examinations/1/download-pdf',
                                    'host' => ['{{base_url}}'],
                                    'path' => ['eye-examinations', '1', 'download-pdf'],
                                ],
                            ],
                            'response' => [],
                        ],
                        [
                            'name' => 'Download Eye Examination PDF (Public - Signed URL)',
                            'request' => [
                                'method' => 'GET',
                                'header' => [
                                    [
                                        'key' => 'Accept',
                                        'value' => 'application/pdf',
                                    ],
                                ],
                                'url' => [
                                    'raw' => '{{base_url_public}}/download/eye-examination/1?signature=YOUR_SIGNED_URL_SIGNATURE',
                                    'host' => ['{{base_url_public}}'],
                                    'path' => ['download', 'eye-examination', '1'],
                                    'query' => [
                                        [
                                            'key' => 'signature',
                                            'value' => 'YOUR_SIGNED_URL_SIGNATURE',
                                            'description' => 'URL signature (get from pdf_public_download_url in API response)',
                                        ],
                                    ],
                                ],
                                'description' => 'Public download endpoint using signed URL. No authentication required. Get the signed URL from the pdf_public_download_url field in any eye examination API response.',
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
                    'key' => 'base_url_public',
                    'value' => config('app.url'),
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