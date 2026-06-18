<?php

namespace App\WebhookClient\Jobs;

use App\Jobs\MailRecieved;
use App\WebhookClient\Exceptions\WebhookFailed;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use App\Enums\MailevaStatus;
use Illuminate\Support\Str;

class ProcessMailevaWebhookJob extends ProcessWebhookJob
{
    /**
     * @throws WebhookFailed
     */
    public function handle(): void
    {
        if (! isset($this->webhookCall->payload['event_type']) || $this->webhookCall->payload['event_type'] === '') {
            throw WebhookFailed::missingType($this->webhookCall);
        }

        event("maileva::{$this->webhookCall->payload['event_type']}", $this->webhookCall);

        $jobClass = $this->determineJobClass($this->webhookCall->payload['event_type']);

        if ($jobClass === '') {
            return;
        }

        if (! class_exists($jobClass)) {
            throw WebhookFailed::jobClassDoesNotExist($jobClass, $this->webhookCall);
        }

        dispatch(new $jobClass($this->webhookCall));
    }

    protected function determineJobClass(string $eventType): string
    {
        $defaultJob = config('webhook-client.configs.2.default_job', '');

        try {
            $enum = MailevaStatus::from($eventType);
            $status = $enum->short();
        } catch (\ValueError $e) {
            return $defaultJob;
        }

        return config("webhook-client.configs.2.jobs.{$status}", $defaultJob);
    }
}
