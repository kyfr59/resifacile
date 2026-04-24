<?php

namespace App\Mail;

use App\Exceptions\MailevaException;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailevaExceptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly MailevaException $exception) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[' . config('app.name') . '] MailevaException – ' . $this->exception->getMessage(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.maileva-exception',
            with: [
                'errorMessage' => $this->exception->getMessage(),
                'description'  => $this->exception->getDescription(),
                'context'   => $this->exception->getContext(),
                'extraData' => $this->exception->getExtraData(),
                'code'      => $this->exception->getCode(),
                'file'      => $this->exception->getFile(),
                'line'      => $this->exception->getLine(),
                'trace'     => $this->exception->getTraceAsString(),
                'env'       => config('app.env'),
                'appName'   => config('app.name'),
                'appUrl'    => config('app.url'),
            ],
        );
    }
}