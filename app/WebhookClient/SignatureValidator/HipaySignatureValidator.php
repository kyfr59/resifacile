<?php

namespace App\WebhookClient\SignatureValidator;

use Exception;
use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;
use Stripe\Webhook;

class HipaySignatureValidator implements SignatureValidator
{

    public function isValid(Request $request, WebhookConfig $config): bool
    {
        if (! config('webhook-client.configs.1.verify_signature')) {
            return true;
        }

        $signature = $request->header($config->signatureHeaderName);
        $secret = config('hipay.' . $request['mid'] . '.passphrase');

        try {
            Webhook::constructEvent($request->getContent(), $signature, $secret);
        } catch (Exception) {
            return false;
        }

        return true;
    }
}
