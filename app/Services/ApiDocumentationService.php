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
            
            // If no PHPDoc, create basic documentation
            if (!$docComment) {
                return $this->generateBasicDocumentation($methodName, $route);
            }

            return $this->parseDocComment($docComment, $route);
        } catch (\ReflectionException $e) {
            return $this->generateBasicDocumentation($methodName, $route);
        }
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
            // Try to decode as JSON, if fails use as string
            $decoded = json_decode($example, true);
            $doc['payload_example'] = ($decoded !== null && json_last_error() === JSON_ERROR_NONE) ? $decoded : $example;
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
