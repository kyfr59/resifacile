<?php

namespace App\Notifications\Customers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationEmailTrackingNumber extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre numéro de suivi : ' . $this->notificationData['Request']['ErlNumbers'])
            ->line('Veuillez trouver ci-contre votre numéro de suivi : ' . $this->notificationData['Request']['ErlNumbers'] . '.')
            ->action('Accéder au suivi de votre courrier', 'https://www.laposte.fr/outils/suivre-vos-envois?code=' . $this->notificationData['Request']['ErlNumbers'])
            ->line('Attention : un délai est à prévoir pour la mise à jour du suivi par La Poste.')
            ->line('Nous restons à votre entière disposition pour toutes questions.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->notificationData;
    }
}
