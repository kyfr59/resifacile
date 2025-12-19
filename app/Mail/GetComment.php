<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GetComment extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(){}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $str = '';

        return new Envelope(
            from: new Address('contact@' . config('mail.from.name'), config('mail.from.name')),
            subject: '⭑⭑⭑⭑⭑ Qu’avez-vous pensé de ' . config('mail.from.name') . ' ?',
            tags: ['trustpilot'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            text: 'emails.get-comment-text',
            markdown: 'emails.get-comment',
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
