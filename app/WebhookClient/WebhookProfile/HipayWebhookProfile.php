<?php

namespace App\WebhookClient\WebhookProfile;

use Illuminate\Http\Request;
use Spatie\WebhookClient\Models\WebhookCall;
use Spatie\WebhookClient\WebhookProfile\WebhookProfile;

class HipayWebhookProfile implements WebhookProfile
{
    public function shouldProcess(Request $request): bool
    {
        return true; //! WebhookCall::where('name', 'hipay')->where('payload->attempt_id', $request->get('attempt_id'))->exists();
    }
}
