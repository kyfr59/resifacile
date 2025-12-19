<?php

namespace App\Actions;

use App\Contracts\Cart;
use App\DataTransferObjects\OrderData;
use App\Helpers\Accounting;
use App\Models\Customer;
use App\Models\Order;
use App\Settings\AccountingSettings;
use Illuminate\Support\Facades\App;

class MakeNewOrder
{
    public function __construct(readonly private Customer $customer)
    {}

    /**
     * @return Order
     */
    public function handle(): Order
    {
        $accountingSettings = App::make(AccountingSettings::class);
        $cart = App::make(Cart::class);

        ++$accountingSettings->order_number;

        $orderNumber = Accounting::makeNumber(
            prefix: $accountingSettings->order_prefix,
            number: $accountingSettings->order_number,
        );

        $orderData = $cart->getOrder();

        $order = new Order([
            'number' => $orderNumber,
            'postage' => $orderData->postage,
            'vat_rate' => $orderData->vat_rate,
            'amount' => $orderData->amount,
            'options' => $orderData->options,
            'documents_compliant' => $orderData->customer_certifies_documents_are_compliant,
            'details' => [
                'documents' => $cart->getDocuments(),
                'recipients' => $cart->getRecipients(),
                'senders' => $cart->getSenders(),
                'pricing_postage' => $orderData->postage->price(),
            ],
            'with_subscription' => $orderData->with_subscription,
            'ip_address' => session()->get('ipClient'),
            'customer_id' => $this->customer->id,
        ]);

        $order->customer()->associate($this->customer);

        $order->save();

        $accountingSettings->save();

        return $order;
    }
}
