<?php

namespace App\Jobs\HipayWebhooks;

use App\Actions\MakeCreateTransaction;
use App\DataTransferObjects\TransactionData;
use App\Enums\InvoiceType;
use App\Enums\OrderStatus;
use App\Enums\SubscriptionStatus;
use App\Enums\TransactionStatus;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Models\WebhookCall;

class HandleOthers implements ShouldQueue
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

        if($transactionData['custom_data']['is_subscription_transaction']) {
            $transactionable = Subscription::find($transactionData['custom_data']['subscription_id']);
            $type = 'App\Models\Subscription';
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
                error_type: null,
            ),
        );
    }
}
