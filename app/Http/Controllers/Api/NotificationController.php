<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

/**
 * Notification Controller
 * 
 * Handles API requests for notifications.
 * 
 * @package App\Http\Controllers\Api
 */
class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param NotificationService $notificationService
     */
    public function __construct(
        private NotificationService $notificationService
    ) {}

    /**
     * Get today's notifications for the authenticated user.
     * 
     * Retrieves all notifications relevant to the logged-in user that were created today:
     * - Notifications sent to all users (type='all')
     * - Notifications sent specifically to this user (type='user')
     * - Notifications sent to the user's store (type='store')
     * 
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "notifications": [
     *       {
     *         "id": 1,
     *         "title": "Welcome",
     *         "body": "Welcome to our platform",
     *         "data": {},
     *         "type": "all",
     *         "user_id": null,
     *         "store_id": null,
     *         "status": "completed",
     *         "created_at": "2025-01-15T10:30:00.000000Z",
     *         "updated_at": "2025-01-15T10:30:00.000000Z"
     *       }
     *     ],
     *     "count": 1
     *   },
     *   "message": "Notifications retrieved successfully"
     * }
     * 
     * @response 401 {
     *   "message": "Unauthenticated."
     * }
     */
    public function getTodayNotifications(Request $request)
    {
        $user = $request->user();
        
        // Check if user is blocked
        if ($user->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been blocked. Please contact support.',
            ], 403);
        }

        $notifications = $this->notificationService->getTodayNotificationsForUser($user->id);

        return response()->json([
            'success' => true,
            'data' => [
                'notifications' => $notifications->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'body' => $notification->body,
                        'data' => $notification->data ?? [],
                        'type' => $notification->type,
                        'user_id' => $notification->user_id,
                        'store_id' => $notification->store_id,
                        'status' => $notification->status,
                        'created_at' => $notification->created_at->toIso8601String(),
                        'updated_at' => $notification->updated_at->toIso8601String(),
                    ];
                }),
                'count' => $notifications->count(),
            ],
            'message' => 'Notifications retrieved successfully',
            'timestamp' => now()->toIso8601String(),
        ], 200);
    }
}

