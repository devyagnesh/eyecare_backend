<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Spam Detection Service
 * 
 * Automatically detects and marks spam accounts based on various criteria.
 * 
 * @package App\Services
 */
class SpamDetectionService
{
    /**
     * Spam detection criteria thresholds
     */
    private const SPAM_CRITERIA = [
        'email_not_verified_days' => 7, // Mark as spam if email not verified after 7 days
        'no_store_created_days' => 14, // Mark as spam if no store created after 14 days
        'no_login_days' => 30, // Mark as spam if no login after 30 days
        'suspicious_name_patterns' => [
            'test', 'demo', 'fake', 'spam', 'bot', 'admin', 'user123', 'temp'
        ],
        'suspicious_email_domains' => [
            'tempmail', 'guerrillamail', 'mailinator', '10minutemail', 'throwaway'
        ],
        'max_accounts_per_ip' => 3, // Max accounts from same IP in 24 hours
    ];

    /**
     * Check if a user should be marked as spam.
     *
     * @param User $user
     * @return bool
     */
    public function isSpam(User $user): bool
    {
        $spamScore = 0;
        $reasons = [];

        // Check 1: Email not verified after threshold days
        if (!$user->email_verified_at) {
            $daysSinceSignup = $user->created_at->diffInDays(now());
            if ($daysSinceSignup >= self::SPAM_CRITERIA['email_not_verified_days']) {
                $spamScore += 2;
                $reasons[] = "Email not verified after {$daysSinceSignup} days";
            }
        }

        // Check 2: No store created after threshold days
        if (!$user->store) {
            $daysSinceSignup = $user->created_at->diffInDays(now());
            if ($daysSinceSignup >= self::SPAM_CRITERIA['no_store_created_days']) {
                $spamScore += 3;
                $reasons[] = "No store created after {$daysSinceSignup} days";
            }
        }

        // Check 3: Suspicious name patterns
        $nameLower = strtolower($user->name);
        foreach (self::SPAM_CRITERIA['suspicious_name_patterns'] as $pattern) {
            if (str_contains($nameLower, $pattern)) {
                $spamScore += 2;
                $reasons[] = "Suspicious name pattern: {$pattern}";
                break;
            }
        }

        // Check 4: Suspicious email domains
        $emailLower = strtolower($user->email);
        foreach (self::SPAM_CRITERIA['suspicious_email_domains'] as $domain) {
            if (str_contains($emailLower, $domain)) {
                $spamScore += 3;
                $reasons[] = "Suspicious email domain: {$domain}";
                break;
            }
        }

        // Check 5: Generic or very short names
        if (strlen(trim($user->name)) < 3) {
            $spamScore += 2;
            $reasons[] = "Name too short";
        }

        // Check 6: No login activity (if user has devices)
        if ($user->devices()->count() > 0) {
            $lastLogin = $user->devices()->max('last_active_at');
            if ($lastLogin) {
                $daysSinceLogin = \Carbon\Carbon::parse($lastLogin)->diffInDays(now());
                if ($daysSinceLogin >= self::SPAM_CRITERIA['no_login_days']) {
                    $spamScore += 1;
                    $reasons[] = "No login activity for {$daysSinceLogin} days";
                }
            }
        } else {
            // No devices registered at all
            $daysSinceSignup = $user->created_at->diffInDays(now());
            if ($daysSinceSignup >= 3) {
                $spamScore += 1;
                $reasons[] = "No device registered after {$daysSinceSignup} days";
            }
        }

        // Check 7: Multiple accounts from same IP (check user devices)
        $userIps = $user->devices()->whereNotNull('ip_address')
            ->where('created_at', '>=', now()->subDay())
            ->pluck('ip_address')
            ->unique();
        
        if ($userIps->count() > 0) {
            foreach ($userIps as $ip) {
                $accountsFromSameIp = User::whereHas('devices', function ($query) use ($ip) {
                    $query->where('ip_address', $ip)
                        ->where('created_at', '>=', now()->subDay());
                })->count();
                
                if ($accountsFromSameIp > self::SPAM_CRITERIA['max_accounts_per_ip']) {
                    $spamScore += 4;
                    $reasons[] = "Multiple accounts from same IP ({$accountsFromSameIp} accounts)";
                    break;
                }
            }
        }

        // Mark as spam if score >= 5
        if ($spamScore >= 5) {
            Log::info('User marked as spam', [
                'user_id' => $user->id,
                'email' => $user->email,
                'spam_score' => $spamScore,
                'reasons' => $reasons,
            ]);
            return true;
        }

        return false;
    }

    /**
     * Automatically check and mark user as spam if criteria met.
     *
     * @param User $user
     * @return bool True if marked as spam
     */
    public function checkAndMarkAsSpam(User $user): bool
    {
        // Don't override manual spam marking
        if ($user->is_spam) {
            return true;
        }

        if ($this->isSpam($user)) {
            $user->update(['is_spam' => true]);
            return true;
        }

        return false;
    }

    /**
     * Scan all users and mark spam accounts.
     *
     * @param bool $onlyUnverified Check only unverified users
     * @return array
     */
    public function scanAllUsers(bool $onlyUnverified = false): array
    {
        $query = User::where('is_spam', false);
        
        if ($onlyUnverified) {
            $query->whereNull('email_verified_at');
        }

        $users = $query->get();
        $markedAsSpam = 0;

        foreach ($users as $user) {
            if ($this->checkAndMarkAsSpam($user)) {
                $markedAsSpam++;
            }
        }

        return [
            'scanned' => $users->count(),
            'marked_as_spam' => $markedAsSpam,
        ];
    }
}

