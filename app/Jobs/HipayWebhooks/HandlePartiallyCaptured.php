<?php

namespace App\Jobs\HipayWebhooks;

use App\Actions\MakeCreateTransaction;
use App\Actions\MakeNewInvoice;
use App\Actions\Subscription\CreateServiceAgreementAction;
use App\DataTransferObjects\TransactionData;
use App\Enums\InvoiceType;
use App\Enums\OrderStatus;
use App\Enums\TransactionStatus;
use App\Mail\GetComment;
use App\Mail\InvoiceCreated;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Spatie\WebhookClient\Models\WebhookCall;

class HandlePartiallyCaptured implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public WebhookCall $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle(): void
    {
        /*
        $transactionData = $this->webhookCall->payload;

        $transactionStatus = TransactionStatus::code($transactionData['status']);

        $transaction = Transaction::where('transaction_id', $transactionData['transaction_reference'])->first();

        (new MakeCreateTransaction())->handle(
            transactionData: new TransactionData(
                amount: $transactionData['captured_amount'] * 100,
                mid: $transactionData['mid'],
                status: $transactionStatus,
                transaction_id: $transactionData['transaction_reference'],
                transactionable_id: $transaction->transactionable_id,
                transactionable_type: $transaction->transactionable_type,
            ),
        );

        if(! $transactionData['custom_data']['is_subscription_transaction']) {
            $type = InvoiceType::SUBSCRIPTION;
        } else {
            $transaction->transactionable->status = OrderStatus::PAID;
            $transaction->transactionable->save();

            $type = InvoiceType::ORDER;
        }

        (new MakeNewInvoice())->handle(
            transaction: $transaction,
            type: InvoiceType::ORDER,
        );

        if(! $transactionData['custom_data']['is_subscription_transaction']) {
            $document = null;

            if($transactionData['custom_data']['has_subscription']) {
                $document = (new CreateServiceAgreementAction())($transaction->transactionable);
            }

            Mail::to($transaction->transactionable->customer->email)->send(
                new InvoiceCreated(transaction: $transaction, service_agreement: $document)
            );

            Mail::to($transaction->transactionable->customer->email)->send(
                new GetComment()
            );
        }
        */
    }
}
