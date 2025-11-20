<?php

namespace App\Console\Commands;

use App\Services\AccountDeletionService;
use Illuminate\Console\Command;

/**
 * Delete Scheduled Accounts Command
 * 
 * Permanently deletes user accounts that are scheduled for deletion.
 * This command should be run daily via cron/scheduler.
 * 
 * @package App\Console\Commands
 */
class DeleteScheduledAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:delete-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete user accounts that are scheduled for deletion (after 30 days)';

    /**
     * Execute the console command.
     */
    public function handle(AccountDeletionService $accountDeletionService)
    {
        $this->info('Starting scheduled account deletion process...');

        try {
            $result = $accountDeletionService->deleteScheduledAccounts();

            if ($result['deleted_count'] > 0) {
                $this->info("✓ Successfully deleted {$result['deleted_count']} account(s).");
            } else {
                $this->info('No accounts scheduled for deletion at this time.');
            }

            if (!empty($result['errors'])) {
                $this->warn('Some accounts could not be deleted:');
                foreach ($result['errors'] as $error) {
                    $this->error("  - {$error}");
                }
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to delete scheduled accounts: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
