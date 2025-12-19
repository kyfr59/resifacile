<?php

namespace App\Jobs\HipayWebhooks;

use App\Actions\MakeCreateTransaction;
use App\DataTransferObjects\TransactionData;
use App\Enums\SubscriptionStatus;
use App\Enums\TransactionStatus;
use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Spatie\WebhookClient\Models\WebhookCall;

class HandleRefused implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public WebhookCall $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle(): void
    {
        $transactionData = $this->webhookCall->payload;

        $transactionStatus = TransactionStatus::code($transactionData['status']);

        $errorType = array_key_exists('reason', $transactionData) ? trim($transactionData['reason']['code']
            . ' ' . $transactionData['reason']['message'] ?? $transactionData['message']) : $transactionData['message'];

        if($transactionData['custom_data']['is_subscription_transaction']) {
            $transactionable = Subscription::find($transactionData['custom_data']['subscription_id']);
            $type = 'App\Models\Subscription';

            if(Str::contains(Str::lower($errorType), 'insufficient funds')) {
                if(
                    $transactionable->status === SubscriptionStatus::TRIAL ||
                    $transactionable->status === SubscriptionStatus::RECURRING
                ) {
                    $transactionable->status = SubscriptionStatus::LATE_PAYMENT;
                } else if(
                    $transactionable->created_at->diff(now())->days > 30 &&
                    now()->day === 10
                ) {
                    $transactionable->status = SubscriptionStatus::CANCELED;
                    $transactionable->cancellation_request_at = now();
                }
            } else {
                $transactionable->status = SubscriptionStatus::CANCELED;
                $transactionable->cancellation_request_at = now();
            }

            $transactionable->save();
        } else {
            $transactionable = Order::find($transactionData['custom_data']['order_id']);
            $type = 'App\Models\Order';
        }

        (new MakeCreateTransaction())->handle(
            transactionData: new TransactionData(
                amount: $transactionData['authorized_amount'] * 100,
                mid: $transactionData['mid'],
                status: $transactionStatus,
                transaction_id: $transactionData['transaction_reference'],
                transactionable_id: $transactionable->id,
                transactionable_type: $type,
                error_type: $errorType,
            ),
        );
    }
}

