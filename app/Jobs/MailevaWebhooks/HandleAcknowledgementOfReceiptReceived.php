<?php

namespace App\Jobs\MailevaWebhooks;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Models\WebhookCall;
use Illuminate\Support\Facades\Log;
use App\Exceptions\MailevaException;
use App\Models\Sending;
use Illuminate\Support\Facades\Mail;
use App\Mail\AcknowledgementOfReceiptReceived;
use App\Enums\SendingStatus;
use App\Services\MailevaService;
use App\Mail\ProofOfDepositRecieved;

class HandleAcknowledgementOfReceiptReceived implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public WebhookCall $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle(MailevaService $mailevaService)
    {
        $payload = $this->webhookCall->toArray()['payload'];

        $resource_id = $payload['resource_id'] ?? null;
        if (empty($resource_id)) {
            throw new MailevaException(
                message  : "Impossible de récupérer l'ID de la ressource depuis un appel Maileva",
                context  : self::class . '::handle',
                extraData: ['payload' => $payload],
            );
        }

        $sending = Sending::fromMailevaRecipientId($resource_id);
        if (! $sending) {
            throw new MailevaException(
                message  : "Impossible de récupérer l'envoi depuis un appel Maileva",
                context  : self::class . '::handle',
                extraData: ['resource_id' => $resource_id, 'payload' => $payload],
            );
        }

        if ($sending->status === SendingStatus::DELIVERED) {
            return response()->json(['ok' => true], 201);
        }

        try {
            $pdf = $mailevaService->storeAcknowledgementOfReceipt($sending);
        } catch (\Exception $e) {
            throw new MailevaException(
                message  : "Impossible de stocker la preuve de réception sur le serveur",
                context  : self::class . '::' . __FUNCTION__,
                extraData: [
                    'sending_id'   => $sending->id,
                    'customer_id'  => $sending->customer_id,
                    'customer_mail'=> $sending->customer->email,
                ],
                description: $e->getMessage(),
                previous : $e,
            );
        }

        try {
            Mail::to($sending->customer->email)->send(new AcknowledgementOfReceiptReceived($sending, $pdf));

            $sending->update([
                'status' => SendingStatus::DELIVERED,
                'processed_at' => now(),
            ]);

        } catch (\Exception $e) {
            dd($e);
            throw new MailevaException(
                message  : "Impossible d'envoyer le mail de notification du statut DELIVERED, le statut n'a pas été mis à jour",
                context  : self::class . '::' . __FUNCTION__,
                extraData: [
                    'sending_id'   => $sending->id,
                    'customer_id'  => $sending->customer_id,
                    'customer_mail'=> $sending->customer->email,
                ],
                previous : $e,
            );
        }
    }
}
