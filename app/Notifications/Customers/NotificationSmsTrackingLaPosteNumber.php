<?php

namespace App\Notifications\Customers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\AwsSns\SnsChannel;
use NotificationChannels\AwsSns\SnsMessage;

class NotificationSmsTrackingLaPosteNumber extends Notification
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
        return [SnsChannel::class];
    }

    /**
     * Get the SNS / SMS representation of the notification.
     */
    public function toSns(object $notifiable): SnsMessage
    {
        return SnsMessage::create([
            'body' => 'Suivi courrier : ' . $this->number . ' — ' . $this->notificationData['shortLabel'] . (($this->notificationData['longLabel']) ? ' — ' . $this->notificationData['longLabel'] : ''),
            'transactional' => true,
            'sender' => 'ENVCOURRIER',
        ]);
    }
}
