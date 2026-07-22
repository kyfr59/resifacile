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
use App\Mail\SendingExecuted;
use App\Enums\SendingStatus;

class HandleRejected implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public WebhookCall $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $payload = $webhookCall->toArray()['payload'];

        $resource_id = $payload['resource_id'] ?? null;
        if (empty($resource_id)) {
            throw new MailevaException(
                message  : "Impossible de récupérer l'ID de la ressource depuis un appel Maileva",
                context  : self::class . '::handle',
                extraData: ['payload' => $payload],
            );
        }

        $sending = Sending::fromMailevaId($resource_id);
        if (! $sending) {
            throw new MailevaException(
                message  : "Impossible de récupérer l'envoi depuis un appel Maileva",
                context  : self::class . '::handle',
                extraData: ['resource_id' => $resource_id, 'payload' => $payload],
            );
        }

        $sending->update([
            'status' => SendingStatus::REJECTED,
            'processed_at' => now(),
        ]);

        throw new MailevaException(
            message  : "Un envoi est passé en statut \"REJECTED\" chez Maileva",
            context  : self::class . '::handle',
            extraData: ['payload' => $webhookCall->payload],
        );

        return response()->json(['ok' => true], 201);
    }
}
