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

class ProcessOtherMailevaWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public WebhookCall $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $eventType = $webhookCall->payload['event_type'];
        throw new MailevaException(
            message  : "Un envoi est passé en statut \"".$eventType."\" chez Maileva",
            context  : self::class . '::handle',
            extraData: ['payload' => $webhookCall->payload],
            httpCode: 201,
        );

        return response()->json(['ok' => true], 201);
    }
}
