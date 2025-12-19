<?php

namespace App\Jobs\HipayWebhooks;

use App\Actions\MakeCreateTransaction;
use App\DataTransferObjects\TransactionData;
use App\Enums\TransactionStatus;
use App\Mail\RefundConfirmation;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Spatie\WebhookClient\Models\WebhookCall;

class HandleRefunded implements ShouldQueue
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

        $transaction = Transaction::where('transaction_id', $transactionData['transaction_reference'])->first();

        (new MakeCreateTransaction())->handle(
            transactionData: new TransactionData(
                amount: $transactionData['refunded_amount'] * 100,
                mid: $transactionData['mid'],
                status: $transactionStatus,
                transaction_id: $transactionData['transaction_reference'],
                transactionable_id: $transaction->transactionable_id,
                transactionable_type: $transaction->transactionable_type,
            ),
        );

        Mail::to($transaction->transactionable->customer->email)->send(new RefundConfirmation($transaction));
    }
}
