<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\AccountDeletionNotification;
use Illuminate\Support\Facades\Log;

/**
 * Account Deletion Service
 * 
 * Handles business logic for account deletion requests and scheduled deletions.
 * 
 * @package App\Services
 */
class AccountDeletionService
{
    /**
     * Request account deletion for a user.
     * 
     * Schedules the account for deletion after 30 days and sends notification email.
     *
     * @param User $user
     * @return array
     * @throws \Exception
     */
    public function requestAccountDeletion(User $user): array
    {
        // Check if deletion is already requested
        if ($user->deletion_requested_at) {
            throw new \Exception('Account deletion has already been requested.', 400);
        }

        // Check if user is already deleted
        if ($user->trashed()) {
            throw new \Exception('Account has already been deleted.', 400);
        }

        try {
            $scheduledDeletionAt = now()->addDays(30);

            // Update user with deletion request and block the account
            $user->update([
                'deletion_requested_at' => now(),
                'scheduled_deletion_at' => $scheduledDeletionAt,
                'is_blocked' => true, // Block user from logging in during deletion period
            ]);

            // Send notification email
            try {
                $user->notify(new AccountDeletionNotification($scheduledDeletionAt));
                
                Log::info('Account deletion requested and email sent', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'scheduled_deletion_at' => $scheduledDeletionAt->toIso8601String(),
                ]);
            } catch (\Exception $e) {
                // Log email failure but don't fail the deletion request
                Log::error('Failed to send account deletion email', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                ]);
            }

            return [
                'success' => true,
                'message' => 'Account deletion has been requested. Your account will be deleted after 30 days.',
                'scheduled_deletion_at' => $scheduledDeletionAt->toIso8601String(),
            ];
        } catch (\Exception $e) {
            Log::error('Failed to request account deletion', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Cancel account deletion request.
     * 
     * Cancels a pending account deletion request.
     *
     * @param User $user
     * @return array
     * @throws \Exception
     */
    public function cancelAccountDeletion(User $user): array
    {
        // Check if deletion is not requested
        if (!$user->deletion_requested_at) {
            throw new \Exception('No account deletion request found.', 400);
        }

        try {
            // Unblock user and cancel deletion request
            $user->update([
                'deletion_requested_at' => null,
                'scheduled_deletion_at' => null,
                'is_blocked' => false, // Unblock user when deletion is cancelled
            ]);

            Log::info('Account deletion request cancelled', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return [
                'success' => true,
                'message' => 'Account deletion request has been cancelled.',
            ];
        } catch (\Exception $e) {
            Log::error('Failed to cancel account deletion', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Delete accounts that are scheduled for deletion.
     * 
     * Permanently deletes all accounts where scheduled_deletion_at is in the past.
     *
     * @return array
     */
    public function deleteScheduledAccounts(): array
    {
        $now = now();
        
        $usersToDelete = User::whereNotNull('scheduled_deletion_at')
            ->where('scheduled_deletion_at', '<=', $now)
            ->get();

        $deletedCount = 0;
        $errors = [];

        foreach ($usersToDelete as $user) {
            try {
                // Permanently delete the user (force delete since we're using soft deletes)
                $user->forceDelete();
                
                $deletedCount++;
                
                Log::info('Account permanently deleted', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'scheduled_deletion_at' => $user->scheduled_deletion_at?->toIso8601String(),
                ]);
            } catch (\Exception $e) {
                $errors[] = "User ID {$user->id}: " . $e->getMessage();
                
                Log::error('Failed to delete scheduled account', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return [
            'success' => true,
            'deleted_count' => $deletedCount,
            'errors' => $errors,
            'message' => "Processed {$deletedCount} account(s) for deletion.",
        ];
    }
}

