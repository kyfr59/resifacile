<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;
use App\Models\Sending;

class PNDRecieved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected Sending $sending,
        protected string $pdf,
    )
    {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Votre preuve de non-distribution est disponible - ".config('app.name'),
            tags: ['preuve de non distribution'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $number = $this->sending->tracking->id_ship;
        $order_number = $this->sending->order->number;
        return new Content(
            text: 'emails.pnd-text',
            markdown: 'emails.pnd',
            with: [
                'number' => $number,
                'order_number' => $order_number,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => $this->pdf
            )
            ->as('preuve-de-non-distribution.pdf')
            ->withMime('application/pdf'),
        ];

        return $attachments;
    }
}
