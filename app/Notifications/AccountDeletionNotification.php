<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Account Deletion Notification
 * 
 * Sends email to user when account deletion is requested.
 * 
 * @package App\Notifications
 */
class AccountDeletionNotification extends Notification
{
    use Queueable;

    /**
     * The scheduled deletion date.
     *
     * @var \Carbon\Carbon
     */
    public $scheduledDeletionAt;

    /**
     * Create a new notification instance.
     *
     * @param \Carbon\Carbon $scheduledDeletionAt
     */
    public function __construct($scheduledDeletionAt)
    {
        $this->scheduledDeletionAt = $scheduledDeletionAt;
    }

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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Account Deletion Requested - Eyecare Management System')
            ->view('emails.account-deletion', [
                'name' => $notifiable->name,
                'scheduledDeletionAt' => $this->scheduledDeletionAt,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'scheduled_deletion_at' => $this->scheduledDeletionAt->toIso8601String(),
        ];
    }
}
