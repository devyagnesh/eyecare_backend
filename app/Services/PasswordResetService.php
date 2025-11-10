<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class PasswordResetService
{
    /**
     * Send password reset link to user.
     *
     * @param string $email
     * @return array
     * @throws \Exception
     */
    public function sendResetLink(string $email): array
    {
        // Rate limiting: max 3 requests per 15 minutes per email
        $key = 'password-reset:' . $email;
        $maxAttempts = 3;
        $decayMinutes = 15;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            throw new \Exception(
                'Too many password reset requests. Please try again in ' . ceil($seconds / 60) . ' minutes.',
                429
            );
        }

        try {
            // Find user by email
            $user = User::where('email', $email)->first();

            // Always return success message for security (don't reveal if email exists)
            if (!$user) {
                // Still increment rate limiter to prevent email enumeration
                RateLimiter::hit($key, $decayMinutes * 60);
                
                Log::warning('Password reset requested for non-existent email', [
                    'email' => $email,
                    'ip' => request()->ip(),
                ]);

                return [
                    'success' => true,
                    'message' => 'If that email address exists in our system, we have sent a password reset link.',
                ];
            }

            // Increment rate limiter
            RateLimiter::hit($key, $decayMinutes * 60);

            // Generate password reset token
            $token = Str::random(64);
            
            // Store token in database (using Laravel's password reset tokens table)
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => now(),
                ]
            );

            // Send password reset notification
            $user->notify(new PasswordResetNotification($token));

            Log::info('Password reset link sent successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return [
                'success' => true,
                'message' => 'If that email address exists in our system, we have sent a password reset link.',
            ];
        } catch (\Exception $e) {
            Log::error('Failed to send password reset link', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Failed to send password reset link. Please try again later.', 500);
        }
    }

    /**
     * Reset user password using token.
     *
     * @param string $email
     * @param string $token
     * @param string $password
     * @return array
     * @throws \Exception
     */
    public function resetPassword(string $email, string $token, string $password): array
    {
        // Find user by email
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new \Exception('Invalid reset token.', 400);
        }

        // Get password reset record
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetRecord) {
            throw new \Exception('Invalid or expired reset token.', 400);
        }

        // Check if token is expired (60 minutes)
        $tokenAge = now()->diffInMinutes($resetRecord->created_at);
        if ($tokenAge > 60) {
            // Delete expired token
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            throw new \Exception('Reset token has expired. Please request a new password reset link.', 400);
        }

        // Verify token
        if (!Hash::check($token, $resetRecord->token)) {
            throw new \Exception('Invalid reset token.', 400);
        }

        try {
            // Update user password
            $user->password = Hash::make($password);
            $user->save();

            // Delete used token
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            // Revoke all user tokens (force re-login)
            $user->tokens()->delete();

            Log::info('Password reset successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return [
                'success' => true,
                'message' => 'Password has been reset successfully. Please login with your new password.',
            ];
        } catch (\Exception $e) {
            Log::error('Failed to reset password', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Failed to reset password. Please try again later.', 500);
        }
    }
}

