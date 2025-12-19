<?php

namespace App\DataTransferObjects;

use App\Enums\TransactionErrorType;
use App\Enums\TransactionStatus;
use App\Models\Order;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class TransactionData extends Data
{

    /**
     * @param int $amount
     * @param string $mid
     * @param TransactionStatus $status
     * @param string $transaction_id
     * @param int $transactionable_id
     * @param string $transactionable_type
     * @param TransactionErrorType|null $error_type
     */
    public function __construct(
        #[Required]
        public int $amount,
        #[Required]
        public string $mid,
        #[Required]
        public TransactionStatus $status,
        #[Required]
        public string $transaction_id,
        #[Required]
        public int $transactionable_id,
        #[Required]
        public string $transactionable_type,
        public ?string $error_type = null,
    ){
    }
}
