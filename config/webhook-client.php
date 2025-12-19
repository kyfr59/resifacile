<?php

return [
    'configs' => [
        [
            'name' => 'hipay',
            'signature_header_name' => 'x-allopass-signature',
            'signature_validator' => \App\WebhookClient\SignatureValidator\HipaySignatureValidator::class,
            'webhook_profile' => \App\WebhookClient\WebhookProfile\HipayWebhookProfile::class,
            'webhook_response' => \Spatie\WebhookClient\WebhookResponse\DefaultRespondsTo::class,
            'webhook_model' => \Spatie\WebhookClient\Models\WebhookCall::class,
            'store_headers' => [],
            'process_webhook_job' => \App\WebhookClient\Jobs\ProcessHipayWebhookJob::class,
            'verify_signature' => env('WEBHOOK_HIPAY_SIGNATURE_VERIFY', true),
            'default_job' => \App\Jobs\HipayWebhooks\HandleOthers::class,
            'jobs' => [
                'authorized' => \App\Jobs\HipayWebhooks\HandleAuthorized::class,
                'captured' => \App\Jobs\HipayWebhooks\HandleCaptured::class,
                'partially_captured' => \App\Jobs\HipayWebhooks\HandlePartiallyCaptured::class,
                'refund_requested' => \App\Jobs\HipayWebhooks\HandleRefundRequested::class,
                'refunded' => \App\Jobs\HipayWebhooks\HandleRefunded::class,
                'charged_back' => \App\Jobs\HipayWebhooks\ChargedBack::class,
                'dispute_lost' => \App\Jobs\HipayWebhooks\DisputeLost::class,
                'refused' => \App\Jobs\HipayWebhooks\HandleRefused::class,
                'blocked' => \App\Jobs\HipayWebhooks\HandleBlocked::class,
            ],
        ],
        [
            'name' => 'mail',
            'signature_validator' => \App\WebhookClient\SignatureValidator\MailSignatureValidator::class,
            'webhook_profile' => \App\WebhookClient\WebhookProfile\MailWebhookProfile::class,
            'webhook_response' => \Spatie\WebhookClient\WebhookResponse\DefaultRespondsTo::class,
            'webhook_model' => \Spatie\WebhookClient\Models\WebhookCall::class,
            'store_headers' => [],
            'process_webhook_job' => \App\WebhookClient\Jobs\ProcessMailWebhookJob::class,
            'default_job' => \App\Jobs\MailRecieved::class,
        ]
    ],
    'delete_after_days' => 30,
];
