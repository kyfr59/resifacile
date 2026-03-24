<?php

namespace App\Services;

use App\Actions\GetDocumentFromSending;
use App\Actions\GetSenderFromSending;
use App\Models\Sending;
use Illuminate\Support\Facades\Log;

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
            if (isset($mailevaSendingId)) {
                try {
                    $this->client->deleteSending($mailevaSendingId);
                } catch (\RuntimeException $deleteException) {
                    Log::channel('maileva')->error("Échec du rollback Maileva", [
                        'maileva_sending_id' => $mailevaSendingId,
                        'error'              => $deleteException->getMessage(),
                    ]);
                }
            }
            throw $e;
        }

        Log::channel('maileva')->info("    Envoi {$sending->id} transmis avec succès");
    }
}