<?php

namespace App\Actions\Transaction;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use App\Registries\PaymentGatewayRegistry;
use Illuminate\Support\Facades\App;

class RefundProcess
{
    public function __construct(
        public Transaction $transaction
    ){}

    public function send(
        float|null $amount,
    )
    {
        $paymentGateway = App::make(PaymentGatewayRegistry::class)->get($this->transaction->mid);

        $paymentGateway->refund(
            $this->transaction->transaction_id,
            $amount,
            $this->transaction->transactionable_id
        );
    }
}
