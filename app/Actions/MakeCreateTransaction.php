<?php

namespace App\Actions;

use App\DataTransferObjects\TransactionData;
use App\Models\Transaction;

class MakeCreateTransaction
{
    /**
     * @param TransactionData $transactionData
     * @return Transaction
     */
    public function handle(TransactionData $transactionData): Transaction
    {
        return Transaction::create($transactionData->toArray());
    }
}
