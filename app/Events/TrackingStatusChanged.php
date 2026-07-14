<?php

namespace App\Events;

use App\Models\Tracking;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\TrackingStatusChanged as TrackingStatusChangedEmail;
use App\Exceptions\OkapiException;

class TrackingStatusChanged
{
    use Dispatchable;

    public function __construct(
        public readonly Tracking $tracking,
        public readonly array $event,
        public readonly array $fullShipmentData,
    ) {
        $this->sendNotificationMail();
    }

    protected function sendNotificationMail(): void
    {
        try {
            Mail::to('kyfr59@gmail.com')->send(new TrackingStatusChangedEmail($this->tracking, $this->event, $this->fullShipmentData));

        } catch (\Exception $e) {
            throw new OkapiException(
                message  : "Impossible d'envoyer le mail de changement de statut Okapi aà l'administrateur",
            );
        }
    }
}