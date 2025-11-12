<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApiDocumentationService;
use Illuminate\Http\Request;

/**
 * API Documentation Controller
 * 
 * Provides comprehensive API documentation with examples and interactive features.
 * 
 * @package App\Http\Controllers\Admin
 * @author Eyecare Admin
 * @version 2.0.0
 */
class ApiDocumentationController extends Controller
{
    /**
     * Create a new controller instance.
     * 
     * @param ApiDocumentationService $apiDocumentationService
     */
    public function __construct(
        private ApiDocumentationService $apiDocumentationService
    ) {}

    /**
     * Display the API documentation page.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $baseUrl = rtrim(config('app.url'), '/') . '/api';
        $endpoints = $this->apiDocumentationService->getApiEndpoints($baseUrl);
        $lastUpdated = now()->format('F d, Y \a\t H:i A');
        $apiVersion = '1.0.0';
        
        return view('admin.api-documentation.index', compact('endpoints', 'lastUpdated', 'apiVersion', 'baseUrl'));
    }

    /**
     * Download Postman collection.
     * 
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadPostmanCollection()
    {
        $baseUrl = rtrim(config('app.url'), '/') . '/api';
        $collection = $this->apiDocumentationService->generatePostmanCollection($baseUrl);
        
        $filename = 'eyecare-api-postman-collection-' . date('Y-m-d') . '.json';
        
        return response()->streamDownload(function() use ($collection) {
            echo json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }
}
