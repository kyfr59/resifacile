<?php

namespace App\Actions;

use App\Actions\Customer\UpdateCustomerAction;
use App\Contracts\Cart;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\App;

class MakeUpdateOrder
{
    public function __construct(readonly private ?Customer $customer = null)
    {}

    /**
     * @param int $order_id
     * @return Order
     */
    public function handle(int $order_id): Order
    {
        $cart = App::make(Cart::class);

        $orderData = $cart->getOrder();

        $order = Order::findOrFail($order_id);
        $order->postage = $orderData->postage;
        $order->vat_rate = $orderData->vat_rate;
        $order->amount = $orderData->amount;
        $order->options = $orderData->options;
        $order->documents_compliant = $orderData->customer_certifies_documents_are_compliant;
        $order->details = [
            'documents' => $cart->getDocuments(),
            'recipients' => $cart->getRecipients(),
            'senders' => $cart->getSenders(),
            'pricing_postage' => $orderData->postage->price(),
        ];
        $order->ip_address = session()->get('ipClient');
        $order->with_subscription = $orderData->with_subscription;
        $order->save();

        if($this->customer) {
            $order->customer()->associate($this->customer);
        }

        return $order;
    }
}
