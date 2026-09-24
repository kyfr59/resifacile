<?php

namespace App\Mail;

use App\Models\Customer;
use App\Models\Unsubscribe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class RetractationDemande extends Mailable
{
    use Queueable, SerializesModels;

    public string $url;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private string $email,
    ) {
        $user = Customer::where('email', $this->email)->firstOrFail();

        $unsubscribe = new Unsubscribe();
        $unsubscribe->token = Str::random(32);
        $unsubscribe->customer()->associate($user);
        $unsubscribe->save();

        $this->url = route('frontend.retractation', ['token' => $unsubscribe->token]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('contact@' . config('mail.from.name'), config('mail.from.name')),
            subject: 'Confirmez votre demande de rétractation Accès+ | ' . config('app.name'),
            tags: ['demande retractation'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            text: 'emails.retractation-demande-text',
            markdown: 'emails.retractation-demande',
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
