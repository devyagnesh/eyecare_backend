<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DataExportService;
use Illuminate\Http\Request;

/**
 * Data Export Controller
 * 
 * Handles API requests for data export.
 * 
 * @package App\Http\Controllers\Api
 */
class DataExportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param DataExportService $dataExportService
     */
    public function __construct(
        private DataExportService $dataExportService
    ) {}

    /**
     * Export all data for the authenticated user.
     * 
     * Exports all data related to the authenticated user including:
     * - User profile information
     * - Store information (if exists)
     * - Customers
     * - Eye Examinations
     * - Orders
     * - User Devices
     * - Notifications
     * 
     * @queryParam format string Export format (json). Default: json. Example: json
     * 
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "export_info": {
     *       "exported_at": "2025-11-20T10:30:00.000000Z",
     *       "user_id": 1,
     *       "user_email": "user@example.com",
     *       "version": "1.0"
     *     },
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "user@example.com"
     *     },
     *     "store": {...},
     *     "customers": [...],
     *     "eye_examinations": [...],
     *     "orders": [...],
     *     "devices": [...],
     *     "notifications": [...]
     *   },
     *   "message": "Data exported successfully"
     * }
     * 
     * @response 401 {
     *   "message": "Unauthenticated."
     * }
     * 
     * @response 403 {
     *   "success": false,
     *   "message": "Your account has been blocked. Please contact support."
     * }
     */
    public function exportAll(Request $request)
    {
        $user = $request->user();
        
        // Check if user is blocked
        if ($user->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been blocked. Please contact support.',
            ], 403);
        }

        try {
            $exportData = $this->dataExportService->exportAllData($user);

            $format = $request->get('format', 'json');

            if ($format === 'json') {
                return response()->json([
                    'success' => true,
                    'data' => $exportData,
                    'message' => 'Data exported successfully',
                    'timestamp' => now()->toIso8601String(),
                ], 200)
                ->header('Content-Disposition', 'attachment; filename="eyecare-data-export-' . now()->format('Y-m-d-His') . '.json"')
                ->header('Content-Type', 'application/json');
            }

            // Default to JSON if format is not recognized
            return response()->json([
                'success' => true,
                'data' => $exportData,
                'message' => 'Data exported successfully',
                'timestamp' => now()->toIso8601String(),
            ], 200)
            ->header('Content-Disposition', 'attachment; filename="eyecare-data-export-' . now()->format('Y-m-d-His') . '.json"')
            ->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export data: ' . $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}

