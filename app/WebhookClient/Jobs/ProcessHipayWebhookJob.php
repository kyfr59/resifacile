<?php

namespace App\WebhookClient\Jobs;

use App\Enums\TransactionStatus;
use App\WebhookClient\Exceptions\WebhookFailed;
use Illuminate\Support\Str;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class ProcessHipayWebhookJob extends ProcessWebhookJob
{
    /**
     * @throws WebhookFailed
     */
    public function handle(): void
    {
        if (! isset($this->webhookCall->payload['status']) || $this->webhookCall->payload['status'] === '') {
            throw WebhookFailed::missingType($this->webhookCall);
        }

        event("hipay::{$this->webhookCall->payload['status']}", $this->webhookCall);

        $jobClass = $this->determineJobClass($this->webhookCall->payload['status']);

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
        $jobConfigKey = Str::lower(TransactionStatus::code($eventType)->value);

        $defaultJob = config('webhook-client.configs.0.default_job', '');

        return config("webhook-client.configs.0.jobs.{$jobConfigKey}", $defaultJob);
    }
}
