<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionMethod;

/**
 * API Documentation Service
 * 
 * Extracts comprehensive API documentation from PHPDoc comments in controllers.
 * 
 * @package App\Services
 */
class ApiDocumentationService
{
    /**
     * Get all API endpoints with comprehensive documentation extracted from PHPDoc.
     * 
     * @param string $baseUrl The base API URL
     * @return array
     */
    public function getApiEndpoints(string $baseUrl): array
    {
        $endpoints = [];
        $groupedEndpoints = [];

        // Get all API routes
        $routes = Route::getRoutes()->getRoutes();
        
        foreach ($routes as $route) {
            // Only process API routes
            if (!str_starts_with($route->uri(), 'api/')) {
                continue;
            }

            $action = $route->getAction();
            
            // Skip if not a controller action
            if (!isset($action['controller'])) {
                continue;
            }

            [$controllerClass, $methodName] = explode('@', $action['controller']);
            
            // Skip if controller doesn't exist
            if (!class_exists($controllerClass)) {
                continue;
            }

            // Skip if method doesn't exist
            if (!method_exists($controllerClass, $methodName)) {
                continue;
            }

            // Extract documentation
            $doc = $this->extractMethodDocumentation($controllerClass, $methodName, $route);
            
            // Determine group
            $group = $this->determineGroup($controllerClass, $route->uri());
            
            // Build endpoint data
            $method = strtoupper($route->methods()[0] ?? 'GET');
            $requiresAuth = $this->requiresAuth($route);
            
            $endpoint = [
                'method' => $method,
                'url' => $baseUrl . '/' . $route->uri(),
                'uri' => $route->uri(),
                'name' => $route->getName() ?? $methodName,
                'description' => $doc['description'] ?? '',
                'auth' => $requiresAuth,
                'headers' => $this->getHeaders($method, $requiresAuth),
                'parameters' => $doc['parameters'] ?? [],
                'query_parameters' => $doc['query_parameters'] ?? [],
                'payload_example' => $doc['payload_example'] ?? null,
                'success_response' => $doc['success_response'] ?? null,
                'error_response' => $doc['error_response'] ?? null,
                'status_codes' => $doc['status_codes'] ?? $this->getDefaultStatusCodes($method),
            ];

            if (!isset($groupedEndpoints[$group])) {
                $groupedEndpoints[$group] = [
                    'group' => $group,
                    'icon' => $this->getGroupIcon($group),
                    'description' => $this->getGroupDescription($group),
                    'endpoints' => [],
                ];
            }

            $groupedEndpoints[$group]['endpoints'][] = $endpoint;
        }

        return array_values($groupedEndpoints);
    }

    /**
     * Extract comprehensive documentation from a controller method's PHPDoc.
     * 
     * @param string $controllerClass
     * @param string $methodName
     * @param \Illuminate\Routing\Route $route
     * @return array|null
     */
    private function extractMethodDocumentation(string $controllerClass, string $methodName, $route): ?array
    {
        try {
            $reflection = new ReflectionClass($controllerClass);
            $method = $reflection->getMethod($methodName);
            
            $docComment = $method->getDocComment();
            
            // Parse documentation
            $doc = $docComment ? $this->parseDocComment($docComment, $route) : $this->generateBasicDocumentation($methodName, $route);
            
            // Generate real examples if not present in PHPDoc
            if (empty($doc['payload_example'])) {
                $doc['payload_example'] = $this->generatePayloadExample($controllerClass, $methodName, $route);
            }
            
            if (empty($doc['success_response'])) {
                $doc['success_response'] = $this->generateSuccessResponse($controllerClass, $methodName, $route);
            }
            
            if (empty($doc['error_response'])) {
                $doc['error_response'] = $this->generateErrorResponse($controllerClass, $methodName, $route);
            }
            
            return $doc;
        } catch (\ReflectionException $e) {
            return $this->generateBasicDocumentation($methodName, $route);
        }
    }
    
    /**
     * Generate real payload example based on controller and method.
     */
    private function generatePayloadExample(string $controllerClass, string $methodName, $route): ?array
    {
        $method = strtoupper($route->methods()[0] ?? 'GET');
        
        // Only generate for POST, PUT, PATCH
        if (!in_array($method, ['POST', 'PUT', 'PATCH'])) {
            return null;
        }
        
        $controllerName = class_basename($controllerClass);
        
        // Generate examples based on controller
        if ($controllerName === 'AuthController') {
            if ($methodName === 'login') {
                return [
                    'email' => 'user@example.com',
                    'password' => 'password123',
                    'device_id' => 'device_123456',
                    'device_type' => 'mobile',
                    'device_name' => 'iPhone 14',
                    'os_name' => 'iOS',
                    'os_version' => '17.0',
                    'browser_name' => 'Safari',
                    'browser_version' => '17.0',
                    'notification_token' => 'fcm_token_here',
                    'notification_platform' => 'fcm'
                ];
            } elseif ($methodName === 'register') {
                return [
                    'name' => 'John Doe',
                    'email' => 'john.doe@example.com',
                    'password' => 'SecurePassword123!',
                    'password_confirmation' => 'SecurePassword123!',
                    'device_id' => 'device_123456',
                    'device_type' => 'mobile'
                ];
            }
        } elseif ($controllerName === 'StoreController') {
            if ($methodName === 'store') {
                return [
                    'name' => 'Vision Care Center',
                    'email' => 'info@visioncare.com',
                    'phone_number' => '+1234567890',
                    'address' => '123 Main Street, City, State 12345',
                    'logo' => null
                ];
            } elseif ($methodName === 'update') {
                return [
                    'name' => 'Vision Care Center Updated',
                    'email' => 'info@visioncare.com',
                    'phone_number' => '+1234567890',
                    'address' => '123 Main Street, City, State 12345'
                ];
            }
        } elseif ($controllerName === 'CustomerController') {
            if ($methodName === 'store') {
                return [
                    'name' => 'Jane Smith',
                    'email' => 'jane.smith@example.com',
                    'phone_number' => '+1987654321',
                    'address' => '456 Oak Avenue, City, State 54321'
                ];
            } elseif ($methodName === 'update') {
                return [
                    'name' => 'Jane Smith Updated',
                    'email' => 'jane.smith.new@example.com',
                    'phone_number' => '+1987654321',
                    'address' => '456 Oak Avenue, City, State 54321'
                ];
            }
        } elseif ($controllerName === 'EyeExaminationController') {
            if ($methodName === 'store') {
                return [
                    'customer_id' => 1,
                    'exam_date' => '2025-01-15',
                    'chief_complaint' => 'Blurred vision',
                    'old_rx_date' => '2024-01-10',
                    'od_va_unaided' => '6/12',
                    'os_va_unaided' => '6/12',
                    'od_sphere' => -2.50,
                    'od_cylinder' => -0.75,
                    'od_axis' => 90,
                    'os_sphere' => -2.25,
                    'os_cylinder' => -0.50,
                    'os_axis' => 85,
                    'add_power' => 1.50,
                    'pd_distance' => 64,
                    'pd_near' => 62,
                    'od_bcva' => '6/6',
                    'os_bcva' => '6/6',
                    'iop_od' => 15,
                    'iop_os' => 16,
                    'fundus_notes' => 'Normal fundus',
                    'diagnosis' => 'Myopia with astigmatism',
                    'management_plan' => 'Prescribe glasses',
                    'next_recall_date' => '2026-01-15'
                ];
            } elseif ($methodName === 'update') {
                return [
                    'exam_date' => '2025-01-15',
                    'od_sphere' => -2.75,
                    'od_cylinder' => -0.75,
                    'od_axis' => 90
                ];
            }
        } elseif ($controllerName === 'SettingController') {
            if ($methodName === 'store') {
                return [
                    'key' => 'app_name',
                    'value' => 'Eyecare',
                    'type' => 'string',
                    'group' => 'general',
                    'description' => 'Application name',
                    'is_public' => true
                ];
            } elseif ($methodName === 'update') {
                return [
                    'value' => 'Eyecare Pro',
                    'description' => 'Updated application name'
                ];
            }
        }
        
        return null;
    }
    
    /**
     * Generate real success response example.
     */
    private function generateSuccessResponse(string $controllerClass, string $methodName, $route): ?array
    {
        $method = strtoupper($route->methods()[0] ?? 'GET');
        $controllerName = class_basename($controllerClass);
        
        if ($controllerName === 'AuthController') {
            if ($methodName === 'login') {
                return [
                    'success' => true,
                    'data' => [
                        'user' => [
                            'id' => 1,
                            'name' => 'John Doe',
                            'email' => 'user@example.com',
                            'email_verified_at' => '2025-01-15T10:30:00.000000Z',
                            'role' => [
                                'id' => 1,
                                'name' => 'User',
                                'slug' => 'user'
                            ],
                            'permissions' => ['view-dashboard', 'manage-customers']
                        ],
                        'token' => '1|abcdefghijklmnopqrstuvwxyz1234567890',
                        'device' => [
                            'id' => 1,
                            'device_id' => 'device_123456',
                            'device_type' => 'mobile'
                        ]
                    ],
                    'message' => 'Login successful'
                ];
            } elseif ($methodName === 'me') {
                return [
                    'success' => true,
                    'data' => [
                        'user' => [
                            'id' => 1,
                            'name' => 'John Doe',
                            'email' => 'user@example.com',
                            'email_verified' => true,
                            'email_verified_at' => '2025-01-15T10:30:00.000000Z',
                            'role' => [
                                'id' => 1,
                                'name' => 'User',
                                'slug' => 'user'
                            ],
                            'permissions' => ['view-dashboard', 'manage-customers']
                        ]
                    ]
                ];
            }
        } elseif ($controllerName === 'StoreController') {
            if ($methodName === 'show') {
                return [
                    'success' => true,
                    'data' => [
                        'store' => [
                            'id' => 1,
                            'name' => 'Vision Care Center',
                            'logo' => 'http://example.com/storage/stores/logos/logo.png',
                            'email' => 'info@visioncare.com',
                            'phone_number' => '+1234567890',
                            'address' => '123 Main Street, City, State 12345',
                            'created_at' => '2025-01-15T10:30:00.000000Z',
                            'updated_at' => '2025-01-15T10:30:00.000000Z'
                        ]
                    ]
                ];
            } elseif ($methodName === 'store') {
                return [
                    'success' => true,
                    'message' => 'Store created successfully.',
                    'data' => [
                        'store' => [
                            'id' => 1,
                            'name' => 'Vision Care Center',
                            'logo' => 'http://example.com/storage/stores/logos/logo.png',
                            'email' => 'info@visioncare.com',
                            'phone_number' => '+1234567890',
                            'address' => '123 Main Street, City, State 12345',
                            'created_at' => '2025-01-15T10:30:00.000000Z',
                            'updated_at' => '2025-01-15T10:30:00.000000Z'
                        ]
                    ]
                ];
            }
        } elseif ($controllerName === 'CustomerController') {
            if ($methodName === 'index') {
                return [
                    'success' => true,
                    'data' => [
                        'customers' => [
                            [
                                'id' => 1,
                                'store_id' => 1,
                                'name' => 'Jane Smith',
                                'email' => 'jane.smith@example.com',
                                'phone_number' => '+1987654321',
                                'address' => '456 Oak Avenue, City, State 54321',
                                'created_at' => '2025-01-15T10:30:00.000000Z',
                                'updated_at' => '2025-01-15T10:30:00.000000Z'
                            ]
                        ],
                        'pagination' => [
                            'current_page' => 1,
                            'last_page' => 1,
                            'per_page' => 15,
                            'total' => 1,
                            'from' => 1,
                            'to' => 1
                        ]
                    ]
                ];
            } elseif ($methodName === 'store') {
                return [
                    'success' => true,
                    'message' => 'Customer created successfully.',
                    'data' => [
                        'customer' => [
                            'id' => 1,
                            'store_id' => 1,
                            'name' => 'Jane Smith',
                            'email' => 'jane.smith@example.com',
                            'phone_number' => '+1987654321',
                            'address' => '456 Oak Avenue, City, State 54321',
                            'created_at' => '2025-01-15T10:30:00.000000Z',
                            'updated_at' => '2025-01-15T10:30:00.000000Z'
                        ]
                    ]
                ];
            }
        } elseif ($controllerName === 'EyeExaminationController') {
            if ($methodName === 'store') {
                return [
                    'success' => true,
                    'message' => 'Eye examination created successfully.',
                    'data' => [
                        'eye_examination' => [
                            'id' => 1,
                            'customer_id' => 1,
                            'customer' => [
                                'id' => 1,
                                'name' => 'Jane Smith',
                                'email' => 'jane.smith@example.com',
                                'phone_number' => '+1987654321'
                            ],
                            'store_id' => 1,
                            'exam_date' => '2025-01-15',
                            'chief_complaint' => 'Blurred vision',
                            'old_rx_date' => '2024-01-10',
                            'od_sphere' => -2.50,
                            'od_cylinder' => -0.75,
                            'od_axis' => 90,
                            'os_sphere' => -2.25,
                            'os_cylinder' => -0.50,
                            'os_axis' => 85,
                            'add_power' => 1.50,
                            'pd_distance' => 64,
                            'pd_near' => 62,
                            'od_bcva' => '6/6',
                            'os_bcva' => '6/6',
                            'iop_od' => 15,
                            'iop_os' => 16,
                            'fundus_notes' => 'Normal fundus',
                            'diagnosis' => 'Myopia with astigmatism',
                            'management_plan' => 'Prescribe glasses',
                            'next_recall_date' => '2026-01-15',
                            'pdf_download_url' => 'http://example.com/api/eye-examinations/1/download-pdf'
                        ]
                    ]
                ];
            }
        } elseif ($controllerName === 'SettingController') {
            if ($methodName === 'index') {
                return [
                    'success' => true,
                    'data' => [
                        'settings' => [
                            [
                                'id' => 1,
                                'key' => 'app_name',
                                'value' => 'Eyecare',
                                'type' => 'string',
                                'group' => 'general',
                                'is_public' => true,
                                'description' => 'Application name'
                            ]
                        ],
                        'pagination' => [
                            'current_page' => 1,
                            'last_page' => 1,
                            'per_page' => 15,
                            'total' => 1,
                            'from' => 1,
                            'to' => 1
                        ]
                    ],
                    'message' => 'Settings retrieved successfully'
                ];
            }
        }
        
        return [
            'success' => true,
            'data' => []
        ];
    }
    
    /**
     * Generate real error response example.
     */
    private function generateErrorResponse(string $controllerClass, string $methodName, $route): ?array
    {
        $controllerName = class_basename($controllerClass);
        
        // Common validation error
        if (in_array($controllerName, ['StoreController', 'CustomerController', 'EyeExaminationController', 'SettingController'])) {
            return [
                'success' => false,
                'message' => 'The provided data is invalid.'
            ];
        }
        
        // Auth errors
        if ($controllerName === 'AuthController') {
            if ($methodName === 'login') {
                return [
                    'success' => false,
                    'message' => 'The provided credentials are incorrect.'
                ];
            }
        }
        
        // Not found errors
        return [
            'success' => false,
            'message' => 'Resource not found.'
        ];
    }

    /**
     * Generate basic documentation when PHPDoc is missing.
     * 
     * @param string $methodName
     * @param \Illuminate\Routing\Route $route
     * @return array
     */
    private function generateBasicDocumentation(string $methodName, $route): array
    {
        return [
            'description' => ucfirst(str_replace(['_', '-'], ' ', $methodName)) . ' endpoint.',
            'parameters' => [],
            'query_parameters' => [],
            'payload_example' => null,
            'success_response' => null,
            'error_response' => null,
            'status_codes' => $this->getDefaultStatusCodes(strtoupper($route->methods()[0] ?? 'GET')),
        ];
    }

    /**
     * Parse PHPDoc comment into structured data.
     * 
     * @param string $docComment
     * @param \Illuminate\Routing\Route $route
     * @return array
     */
    private function parseDocComment(string $docComment, $route): array
    {
        $doc = [
            'description' => '',
            'parameters' => [],
            'query_parameters' => [],
            'payload_example' => null,
            'success_response' => null,
            'error_response' => null,
            'status_codes' => [],
        ];

        // Extract description (text before @param, @return, @example, etc.)
        $lines = explode("\n", $docComment);
        $descriptionLines = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Skip comment markers
            $line = preg_replace('/^\s*\*\s*/', '', $line);
            $line = preg_replace('/^\s*\/\*\*\s*/', '', $line);
            $line = preg_replace('/^\s*\*\/\s*$/', '', $line);
            
            // Stop at tags
            if (preg_match('/^@(param|return|example|status|query)/', $line)) {
                break;
            }
            
            if (!empty($line)) {
                $descriptionLines[] = $line;
            }
        }
        
        $doc['description'] = trim(implode(' ', $descriptionLines));

        // Extract @param tags (body parameters)
        if (preg_match_all('/@param\s+(\S+)\s+\$(\w+)\s+(.*?)(?=@|\*\/|$)/s', $docComment, $matches)) {
            foreach ($matches[0] as $index => $match) {
                $type = $matches[1][$index] ?? '';
                $name = $matches[2][$index] ?? '';
                $description = trim($matches[3][$index] ?? '');
                
                if (!empty($name)) {
                    $doc['parameters'][$name] = [
                        'type' => $type,
                        'description' => $description,
                        'required' => !str_contains($description, 'optional') && !str_contains($description, 'nullable'),
                    ];
                }
            }
        }

        // Extract @query tags (query parameters)
        if (preg_match_all('/@query\s+(\S+)\s+(\S+)\s+(.*?)(?=@|\*\/|$)/s', $docComment, $matches)) {
            foreach ($matches[0] as $index => $match) {
                $type = $matches[1][$index] ?? 'string';
                $name = $matches[2][$index] ?? '';
                $description = trim($matches[3][$index] ?? '');
                
                if (!empty($name)) {
                    $doc['query_parameters'][$name] = [
                        'type' => $type,
                        'description' => $description,
                        'required' => !str_contains($description, 'optional'),
                    ];
                }
            }
        }

        // Extract @example payload
        if (preg_match('/@example\s+payload\s*\n\s*\*\s*(.*?)(?=@example|@return|@status|\*\/|$)/s', $docComment, $matches)) {
            $example = trim($matches[1]);
            $example = preg_replace('/^\s*\*\s*/m', '', $example);
            // Remove GET /api/... format if present
            if (preg_match('/^GET\s+/', $example)) {
                $doc['payload_example'] = $example; // Keep as string for GET requests
            } else {
                // Try to decode as JSON, if fails use as string
                $decoded = json_decode($example, true);
                $doc['payload_example'] = ($decoded !== null && json_last_error() === JSON_ERROR_NONE) ? $decoded : $example;
            }
        }

        // Extract @example success_response
        if (preg_match('/@example\s+success_response\s*\n\s*\*\s*(.*?)(?=@example|@return|@status|\*\/|$)/s', $docComment, $matches)) {
            $example = trim($matches[1]);
            $example = preg_replace('/^\s*\*\s*/m', '', $example);
            // Try to decode as JSON, if fails use as string
            $decoded = json_decode($example, true);
            $doc['success_response'] = ($decoded !== null && json_last_error() === JSON_ERROR_NONE) ? $decoded : $example;
        }

        // Extract @example error_response
        if (preg_match('/@example\s+error_response\s*\n\s*\*\s*(.*?)(?=@example|@return|@status|\*\/|$)/s', $docComment, $matches)) {
            $example = trim($matches[1]);
            $example = preg_replace('/^\s*\*\s*/m', '', $example);
            // Try to decode as JSON, if fails use as string
            $decoded = json_decode($example, true);
            $doc['error_response'] = ($decoded !== null && json_last_error() === JSON_ERROR_NONE) ? $decoded : $example;
        }

        // Extract @status tags
        if (preg_match_all('/@status\s+(\d+)\s+(.*?)(?=@status|@return|@example|\*\/|$)/s', $docComment, $matches)) {
            foreach ($matches[1] as $index => $code) {
                $message = trim($matches[2][$index]);
                $message = preg_replace('/^\s*\*\s*/m', '', $message);
                $doc['status_codes'][(int)$code] = trim($message);
            }
        }

        // If no status codes, use defaults
        if (empty($doc['status_codes'])) {
            $doc['status_codes'] = $this->getDefaultStatusCodes(strtoupper($route->methods()[0] ?? 'GET'));
        }

        return $doc;
    }

    /**
     * Get default headers for an endpoint.
     * 
     * @param string $method
     * @param bool $requiresAuth
     * @return array
     */
    private function getHeaders(string $method, bool $requiresAuth): array
    {
        $headers = [
            'Content-Type' => [
                'type' => 'string',
                'required' => in_array($method, ['POST', 'PUT', 'PATCH']),
                'description' => 'Content type of the request body',
                'example' => 'application/json',
            ],
        ];

        if ($requiresAuth) {
            $headers['Authorization'] = [
                'type' => 'string',
                'required' => true,
                'description' => 'Bearer token for authentication',
                'example' => 'Bearer YOUR_ACCESS_TOKEN',
            ];
        }

        $headers['Accept'] = [
            'type' => 'string',
            'required' => false,
            'description' => 'Expected response format',
            'example' => 'application/json',
        ];

        return $headers;
    }

    /**
     * Get default status codes for a method.
     * 
     * @param string $method
     * @return array
     */
    private function getDefaultStatusCodes(string $method): array
    {
        $codes = [
            200 => 'Success',
            400 => 'Bad Request - Invalid input or validation error',
            401 => 'Unauthorized - Authentication required or invalid token',
            403 => 'Forbidden - Insufficient permissions',
            404 => 'Not Found - Resource not found',
            422 => 'Unprocessable Entity - Validation failed',
            500 => 'Internal Server Error',
        ];

        if ($method === 'POST') {
            $codes[201] = 'Created - Resource successfully created';
        }

        return $codes;
    }

    /**
     * Determine the group for an endpoint.
     * 
     * @param string $controllerClass
     * @param string $uri
     * @return string
     */
    private function determineGroup(string $controllerClass, string $uri): string
    {
        // Extract controller name
        $controllerName = class_basename($controllerClass);
        
        // Remove "Controller" suffix
        $controllerName = str_replace('Controller', '', $controllerName);
        
        // Map to friendly names
        $groupMap = [
            'Auth' => 'Authentication',
            'Store' => 'Stores',
            'Customer' => 'Customers',
            'EyeExamination' => 'Eye Examinations',
            'Setting' => 'Settings',
        ];
        
        return $groupMap[$controllerName] ?? $controllerName;
    }

    /**
     * Check if route requires authentication.
     * 
     * @param \Illuminate\Routing\Route $route
     * @return bool
     */
    private function requiresAuth($route): bool
    {
        $middleware = $route->gatherMiddleware();
        return in_array('auth:sanctum', $middleware) || in_array('auth', $middleware);
    }

    /**
     * Get icon for a group.
     * 
     * @param string $group
     * @return string
     */
    private function getGroupIcon(string $group): string
    {
        $icons = [
            'Authentication' => 'lock',
            'Stores' => 'store',
            'Customers' => 'users',
            'Eye Examinations' => 'eye',
            'Settings' => 'settings',
        ];
        
        return $icons[$group] ?? 'api';
    }

    /**
     * Get description for a group.
     * 
     * @param string $group
     * @return string
     */
    private function getGroupDescription(string $group): string
    {
        $descriptions = [
            'Authentication' => 'User authentication, registration, email verification, and password management endpoints.',
            'Stores' => 'Store management endpoints for creating and managing store information.',
            'Customers' => 'Customer management endpoints for CRUD operations on customer records.',
            'Eye Examinations' => 'Eye examination management endpoints for creating, viewing, and managing eye examination records.',
            'Settings' => 'Application settings management endpoints for system configuration.',
        ];
        
        return $descriptions[$group] ?? "Endpoints for {$group}.";
    }

    /**
     * Generate Postman collection JSON.
     * 
     * @param string $baseUrl The base API URL
     * @return array
     */
    public function generatePostmanCollection(string $baseUrl): array
    {
        $endpoints = $this->getApiEndpoints($baseUrl);
        
        $items = [];
        foreach ($endpoints as $group) {
            $groupItems = [];
            foreach ($group['endpoints'] as $endpoint) {
                $item = [
                    'name' => $endpoint['name'],
                    'request' => [
                        'method' => $endpoint['method'],
                        'header' => [],
                        'url' => [
                            'raw' => $endpoint['url'],
                            'host' => [parse_url($baseUrl, PHP_URL_HOST)],
                            'path' => explode('/', trim(parse_url($endpoint['url'], PHP_URL_PATH), '/')),
                        ],
                    ],
                ];
                
                // Add headers
                foreach ($endpoint['headers'] as $headerName => $headerData) {
                    if ($headerData['required'] ?? false) {
                        $item['request']['header'][] = [
                            'key' => $headerName,
                            'value' => $headerData['example'] ?? '',
                            'type' => 'text',
                        ];
                    }
                }
                
                if ($endpoint['payload_example']) {
                    $item['request']['body'] = [
                        'mode' => 'raw',
                        'raw' => json_encode($endpoint['payload_example'], JSON_PRETTY_PRINT),
                        'options' => [
                            'raw' => [
                                'language' => 'json',
                            ],
                        ],
                    ];
                }
                
                $groupItems[] = $item;
            }
            
            $items[] = [
                'name' => $group['group'],
                'item' => $groupItems,
            ];
        }
        
        return [
            'info' => [
                'name' => 'Eyecare API',
                'description' => 'API documentation for Eyecare application',
                'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
                'version' => '1.0.0',
            ],
            'item' => $items,
        ];
    }
}
