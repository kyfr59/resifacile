<?php

namespace App\Actions;

use App\Contracts\Cart;
use App\DataTransferObjects\OrderData;
use App\Enums\PaymentMethodType;
use App\Helpers\Accounting;
use App\Models\Customer;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Settings\AccountingSettings;
use Illuminate\Support\Facades\App;

class MakeNewPaymentMethod
{
    public function __construct(readonly private Customer $customer)
    {}

    public function __invoke(string $card_id, array $card): PaymentMethod
    {
        $paymentMethod = new PaymentMethod([
            'card_id' => $card_id,
            'type' => PaymentMethodType::CARD,
            'data' => $card,
        ]);

        $paymentMethod->customer()->associate($this->customer);

        $paymentMethod->save();

        return $paymentMethod;
    }
}
