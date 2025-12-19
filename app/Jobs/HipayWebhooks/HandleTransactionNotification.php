<?php

namespace App\Jobs\HipayWebhooks;

use App\Actions\MakeCreateTransaction;
use App\Actions\MakeNewInvoice;
use App\DataTransferObjects\TransactionData;
use App\Enums\InvoiceType;
use App\Enums\OrderStatus;
use App\Enums\SubscriptionStatus;
use App\Enums\TransactionStatus;
use App\Mail\InvoiceCreated;
use App\Models\Customer;
use App\Models\Order;
use App\Notifications\InvoicePaid;
use App\Registries\PaymentGatewayRegistry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Spatie\WebhookClient\Models\WebhookCall;
use Stripe\Exception\ApiErrorException;

class HandleTransactionNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const TRANSACTION = 7;

    private const RECURRING_TRANSACTION = 9;

    /**
     * @var WebhookCall
     */
    public WebhookCall $webhookCall;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        WebhookCall $webhookCall,
    )
    {
        $this->webhookCall = $webhookCall;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws ApiErrorException
     */
    public function handle(PaymentGatewayRegistry $paymentGatewayRegistry): void
    {
        $transactionData = $this->webhookCall->payload;

        $transactionStatus = TransactionStatus::code($transactionData['code']);

        if($transactionData['eci'] === self::TRANSACTION) {
            $transactionable = Order::find($transactionData['order_id']);
            $transactionable->status = OrderStatus::PAID;
            $transactionable->save();

            $type = InvoiceType::ORDER;
        } else if ($transactionData['eci'] === self::RECURRING_TRANSACTION) {
            $transactionable = Customer::find($transactionData['customer'])->subscription;
            if($transactionable->status === SubscriptionStatus::TRIAL) {
                $transactionable->status = SubscriptionStatus::RECURRING;
                $transactionable->save();
            }

            $type = InvoiceType::SUBSCRIPTION;
        }

        if($transactionStatus === TransactionStatus::CAPTURED) {
            $amount = $transactionData['captured_amount'];
        }

        if(
            $transactionStatus === TransactionStatus::REFUNDED ||
            $transactionStatus === TransactionStatus::PARTIALLY_REFUNDED
        ) {
            $amount = $transactionData['refunded_amount'];
        }

        $transaction = (new MakeCreateTransaction())->handle(
            transactionData: new TransactionData(
                amount: $amount,
                mid: $transactionData['mid'],
                status: $transactionStatus,
                transaction_id: $transactionData['attempt_id'],
                transactionable_id: $transactionable->id,
                transactionable_type: 'App\Models\\' . class_basename($transactionable),
            ),
        );

        (new MakeNewInvoice())->handle(
            transaction: $transaction,
            type: $type,
        );

        if($transactionData['eci'] === self::TRANSACTION) {
            Mail::to($transactionable->customer->email)
                ->send(new InvoiceCreated(transaction: $transaction));
        }
    }
}
