<?php

namespace App\WebhookClient\Jobs;

use App\Jobs\MailRecieved;
use App\WebhookClient\Exceptions\WebhookFailed;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class ProcessMailWebhookJob extends ProcessWebhookJob
{
    /**
     * @throws WebhookFailed
     */
    public function handle(): void
    {
        $s = dispatch(new MailRecieved($this->webhookCall));
    }
}
