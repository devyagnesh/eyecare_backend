<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;

/**
 * Notification Service
 * 
 * Handles business logic for sending push notifications via Firebase Cloud Messaging.
 * 
 * @package App\Services
 */
class NotificationService
{
    private $messaging;
    private $projectId;

    /**
     * Create a new service instance.
     */
    public function __construct()
    {
        // Get credentials path from config (which reads from .env)
        $credentialsPath = config('services.firebase.credentials_path');
        
        // If path is relative, make it absolute from storage/app
        if (!str_starts_with($credentialsPath, '/') && !str_starts_with($credentialsPath, storage_path())) {
            $credentialsPath = storage_path('app/' . ltrim($credentialsPath, '/'));
        }
        
        // If path is already absolute, use it as is
        if (!file_exists($credentialsPath)) {
            throw new \Exception(
                'Firebase credentials file not found at: ' . $credentialsPath . '. ' .
                'Please set FIREBASE_CREDENTIALS_PATH in .env file. ' .
                'Example: FIREBASE_CREDENTIALS_PATH=firebase/firebase-credentials.json ' .
                'or use absolute path: FIREBASE_CREDENTIALS_PATH=/path/to/credentials.json'
            );
        }

        // Read and validate credentials file
        $credentials = json_decode(file_get_contents($credentialsPath), true);
        if (!$credentials || !isset($credentials['project_id'])) {
            throw new \Exception('Invalid Firebase credentials file. Missing project_id.');
        }
        
        $this->projectId = $credentials['project_id'];
        
        Log::info('Firebase initialized', [
            'project_id' => $this->projectId,
            'credentials_path' => $credentialsPath,
        ]);

        $factory = (new Factory)->withServiceAccount($credentialsPath);
        $this->messaging = $factory->createMessaging();
    }

    /**
     * Get the Firebase project ID.
     *
     * @return string
     */
    public function getProjectId(): string
    {
        return $this->projectId;
    }

    /**
     * Send notification to all users.
     *
     * @param string $title
     * @param string $body
     * @param array $data
     * @param int $createdBy
     * @return Notification
     */
    public function sendToAll(string $title, string $body, array $data = [], int $createdBy = null): Notification
    {
        $notification = Notification::create([
            'title' => $title,
            'body' => $body,
            'data' => $data,
            'type' => 'all',
            'status' => 'sending',
            'created_by' => $createdBy,
        ]);

        try {
            // Query devices with non-empty notification tokens
            // notification_platform can be null or 'fcm' - we'll handle both
            $devices = UserDevice::where('is_active', true)
                ->where(function ($query) {
                    $query->whereNotNull('notification_token')
                        ->where('notification_token', '!=', '');
                })
                ->where(function ($query) {
                    $query->where('notification_platform', 'fcm')
                        ->orWhereNull('notification_platform');
                })
                ->get();

            // If no devices with tokens exist, mark as completed with 0 sent
            if ($devices->isEmpty()) {
                // Debug: Log total devices and devices with tokens for troubleshooting
                $totalDevices = UserDevice::count();
                $devicesWithTokens = UserDevice::whereNotNull('notification_token')
                    ->where('notification_token', '!=', '')
                    ->count();
                $activeDevices = UserDevice::where('is_active', true)->count();
                
                Log::warning('No active devices with notification tokens found', [
                    'total_devices' => $totalDevices,
                    'devices_with_tokens' => $devicesWithTokens,
                    'active_devices' => $activeDevices,
                ]);

                $notification->update([
                    'status' => 'completed',
                    'sent_count' => 0,
                    'failed_count' => 0,
                    'error_message' => 'No active devices with notification tokens found.',
                    'sent_at' => now(),
                ]);

                return $notification;
            }

            $sentCount = 0;
            $failedCount = 0;
            $errors = [];

            foreach ($devices as $device) {
                try {
                    // Default to 'fcm' if platform is null
                    $platform = $device->notification_platform ?? 'fcm';
                    $this->sendToDevice($device->notification_token, $title, $body, $data, $platform);
                    $sentCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                    $errors[] = "Device {$device->id}: " . $e->getMessage();
                    Log::error('Failed to send notification to device', [
                        'device_id' => $device->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $notification->update([
                'status' => $failedCount === 0 ? 'completed' : ($sentCount > 0 ? 'completed' : 'failed'),
                'sent_count' => $sentCount,
                'failed_count' => $failedCount,
                'error_message' => !empty($errors) ? implode('; ', array_slice($errors, 0, 10)) : null,
                'sent_at' => now(),
            ]);

            return $notification;
        } catch (\Exception $e) {
            $notification->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Send notification to a specific user.
     *
     * @param int $userId
     * @param string $title
     * @param string $body
     * @param array $data
     * @param int $createdBy
     * @return Notification
     */
    public function sendToUser(int $userId, string $title, string $body, array $data = [], int $createdBy = null): Notification
    {
        $user = User::findOrFail($userId);

        $notification = Notification::create([
            'title' => $title,
            'body' => $body,
            'data' => $data,
            'type' => 'user',
            'user_id' => $userId,
            'status' => 'sending',
            'created_by' => $createdBy,
        ]);

        try {
            // Query devices with non-empty notification tokens
            // notification_platform can be null or 'fcm' - we'll handle both
            $devices = $user->devices()
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->whereNotNull('notification_token')
                        ->where('notification_token', '!=', '');
                })
                ->where(function ($query) {
                    $query->where('notification_platform', 'fcm')
                        ->orWhereNull('notification_platform');
                })
                ->get();

            // If user has no devices with tokens, mark as completed with 0 sent
            if ($devices->isEmpty()) {
                $notification->update([
                    'status' => 'completed',
                    'sent_count' => 0,
                    'failed_count' => 0,
                    'error_message' => 'User has no active devices with notification tokens.',
                    'sent_at' => now(),
                ]);

                return $notification;
            }

            $sentCount = 0;
            $failedCount = 0;
            $errors = [];

            foreach ($devices as $device) {
                try {
                    // Default to 'fcm' if platform is null
                    $platform = $device->notification_platform ?? 'fcm';
                    $this->sendToDevice($device->notification_token, $title, $body, $data, $platform);
                    $sentCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                    $errors[] = "Device {$device->id}: " . $e->getMessage();
                    Log::error('Failed to send notification to device', [
                        'device_id' => $device->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $notification->update([
                'status' => $failedCount === 0 ? 'completed' : ($sentCount > 0 ? 'completed' : 'failed'),
                'sent_count' => $sentCount,
                'failed_count' => $failedCount,
                'error_message' => !empty($errors) ? implode('; ', array_slice($errors, 0, 10)) : null,
                'sent_at' => now(),
            ]);

            return $notification;
        } catch (\Exception $e) {
            $notification->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Send notification to users of a specific store.
     *
     * @param int $storeId
     * @param string $title
     * @param string $body
     * @param array $data
     * @param int $createdBy
     * @return Notification
     */
    public function sendToStore(int $storeId, string $title, string $body, array $data = [], int $createdBy = null): Notification
    {
        $store = \App\Models\Store::findOrFail($storeId);

        $notification = Notification::create([
            'title' => $title,
            'body' => $body,
            'data' => $data,
            'type' => 'store',
            'store_id' => $storeId,
            'status' => 'sending',
            'created_by' => $createdBy,
        ]);

        try {
            // Query devices with non-empty notification tokens
            // notification_platform can be null or 'fcm' - we'll handle both
            $devices = UserDevice::whereHas('user', function ($query) use ($storeId) {
                $query->whereHas('store', function ($q) use ($storeId) {
                    $q->where('stores.id', $storeId);
                });
            })
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNotNull('notification_token')
                    ->where('notification_token', '!=', '');
            })
            ->where(function ($query) {
                $query->where('notification_platform', 'fcm')
                    ->orWhereNull('notification_platform');
            })
            ->get();

            // If store has no devices with tokens, mark as completed with 0 sent
            if ($devices->isEmpty()) {
                $notification->update([
                    'status' => 'completed',
                    'sent_count' => 0,
                    'failed_count' => 0,
                    'error_message' => 'Store has no active devices with notification tokens.',
                    'sent_at' => now(),
                ]);

                return $notification;
            }

            $sentCount = 0;
            $failedCount = 0;
            $errors = [];

            foreach ($devices as $device) {
                try {
                    // Default to 'fcm' if platform is null
                    $platform = $device->notification_platform ?? 'fcm';
                    $this->sendToDevice($device->notification_token, $title, $body, $data, $platform);
                    $sentCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                    $errors[] = "Device {$device->id}: " . $e->getMessage();
                    Log::error('Failed to send notification to device', [
                        'device_id' => $device->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $notification->update([
                'status' => $failedCount === 0 ? 'completed' : ($sentCount > 0 ? 'completed' : 'failed'),
                'sent_count' => $sentCount,
                'failed_count' => $failedCount,
                'error_message' => !empty($errors) ? implode('; ', array_slice($errors, 0, 10)) : null,
                'sent_at' => now(),
            ]);

            return $notification;
        } catch (\Exception $e) {
            $notification->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Send notification to a specific device token.
     *
     * @param string $token
     * @param string $title
     * @param string $body
     * @param array $data
     * @param string $platform
     * @return void
     * @throws \Exception
     */
    private function sendToDevice(string $token, string $title, string $body, array $data = [], string $platform = 'fcm'): void
    {
        try {
            $notification = FirebaseNotification::create($title, $body);

            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($notification)
                ->withData($data);

            // Platform-specific configurations
            if ($platform === 'fcm') {
                $androidConfig = AndroidConfig::fromArray([
                    'priority' => 'high',
                ]);
                $message = $message->withAndroidConfig($androidConfig);
            }

            $this->messaging->send($message);
        } catch (\Kreait\Firebase\Exception\Messaging\InvalidArgument $e) {
            // Handle invalid token or SenderId mismatch
            $errorMessage = $e->getMessage();
            
            if (stripos($errorMessage, 'SenderId') !== false || stripos($errorMessage, 'mismatch') !== false) {
                $detailedError = 'SenderId mismatch: The FCM token was generated for a different Firebase project. ' .
                    'Backend is using project ID: ' . $this->projectId . '. ' .
                    'Ensure the mobile app uses the same Firebase project. ' .
                    'Check that google-services.json (Android) or GoogleService-Info.plist (iOS) has the same project_id.';
                
                Log::error('FCM SenderId mismatch', [
                    'project_id' => $this->projectId,
                    'token_preview' => substr($token, 0, 20) . '...',
                    'error' => $errorMessage,
                ]);
                
                throw new \Exception($detailedError, 0, $e);
            }
            
            throw $e;
        } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
            // Token is invalid or unregistered
            $errorMessage = 'Invalid or unregistered FCM token. The device may have uninstalled the app or the token has expired.';
            Log::warning('FCM token not found', [
                'token_preview' => substr($token, 0, 20) . '...',
                'error' => $e->getMessage(),
            ]);
            throw new \Exception($errorMessage, 0, $e);
        } catch (\Exception $e) {
            Log::error('FCM send error', [
                'token_preview' => substr($token, 0, 20) . '...',
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
            ]);
            throw $e;
        }
    }

    /**
     * Get all notifications with filters.
     *
     * @param array $filters
     * @param bool $paginated
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getNotifications(array $filters = [], bool $paginated = true, int $perPage = 15)
    {
        $query = Notification::with(['user', 'store', 'creator'])
            ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_order'] ?? 'desc');

        if (isset($filters['type']) && $filters['type']) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if ($paginated) {
            return $query->paginate($perPage)->withQueryString();
        }

        return $query->get();
    }
}

