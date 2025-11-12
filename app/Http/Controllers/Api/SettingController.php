<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreSettingRequest;
use App\Http\Requests\Api\UpdateSettingRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\Request;

/**
 * Setting API Controller
 * 
 * Handles API requests for settings management.
 * 
 * @package App\Http\Controllers\Api
 */
class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param SettingService $settingService
     */
    public function __construct(
        private SettingService $settingService
    ) {}

    /**
     * Get all settings (admin only, or public settings for guests).
     * 
     * Retrieves a list of settings. If authenticated, returns all settings with pagination support.
     * If not authenticated, returns only public settings.
     *
     * @param Request $request Query parameters: search (string, optional), group (string, optional), type (string, optional), sort_by (string, optional, default: 'key'), sort_order (string, optional, default: 'asc'), paginated (boolean, optional, default: true), per_page (integer, optional, default: 15, max: 100)
     * @return \Illuminate\Http\JsonResponse
     * 
     * @example payload
     * GET /api/settings?search=app&group=general&type=string&paginated=true&per_page=15
     * 
     * @example success_response
     * {
     *   "success": true,
     *   "data": {
     *     "settings": [
     *       {
     *         "id": 1,
     *         "key": "app_name",
     *         "value": "Eyecare",
     *         "type": "string",
     *         "group": "general",
     *         "is_public": true,
     *         "description": "Application name"
     *       }
     *     ],
     *     "pagination": {
     *       "current_page": 1,
     *       "last_page": 1,
     *       "per_page": 15,
     *       "total": 1,
     *       "from": 1,
     *       "to": 1
     *     }
     *   },
     *   "message": "Settings retrieved successfully"
     * }
     * 
     * @example error_response
     * {
     *   "success": false,
     *   "error_code": "UNAUTHORIZED",
     *   "message": "Unauthenticated."
     * }
     * 
     * @status 200 Success
     * @status 401 Unauthorized (if authentication required but not provided)
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'group' => $request->get('group'),
            'type' => $request->get('type'),
            'sort_by' => $request->get('sort_by', 'key'),
            'sort_order' => $request->get('sort_order', 'asc'),
        ];

        // If not authenticated, only show public settings
        if (!$request->user()) {
            $settings = Setting::public()->get();
        } else {
            $paginated = filter_var($request->get('paginated', true), FILTER_VALIDATE_BOOLEAN);
            $perPage = min($request->get('per_page', 15), 100);
            $settings = $this->settingService->getSettings($filters, $paginated, $perPage);
        }

        if ($settings instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            return response()->json([
                'success' => true,
                'data' => [
                    'settings' => SettingResource::collection($settings->items()),
                    'pagination' => [
                        'current_page' => $settings->currentPage(),
                        'last_page' => $settings->lastPage(),
                        'per_page' => $settings->perPage(),
                        'total' => $settings->total(),
                        'from' => $settings->firstItem(),
                        'to' => $settings->lastItem(),
                    ],
                ],
                'message' => 'Settings retrieved successfully',
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'settings' => SettingResource::collection($settings),
                'total' => $settings->count(),
            ],
            'message' => 'Settings retrieved successfully',
        ], 200);
    }

    /**
     * Create a new setting (admin only).
     * 
     * Creates a new application setting. Requires authentication and admin privileges.
     *
     * @param StoreSettingRequest $request Request body containing: key (string, required, unique, alphanumeric and underscores only), value (string, optional), type (string, required, one of: string, integer, boolean, json, text, float), group (string, required, max: 100), description (string, optional, max: 500), is_public (boolean, optional)
     * @return \Illuminate\Http\JsonResponse
     * 
     * @example payload
     * {
     *   "key": "app_name",
     *   "value": "Eyecare",
     *   "type": "string",
     *   "group": "general",
     *   "description": "Application name",
     *   "is_public": true
     * }
     * 
     * @example success_response
     * {
     *   "success": true,
     *   "data": {
     *     "setting": {
     *       "id": 1,
     *       "key": "app_name",
     *       "value": "Eyecare",
     *       "type": "string",
     *       "group": "general",
     *       "is_public": true,
     *       "description": "Application name",
     *       "created_at": "2025-01-15T10:30:00.000000Z",
     *       "updated_at": "2025-01-15T10:30:00.000000Z"
     *     }
     *   },
     *   "message": "Setting created successfully"
     * }
     * 
     * @example error_response
     * {
     *   "success": false,
     *   "error_code": "SETTING_CREATE_ERROR",
     *   "message": "A setting with this key already exists."
     * }
     * 
     * @status 201 Created
     * @status 400 Bad Request (validation errors or creation failed)
     * @status 401 Unauthorized
     * @status 422 Validation Error
     */
    public function store(StoreSettingRequest $request)
    {
        try {
            $setting = $this->settingService->createSetting($request->validated());
            
            return response()->json([
                'success' => true,
                'data' => [
                    'setting' => new SettingResource($setting),
                ],
                'message' => 'Setting created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error_code' => 'SETTING_CREATE_ERROR',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get a specific setting.
     * 
     * Retrieves a single setting by ID. Public settings are accessible without authentication.
     * Private settings require authentication.
     *
     * @param Request $request
     * @param Setting $setting The setting model (route parameter)
     * @return \Illuminate\Http\JsonResponse
     * 
     * @example payload
     * GET /api/settings/1
     * 
     * @example success_response
     * {
     *   "success": true,
     *   "data": {
     *     "setting": {
     *       "id": 1,
     *       "key": "app_name",
     *       "value": "Eyecare",
     *       "type": "string",
     *       "group": "general",
     *       "is_public": true,
     *       "description": "Application name",
     *       "created_at": "2025-01-15T10:30:00.000000Z",
     *       "updated_at": "2025-01-15T10:30:00.000000Z"
     *     }
     *   }
     * }
     * 
     * @example error_response
     * {
     *   "success": false,
     *   "error_code": "UNAUTHORIZED",
     *   "message": "This setting is not publicly accessible"
     * }
     * 
     * @status 200 Success
     * @status 403 Forbidden (private setting accessed without authentication)
     * @status 404 Not Found
     */
    public function show(Request $request, Setting $setting)
    {
        // If not authenticated, only allow public settings
        if (!$request->user() && !$setting->is_public) {
            return response()->json([
                'success' => false,
                'error_code' => 'UNAUTHORIZED',
                'message' => 'This setting is not publicly accessible',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'setting' => new SettingResource($setting),
            ],
        ], 200);
    }

    /**
     * Update a setting (admin only).
     * 
     * Updates an existing setting. All fields are optional (use 'sometimes' validation).
     * Requires authentication and admin privileges.
     *
     * @param UpdateSettingRequest $request Request body containing: key (string, optional, unique), value (string, optional), type (string, optional), group (string, optional), description (string, optional), is_public (boolean, optional)
     * @param Setting $setting The setting model (route parameter)
     * @return \Illuminate\Http\JsonResponse
     * 
     * @example payload
     * {
     *   "value": "Eyecare Pro",
     *   "description": "Updated application name"
     * }
     * 
     * @example success_response
     * {
     *   "success": true,
     *   "data": {
     *     "setting": {
     *       "id": 1,
     *       "key": "app_name",
     *       "value": "Eyecare Pro",
     *       "type": "string",
     *       "group": "general",
     *       "is_public": true,
     *       "description": "Updated application name",
     *       "created_at": "2025-01-15T10:30:00.000000Z",
     *       "updated_at": "2025-01-15T11:00:00.000000Z"
     *     }
     *   },
     *   "message": "Setting updated successfully"
     * }
     * 
     * @example error_response
     * {
     *   "success": false,
     *   "error_code": "SETTING_UPDATE_ERROR",
     *   "message": "A setting with this key already exists."
     * }
     * 
     * @status 200 Success
     * @status 400 Bad Request (update failed)
     * @status 401 Unauthorized
     * @status 404 Not Found
     * @status 422 Validation Error
     */
    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        try {
            $setting = $this->settingService->updateSetting($setting->id, $request->validated());
            
            return response()->json([
                'success' => true,
                'data' => [
                    'setting' => new SettingResource($setting),
                ],
                'message' => 'Setting updated successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error_code' => 'SETTING_UPDATE_ERROR',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete a setting (admin only).
     * 
     * Permanently deletes a setting. Requires authentication and admin privileges.
     *
     * @param Request $request
     * @param Setting $setting The setting model (route parameter)
     * @return \Illuminate\Http\JsonResponse
     * 
     * @example payload
     * DELETE /api/settings/1
     * 
     * @example success_response
     * {
     *   "success": true,
     *   "message": "Setting deleted successfully"
     * }
     * 
     * @example error_response
     * {
     *   "success": false,
     *   "error_code": "SETTING_DELETE_ERROR",
     *   "message": "Failed to delete setting."
     * }
     * 
     * @status 200 Success
     * @status 400 Bad Request (delete failed)
     * @status 401 Unauthorized
     * @status 404 Not Found
     */
    public function destroy(Request $request, Setting $setting)
    {
        try {
            $this->settingService->deleteSetting($setting->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Setting deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error_code' => 'SETTING_DELETE_ERROR',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get settings by group.
     * 
     * Retrieves all settings belonging to a specific group. Public settings are accessible without authentication.
     * Private settings require authentication.
     *
     * @param Request $request
     * @param string $group The setting group name (route parameter)
     * @return \Illuminate\Http\JsonResponse
     * 
     * @example payload
     * GET /api/settings/group/general
     * 
     * @example success_response
     * {
     *   "success": true,
     *   "data": {
     *     "settings": [
     *       {
     *         "id": 1,
     *         "key": "app_name",
     *         "value": "Eyecare",
     *         "type": "string",
     *         "group": "general",
     *         "is_public": true
     *       }
     *     ],
     *     "group": "general"
     *   }
     * }
     * 
     * @example error_response
     * {
     *   "success": false,
     *   "error_code": "NOT_FOUND",
     *   "message": "No settings found for this group."
     * }
     * 
     * @status 200 Success
     * @status 404 Not Found (group doesn't exist or no settings in group)
     */
    public function getByGroup(Request $request, string $group)
    {
        $settings = $this->settingService->getSettingsByGroup($group);
        
        // Filter public settings if not authenticated
        if (!$request->user()) {
            $settings = $settings->where('is_public', true);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'settings' => SettingResource::collection($settings),
                'group' => $group,
            ],
        ], 200);
    }
}

