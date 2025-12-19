<?php

namespace App\Notifications\Customers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationEmailTrackingLaPosteNumber extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected string $number,
        protected array $notificationData
    ){
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
            ->subject('Suivi de votre courrier : ' . $this->number . ' | ' . config('app.name'))
            ->line($this->notificationData['shortLabel'])
            ->lineIf($this->notificationData['longLabel'] != "", $this->notificationData['longLabel'])
            ->action('Accéder au suivi de votre courrier', 'https://www.laposte.fr/outils/suivre-vos-envois?code=' . $this->number)
            ->line('Nous restons à votre entière disposition pour toutes questions.');
    }
}
