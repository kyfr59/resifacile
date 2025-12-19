<?php

namespace App\Mail;

use App\Models\Document;
use App\Models\Page;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected Transaction $transaction,
        //protected Document|null $service_agreement = null,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre facture | ' . config('app.name'),
            tags: ['facture'],
            metadata: [
                'transaction_id' => $this->transaction->id,
            ]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->transaction->invoice->type->mailInvoicePath(),
            //text: $this->transaction->invoice->type->mailInvoicePath() . '-text',
            with: [
                'data' => $this->transaction,
                'cgv' => Page::find(1),
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
        $attachments = [
            Attachment::fromStorage($this->transaction->invoice->path)
                ->as( $this->transaction->invoice->number . '.pdf')
                ->withMime('application/pdf'),
        ];

        //if($this->service_agreement) {
        //    $attachments[] = Attachment::fromStorage($this->service_agreement->path)
        //        ->as($this->service_agreement->readable_file_name)
        //        ->withMime('application/pdf');
        //}

        return $attachments;
    }
}
