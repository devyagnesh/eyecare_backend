<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ForgotPasswordRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\ResendVerificationEmailRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\Role;
use App\Services\EmailVerificationService;
use App\Services\PasswordResetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    /**
     * Login user and create/update device record.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_id' => 'nullable|string|max:255',
            'device_type' => 'nullable|string|in:mobile,tablet,desktop',
            'device_name' => 'nullable|string|max:255',
            'os_name' => 'nullable|string|max:255',
            'os_version' => 'nullable|string|max:255',
            'browser_name' => 'nullable|string|max:255',
            'browser_version' => 'nullable|string|max:255',
            'notification_token' => 'nullable|string',
            'notification_platform' => 'nullable|string|in:fcm,apns,web-push',
        ]);

        $user = User::with('role.permissions')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create or update device record
        $device = $this->createOrUpdateDevice($user, $request);

        // Create API token
        $token = $user->createToken($request->device_name ?? 'api-token', ['*'])->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->toIso8601String() : null,
                    'role' => $user->role ? [
                        'id' => $user->role->id,
                        'name' => $user->role->name,
                        'slug' => $user->role->slug,
                    ] : null,
                    'permissions' => $user->permissions()->pluck('slug')->toArray(),
                ],
                'token' => $token,
                'device' => [
                    'id' => $device->id,
                    'device_id' => $device->device_id,
                    'device_type' => $device->device_type,
                ],
            ],
            'message' => 'Login successful',
        ], 200);
    }

    /**
     * Register a new user (if signup is needed in future).
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        // Get default "user" role if role_id not provided
        $roleId = $validated['role_id'] ?? null;
        if (!$roleId) {
            $userRole = Role::where('slug', 'user')->first();
            if (!$userRole) {
                return response()->json([
                    'success' => false,
                    'message' => 'Default user role not found. Please run database seeder.',
                ], 500);
            }
            $roleId = $userRole->id;
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $roleId,
        ]);

        $user->load('role.permissions');

        // Send email verification notification
        try {
            $user->sendEmailVerificationNotification();
            \Log::info('Verification email sent successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail registration
            \Log::error('Failed to send verification email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        // Create or update device record
        $device = $this->createOrUpdateDevice($user, $request);

        // Create API token
        $token = $user->createToken($validated['device_name'] ?? 'api-token', ['*'])->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->toIso8601String() : null,
                    'role' => $user->role ? [
                        'id' => $user->role->id,
                        'name' => $user->role->name,
                        'slug' => $user->role->slug,
                    ] : null,
                    'permissions' => $user->permissions()->pluck('slug')->toArray(),
                ],
                'token' => $token,
                'device' => [
                    'id' => $device->id,
                    'device_id' => $device->device_id,
                    'device_type' => $device->device_type,
                ],
            ],
            'message' => 'Registration successful. Please check your email to verify your account.',
        ], 201);
    }

    /**
     * Logout user and optionally revoke device token.
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        // Optionally deactivate device
        if ($request->has('device_id')) {
            UserDevice::where('user_id', $user->id)
                ->where('device_id', $request->device_id)
                ->update(['is_active' => false]);
        }

        // Revoke current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ], 200);
    }

    /**
     * Get authenticated user information.
     */
    public function me(Request $request)
    {
        $user = $request->user()->load('role.permissions');

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified' => $user->hasVerifiedEmail(),
                    'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->toIso8601String() : null,
                    'role' => $user->role ? [
                        'id' => $user->role->id,
                        'name' => $user->role->name,
                        'slug' => $user->role->slug,
                    ] : null,
                    'permissions' => $user->permissions()->pluck('slug')->toArray(),
                ],
            ],
        ], 200);
    }

    /**
     * Check if the authenticated user's email is verified.
     */
    public function checkEmailVerification(Request $request, EmailVerificationService $emailVerificationService)
    {
        $user = $request->user();
        $data = $emailVerificationService->checkEmailVerification($user);

        return response()->json([
            'success' => true,
            'data' => array_merge($data, ['email' => $user->email]),
        ], 200);
    }

    /**
     * Verify user's email address via API.
     */
    public function verifyEmail(Request $request, EmailVerificationService $emailVerificationService)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'hash' => 'required|string',
        ]);

        $user = User::findOrFail($request->id);

        try {
            $result = $emailVerificationService->verifyEmail($user, $request->hash);
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Resend email verification notification.
     */
    public function resendVerificationEmail(ResendVerificationEmailRequest $request, EmailVerificationService $emailVerificationService)
    {
        $user = $request->user();

        try {
            $result = $emailVerificationService->resendVerificationEmail($user);
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Send password reset link.
     */
    public function forgotPassword(ForgotPasswordRequest $request, PasswordResetService $passwordResetService)
    {
        try {
            $result = $passwordResetService->sendResetLink($request->email);
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Reset password using token.
     */
    public function resetPassword(ResetPasswordRequest $request, PasswordResetService $passwordResetService)
    {
        try {
            $result = $passwordResetService->resetPassword(
                $request->email,
                $request->token,
                $request->password
            );
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Update notification token for a device.
     */
    public function updateNotificationToken(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
            'notification_token' => 'required|string',
            'notification_platform' => 'required|string|in:fcm,apns,web-push',
        ]);

        $user = $request->user();

        $device = UserDevice::where('user_id', $user->id)
            ->where('device_id', $request->device_id)
            ->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found',
            ], 404);
        }

        $device->update([
            'notification_token' => $request->notification_token,
            'notification_platform' => $request->notification_platform,
            'is_active' => true,
            'last_active_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification token updated successfully',
            'data' => [
                'device' => [
                    'id' => $device->id,
                    'device_id' => $device->device_id,
                ],
            ],
        ], 200);
    }

    /**
     * Create or update device record.
     */
    private function createOrUpdateDevice(User $user, Request $request): UserDevice
    {
        $deviceData = [
            'user_id' => $user->id,
            'device_id' => $request->device_id ?? $this->generateDeviceId($request),
            'device_type' => $request->device_type ?? $this->detectDeviceType($request),
            'device_name' => $request->device_name,
            'os_name' => $request->os_name ?? $this->detectOS($request),
            'os_version' => $request->os_version,
            'browser_name' => $request->browser_name ?? $this->detectBrowser($request),
            'browser_version' => $request->browser_version,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'notification_token' => $request->notification_token,
            'notification_platform' => $request->notification_platform,
            'is_active' => true,
            'last_active_at' => now(),
        ];

        // Try to find existing device by device_id or create new one
        $device = UserDevice::updateOrCreate(
            [
                'user_id' => $user->id,
                'device_id' => $deviceData['device_id'],
            ],
            $deviceData
        );

        return $device;
    }

    /**
     * Generate a unique device ID if not provided.
     */
    private function generateDeviceId(Request $request): string
    {
        // Use IP + User Agent hash as device ID if not provided
        $unique = $request->ip() . $request->userAgent();
        return 'device_' . md5($unique);
    }

    /**
     * Detect device type from user agent.
     */
    private function detectDeviceType(Request $request): ?string
    {
        $userAgent = strtolower($request->userAgent());
        
        if (preg_match('/mobile|android|iphone|ipad|ipod|blackberry|iemobile|opera mini/i', $userAgent)) {
            if (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
                return 'tablet';
            }
            return 'mobile';
        }
        
        return 'desktop';
    }

    /**
     * Detect OS from user agent.
     */
    private function detectOS(Request $request): ?string
    {
        $userAgent = $request->userAgent();
        
        if (preg_match('/windows/i', $userAgent)) {
            return 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            return 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            return 'Linux';
        } elseif (preg_match('/android/i', $userAgent)) {
            return 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            return 'iOS';
        }
        
        return 'Unknown';
    }

    /**
     * Detect browser from user agent.
     */
    private function detectBrowser(Request $request): ?string
    {
        $userAgent = $request->userAgent();
        
        if (preg_match('/chrome/i', $userAgent) && !preg_match('/edg/i', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/safari/i', $userAgent) && !preg_match('/chrome/i', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/edg/i', $userAgent)) {
            return 'Edge';
        } elseif (preg_match('/opera|opr/i', $userAgent)) {
            return 'Opera';
        }
        
        return 'Unknown';
    }
}