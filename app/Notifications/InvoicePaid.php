<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Transaction $transaction,
    ){
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['slack'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param object $notifiable
     * @return SlackMessage
     */
    public function toSlack(object $notifiable): SlackMessage
    {
        return (new SlackMessage())
            ->content('La facture n°' . $this->transaction->invoice->number . 'a été régler !')
            ->attachment(function (SlackAttachment $attachment) {
                $attachment
                    ->title('Facture n°' . $this->transaction->invoice->number)
                    ->fields([
                        'montant' => $this->transaction->amount
                    ]);
            });
    }
}
