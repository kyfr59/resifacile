<?php

namespace App\WebhookClient\WebhookProfile;

use Illuminate\Http\Request;
use Spatie\WebhookClient\Models\WebhookCall;
use Spatie\WebhookClient\WebhookProfile\WebhookProfile;

class MailevaWebhookProfile implements WebhookProfile
{

    public function shouldProcess(Request $request): bool
    {
        //return ! WebhookCall::where('name', 'mail')->where('payload->MessageID', $request->get('MessageID'))->exists();
        return true;
    }
}
