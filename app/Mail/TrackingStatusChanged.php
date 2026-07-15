<?php

namespace App\Mail;

use App\Models\Tracking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrackingStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Tracking $tracking,
        public $event,
        public $fullShipmentData,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Changement de statut Okapi',
            tags: ['okapi'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtml(),
        );
    }

     private function buildHtml(): string
    {
        $label = htmlspecialchars($this->event['shortLabel'] ?? '', ENT_QUOTES, 'UTF-8');
        $date  = htmlspecialchars($this->event['date'] ?? '', ENT_QUOTES, 'UTF-8');
        $url   = htmlspecialchars($this->fullShipmentData['url'] ?? '#', ENT_QUOTES, 'UTF-8');

        $newStatus = $this->fullShipmentData['event'][0];

        $url = route('tracking', ['tracking_number' => $this->tracking->id_ship]);

        return <<<HTML
            <h2>Changement de statut d'un envoi Okapi</h2>
            <p><strong>Sending ID :</strong> {$this->tracking->sending->id}</p>
            <p><strong>Maileva sending ID :</strong> {$this->tracking->sending->getMailevaSendingIdAttribute()}</p>
            <p><strong>Numero de suivi :</strong> {$this->tracking->id_ship}</p>
            <p><strong>Code :</strong> {$newStatus['code']}</p>
            <p><strong>Libellé :</strong> {$newStatus['label']}</p>
            <p><strong>Date :</strong> {$newStatus['date']}</p>
            <p>
                <a href="{$url}">
                    Suivre sur le site
                </a>
            </p>
        HTML;
    }
}
