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

class ProofOfDepositRecieved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected array $payload,
    )
    {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: Str::of($this->payload['payload']['Subject'])->lower()->ucfirst() . ' | ' . config('app.name'),
            tags: ['preuve de depot'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            text: 'emails.proof-of-deposit-text',
            markdown: 'emails.proof-of-deposit',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        foreach ($this->payload['payload']['Attachments'] as $attachment) {
            $attachments[] = Attachment::fromData(fn () => base64_decode($attachment['Content']))
                ->as($attachment['Name'])
                ->withMime('application/zip');
        }

        return $attachments;
    }
}
