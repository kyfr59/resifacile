<?php

namespace App\Services;

use App\Actions\GetDocumentFromSending;
use App\Actions\GetSenderFromSending;
use App\Models\Sending;
use Illuminate\Support\Facades\Log;
use App\Exceptions\MailevaException;

class SendingOrchestrator
{
    public function __construct(
        private MailevaApiClient $client
    ) {}

    public function process(Sending $sending): void
    {
        Log::channel('maileva')->info("    Transmission de l'envoi ID {$sending->id}");

        $maileva = $sending->maileva ?? [];

        try {
            $mailevaSendingId = $this->client->createSending($sending);
            $maileva['sending_id'] = $mailevaSendingId;
            $sending->update(['maileva' => $maileva]);
            $sending->refresh();

            $mailevaDocumentId = $this->client->addDocument($mailevaSendingId, $sending);
            $maileva['document_id'] = $mailevaDocumentId;
            $sending->update(['maileva' => $maileva]);
            $sending->refresh();

            $mailevaRecipientId = $this->client->addRecipient($mailevaSendingId, $sending);
            $maileva['recipient_id'] = $mailevaRecipientId;
            $sending->update(['maileva' => $maileva]);

            $this->client->submitSending($mailevaSendingId);

        } catch (\RuntimeException $e) {

            $this->rollback($mailevaSendingId, $sending, $e);

            throw new MailevaException(
                message    : "Échec de la transmission de l'envoi #{$sending->id} : {$e->getMessage()}",
                context    : self::class . '::process',
                extraData  : [
                    'sending_id'          => $sending->id,
                    'maileva_sending_id'   => $mailevaSendingId,
                    'maileva'             => $maileva,
                ],
                previous   : $e,
            );
        }

        Log::channel('maileva')->info("    Envoi {$sending->id} transmis avec succès");
    }

    private function rollback(?string $mailevaSendingId, Sending $sending, \RuntimeException $origin): void
    {
        if (! $mailevaSendingId) {
            return;
        }

        try {
            $this->client->deleteSending($mailevaSendingId);
        } catch (\RuntimeException $e) {
            // On logue le rollback raté mais on ne re-throw pas :
            // c'est l'exception d'origine qui doit remonter.
            throw new MailevaException(
                message    : "Échec du rollback Maileva pour l'envoi #{$sending->id}",
                context    : self::class . '::rollback',
                extraData  : [
                    'sending_id'        => $sending->id,
                    'maileva_sending_id' => $mailevaSendingId,
                    'rollback_error'    => $e->getMessage(),
                    'origin_error'      => $origin->getMessage(),
                ],
                previous   : $e,
            );
        }
    }
}