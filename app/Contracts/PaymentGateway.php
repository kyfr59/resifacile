<?php

namespace App\Contracts;

use App\DataTransferObjects\TransactionRedirectData;
use App\Models\Order;

interface PaymentGateway
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param array $payload
     * @return void
     */
    public function capture(array $payload): void;

    /**
     * @param array $payload
     * @return TransactionRedirectData|bool
     */
    public function progressPayment(array $payload): TransactionRedirectData|bool;
}
