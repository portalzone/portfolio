<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewsletterSubscribed extends Notification
{
    use Queueable;

    protected $subscriber;

    public function __construct($subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = url('/api/newsletter/verify/' . $this->subscriber->verification_token);

        return (new MailMessage)
            ->subject('Confirm Your Newsletter Subscription - BasePan')
            ->greeting('Hello' . ($this->subscriber->name ? ' ' . $this->subscriber->name : '') . '!')
            ->line('Thank you for subscribing to the BasePan newsletter!')
            ->line('Please click the button below to confirm your email address.')
            ->action('Verify Email', $verificationUrl)
            ->line('If you did not subscribe to this newsletter, please ignore this email.')
            ->salutation('Best regards, The BasePan Team');
    }
}