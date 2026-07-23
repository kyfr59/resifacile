<?php

namespace App\Mail\Okapi;

use App\Models\Sending;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Tracking;

class StatusChangedToET1 extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Tracking $tracking,
        public String $code,
        public Array $event,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre courrier est en cours d’acheminement | Resifacile',
            tags: ['okapi'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $number = $this->tracking->id_ship;
        $order_number = $this->tracking->sending->order->number;
        return new Content(
            markdown: 'emails.okapi.et1',
            text: 'emails.okapi.et1-text',
            with: [
                'number' => $number,
                'order_number' => $order_number,
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
