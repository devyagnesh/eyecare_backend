<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification
{

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your Email - Eyecare Management System')
            ->view('emails.verify-email', [
                'name' => $notifiable->name,
                'verificationUrl' => $verificationUrl,
            ]);
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable): string
    {
        $relativeUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ],
            false // Don't append existing query parameters
        );
        
        // Ensure absolute URL with proper base URL
        if (strpos($relativeUrl, 'http') !== 0) {
            // Extract query string if present
            $queryString = '';
            if (strpos($relativeUrl, '?') !== false) {
                list($path, $queryString) = explode('?', $relativeUrl, 2);
                $relativeUrl = $path;
                $queryString = '?' . $queryString;
            }
            
            // Build absolute URL
            $baseUrl = rtrim(config('app.url'), '/');
            return $baseUrl . $relativeUrl . $queryString;
        }
        
        return $relativeUrl;
    }
}
