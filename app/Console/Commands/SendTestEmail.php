<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendTestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email} {--verification}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify email configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $isVerification = $this->option('verification');

        $this->info("Sending test email to: {$email}");

        try {
            if ($isVerification) {
                // Send verification email template
                $verificationUrl = url('/email/verify/test/' . md5($email));
                
                Mail::send('emails.verify-email', [
                    'name' => 'Test User',
                    'verificationUrl' => $verificationUrl,
                ], function ($message) use ($email) {
                    $message->to($email)
                            ->subject('Test Verification Email - Eyecare Management System');
                });

                $this->info('✓ Test verification email sent successfully!');
            } else {
                // Send simple test email
                Mail::raw('This is a test email from Eyecare Management System. If you receive this, your email configuration is working correctly!', function ($message) use ($email) {
                    $message->to($email)
                            ->subject('Test Email - Eyecare Management System');
                });

                $this->info('✓ Test email sent successfully!');
            }

            $mailDriver = config('mail.default');
            $this->info("Current mail configuration: {$mailDriver}");
            
            if ($mailDriver === 'log') {
                $this->warn("⚠️  Emails are being logged to storage/logs/laravel.log, not actually sent!");
                $this->info("To send real emails, configure SMTP in your .env file:");
                $this->line("MAIL_MAILER=smtp");
                $this->line("MAIL_HOST=smtp.gmail.com");
                $this->line("MAIL_PORT=587");
                $this->line("MAIL_USERNAME=your_email@gmail.com");
                $this->line("MAIL_PASSWORD=your_app_password");
                $this->line("MAIL_ENCRYPTION=tls");
                $this->line("MAIL_FROM_ADDRESS=your_email@gmail.com");
                $this->line("MAIL_FROM_NAME=\"Eyecare Management\"");
            } else {
                $this->info("✓ Please check {$email} inbox (and spam folder).");
            }
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('✗ Failed to send email: ' . $e->getMessage());
            $this->error('Error details: ' . $e->getFile() . ':' . $e->getLine());
            
            Log::error('Test email failed', [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }
}