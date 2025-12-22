<?php

namespace App\Jobs\HipayWebhooks;

use App\Actions\MakeCreateTransaction;
use Illuminate\Support\Str;
use App\Actions\MakeNewInvoice;
use App\Actions\MakeNewPaymentMethod;
use App\Actions\Subscription\CreateServiceAgreementAction;
use App\Actions\Subscription\CreateSubscriptionAction;
use App\DataTransferObjects\SubscriptionData;
use App\DataTransferObjects\TransactionData;
use App\Enums\InvoiceType;
use App\Enums\OrderStatus;
use App\Enums\SubscriptionStatus;
use App\Enums\TransactionStatus;
use App\Mail\GetComment;
use App\Mail\InvoiceCreated;
use App\Mail\SubcriptionCreated;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Settings\SubscriptionSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\WebhookClient\Models\WebhookCall;

class HandleCaptured implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public WebhookCall $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle(
        SubscriptionSettings $subscriptionSettings
    )
    {
        $transactionData = $this->webhookCall->payload;

        $transactionStatus = TransactionStatus::code($transactionData['status']);

        if($transactionData['custom_data']['is_subscription_transaction']) {
            $transactionable = Subscription::find($transactionData['custom_data']['subscription_id']);
            $type = 'App\Models\Subscription';

            if($transactionable->status === SubscriptionStatus::TRIAL || $transactionable->status === SubscriptionStatus::LATE_PAYMENT) {
                $transactionable->status = SubscriptionStatus::RECURRING;
            }

            $transactionable->current_period_end_at = now()->addMonth();
            $transactionable->save();
            $invoiceType = InvoiceType::SUBSCRIPTION;
        } else {
            $transactionable = Order::find($transactionData['custom_data']['order_id']);
            $type = 'App\Models\Order';

            $transactionable->status = OrderStatus::PAID;
            $transactionable->save();

            $invoiceType = InvoiceType::ORDER;
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

        if($invoiceType === InvoiceType::ORDER) {
            (new MakeNewInvoice())->handle(
                transaction: $transaction,
                type: $invoiceType,
            );
        }

        if(!$transactionData['custom_data']['is_subscription_transaction']) {
            $document = null;

            if(!empty($transactionData['custom_data']['has_subscription'])) {
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
                        '3ds_eci' => array_key_exists('three_d_secure', $transactionData) ? $transactionData['three_d_secure']['eci'] : null,
                        '3ds_authentication_status' => array_key_exists('three_d_secure', $transactionData) ? $transactionData['three_d_secure']['authentication_status'] : null,
                        '3ds_authentication_message' => array_key_exists('three_d_secure', $transactionData) ? $transactionData['three_d_secure']['authentication_message'] : null,
                        '3ds_authentication_token' => array_key_exists('three_d_secure', $transactionData) ? $transactionData['three_d_secure']['authentication_token'] : null,
                        '3ds_xid' => array_key_exists('three_d_secure', $transactionData) ? $transactionData['three_d_secure']['xid'] : null,
                    ]
                );

                (new CreateSubscriptionAction)(new SubscriptionData(
                    designation: $subscriptionSettings->label,
                    price: $subscriptionSettings->recurring_amount,
                    status: SubscriptionStatus::TRIAL,
                    meta_data: [
                        'order_id' => $transactionData['custom_data']['order_id'],
                        'mid' => $transactionData['mid'],
                    ],
                    customer_id: $transactionData['custom_data']['customer_id'],

                    current_period_end_at: now()->addDays(16),
                    discount_rate: $subscriptionSettings->discount,
                ));

                $document = (new CreateServiceAgreementAction())($transaction->transactionable);

                Mail::to($transaction->transactionable->customer->email)->send(
                    new SubcriptionCreated(transaction: $transaction, service_agreement: $document)
                );
            }

            Mail::to($transaction->transactionable->customer->email)->send(
                new InvoiceCreated(transaction: $transaction)
            );

            Mail::to($transaction->transactionable->customer->email)->send(
                new GetComment()
            );
        }

        if (config('payment.bypass_payment')) {
            $order = Order::find($transactionData['custom_data']['order_id']);
            $url = route('frontend.letter.payment.confirmation', [
                'token' => Str::uuid(),
                'orderid' => $order->number,
            ]);
            header('Location:'.$url);
            exit;
        }
    }
}
