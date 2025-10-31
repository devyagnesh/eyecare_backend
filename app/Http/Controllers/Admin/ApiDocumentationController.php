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
                        'description' => 'Authenticate user and create device record',
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
                        'response' => [
                            'success' => true,
                            'data' => [
                                'user' => [
                                    'id' => 'integer',
                                    'name' => 'string',
                                    'email' => 'string',
                                    'role' => 'object',
                                    'permissions' => 'array',
                                ],
                                'token' => 'string - Bearer token for authenticated requests',
                                'device' => [
                                    'id' => 'integer',
                                    'device_id' => 'string',
                                    'device_type' => 'string',
                                ],
                            ],
                            'message' => 'string',
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'url' => $baseUrl . '/auth/register',
                        'name' => 'Register',
                        'description' => 'Register a new user account',
                        'auth' => 'None',
                        'parameters' => [
                            'required' => [
                                'name' => 'string - Full name',
                                'email' => 'string - Email address (must be unique)',
                                'password' => 'string - Password (min 8 characters)',
                                'password_confirmation' => 'string - Password confirmation',
                                'role_id' => 'integer - Role ID (must exist)',
                            ],
                            'optional' => [
                                'device_id' => 'string - Unique device identifier',
                                'device_type' => 'string - Device type',
                                'device_name' => 'string - Device name',
                                'notification_token' => 'string - Push notification token',
                                'notification_platform' => 'string - Notification platform',
                            ],
                        ],
                        'response' => [
                            'success' => true,
                            'data' => [
                                'user' => 'object',
                                'token' => 'string',
                                'device' => 'object',
                            ],
                            'message' => 'string',
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'url' => $baseUrl . '/auth/logout',
                        'name' => 'Logout',
                        'description' => 'Logout user and revoke access token',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'optional' => [
                                'device_id' => 'string - Device ID to deactivate',
                            ],
                        ],
                        'response' => [
                            'success' => true,
                            'message' => 'Logged out successfully',
                        ],
                    ],
                    [
                        'method' => 'GET',
                        'url' => $baseUrl . '/auth/me',
                        'name' => 'Get Authenticated User',
                        'description' => 'Get current authenticated user information',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [],
                        'response' => [
                            'success' => true,
                            'data' => [
                                'user' => [
                                    'id' => 'integer',
                                    'name' => 'string',
                                    'email' => 'string',
                                    'role' => 'object',
                                    'permissions' => 'array',
                                ],
                            ],
                        ],
                    ],
                    [
                        'method' => 'POST',
                        'url' => $baseUrl . '/auth/update-notification-token',
                        'name' => 'Update Notification Token',
                        'description' => 'Update push notification token for a device',
                        'auth' => 'Bearer Token (Required)',
                        'parameters' => [
                            'required' => [
                                'device_id' => 'string - Device identifier',
                                'notification_token' => 'string - New notification token',
                                'notification_platform' => 'string - Platform (fcm, apns, web-push)',
                            ],
                        ],
                        'response' => [
                            'success' => true,
                            'message' => 'Notification token updated successfully',
                            'data' => [
                                'device' => [
                                    'id' => 'integer',
                                    'device_id' => 'string',
                                ],
                            ],
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