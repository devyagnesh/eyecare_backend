<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;

class EmailVerificationService
{
    /**
     * Resend email verification notification.
     *
     * @param User $user
     * @return array
     * @throws \Exception
     */
    public function resendVerificationEmail(User $user): array
    {
        // Check if email is already verified
        if ($user->hasVerifiedEmail()) {
            throw new \Exception('Email already verified.', 400);
        }

        // Rate limiting: max 3 requests per 15 minutes per user
        $key = 'resend-verification:' . $user->id;
        $maxAttempts = 3;
        $decayMinutes = 15;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            throw new \Exception(
                'Too many verification email requests. Please try again in ' . ceil($seconds / 60) . ' minutes.',
                429
            );
        }

        try {
            // Increment rate limiter
            RateLimiter::hit($key, $decayMinutes * 60);

            // Send verification email
            $user->sendEmailVerificationNotification();

            Log::info('Verification email resent successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return [
                'success' => true,
                'message' => 'Verification email has been sent. Please check your inbox.',
            ];
        } catch (\Exception $e) {
            Log::error('Failed to resend verification email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Failed to send verification email. Please try again later.', 500);
        }
    }

    /**
     * Check if user's email is verified.
     *
     * @param User $user
     * @return array
     */
    public function checkEmailVerification(User $user): array
    {
        return [
            'email_verified' => $user->hasVerifiedEmail(),
            'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->toIso8601String() : null,
        ];
    }

    /**
     * Verify user's email address.
     *
     * @param User $user
     * @param string $hash
     * @return array
     * @throws \Exception
     */
    public function verifyEmail(User $user, string $hash): array
    {
        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            return [
                'success' => true,
                'message' => 'Email already verified.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email,
                        'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->toIso8601String() : null,
                    ],
                ],
            ];
        }

        // Verify the hash matches the user's email
        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            throw new \Exception('Invalid verification link.', 403);
        }

        // Mark email as verified
        if ($user->markEmailAsVerified()) {
            // Clear the cache so the next check-email-verification request returns updated status
            $cacheKey = 'email_verification_check_' . $user->id;
            Cache::forget($cacheKey);
            
            Log::info('Email verified successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return [
                'success' => true,
                'message' => 'Email verified successfully.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->toIso8601String() : null,
                    ],
                ],
            ];
        }

        throw new \Exception('Failed to verify email.', 500);
    }
}

