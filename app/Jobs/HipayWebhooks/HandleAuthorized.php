<?php

namespace App\Jobs\HipayWebhooks;

use App\Actions\MakeCreateTransaction;
use App\Actions\MakeNewPaymentMethod;
use App\Actions\Subscription\CreateSubscriptionAction;
use App\DataTransferObjects\SubscriptionData;
use App\DataTransferObjects\TransactionData;
use App\Enums\OrderStatus;
use App\Enums\SubscriptionStatus;
use App\Enums\TransactionStatus;
use App\Helpers\Accounting;
use App\Models\Customer;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Registries\PaymentGatewayRegistry;
use App\Settings\SubscriptionSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Models\WebhookCall;

class HandleAuthorized implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const TRANSACTION = 7;

    private const RECURRING_TRANSACTION = 9;

    public WebhookCall $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle(
        PaymentGatewayRegistry $paymentGatewayRegistry,
        SubscriptionSettings $subscriptionSettings
    ): void
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

        $transaction = (new MakeCreateTransaction())->handle(
            transactionData: new TransactionData(
                amount: $transactionData['authorized_amount'] * 100,
                mid: $transactionData['mid'],
                status: $transactionStatus,
                transaction_id: $transactionData['transaction_reference'],
                transactionable_id: $transactionable->id,
                transactionable_type: $type,
            ),
        );

        /*if($transactionData['custom_data']['has_subscription']) {
            $paymentGateway = $paymentGatewayRegistry->get(config('hipay.default'));

            (new MakeNewPaymentMethod($transactionable->customer))(
                $transactionData['payment_method']['card_id'],
                [
                    'payment_product' => $transactionData['payment_product'],
                    'token' => $transactionData['payment_method']['token'],
                    'brand' => $transactionData['payment_method']['brand'],
                    'pan' => $transactionData['payment_method']['pan'],
                    'card_holder' => $transactionData['payment_method']['card_holder'],
                    'card_expiry_month' => $transactionData['payment_method']['card_expiry_month'],
                    'card_expiry_year' => $transactionData['payment_method']['card_expiry_year'],
                    'issuer' => $transactionData['payment_method']['issuer'],
                    '3ds_eci' => $transactionData['three_d_secure']['eci'],
                    '3ds_authentication_status' => $transactionData['three_d_secure']['authentication_status'],
                    '3ds_authentication_message' => $transactionData['three_d_secure']['authentication_message'],
                    '3ds_authentication_token' => $transactionData['three_d_secure']['authentication_token'],
                    '3ds_xid' => $transactionData['three_d_secure']['xid'],
                ]
            );

            $paymentGateway->capture([
                'amount' => Accounting::addTax($transactionable->amount) / 100,
                'operation' => 'capture',
                'transaction_id' => $transaction->transaction_id,
                'operation_id' => $transactionable->id,
            ]);

            (new CreateSubscriptionAction)(new SubscriptionData(
                designation: $subscriptionSettings->label,
                price: $subscriptionSettings->recurring_amount,
                status: SubscriptionStatus::TRIAL,
                meta_data: [
                    'order_id' => $transactionData['custom_data']['order_id'],
                ],
                customer_id: $transactionData['custom_data']['customer_id'],
                current_period_end_at: now()->addDays(16),
                discount_rate: $subscriptionSettings->discount,
            ));
        }*/
    }
}
