<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Subscription;

class RetractationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Subscription $subscription
    ){}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('contact@' . config('mail.from.name'), config('mail.from.name')),
            subject: 'Confirmation de votre rétractation Accès+ | ' . config('app.name'),
            tags: ['confirmation retractation'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        $retractationDate = \Carbon\Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $this->subscription->getRawOriginal('current_period_end_at'),
            'Europe/Paris'
        );

        $subscriptionDate = \Carbon\Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $this->subscription->getRawOriginal('created_at'),
            'Europe/Paris'
        );

        return new Content(
            text: 'emails.retractation-confirmation-text',
            markdown: 'emails.retractation-confirmation',
            with: [
                'retractationDate' => $retractationDate,
                'subscriptionDate' => $subscriptionDate,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
