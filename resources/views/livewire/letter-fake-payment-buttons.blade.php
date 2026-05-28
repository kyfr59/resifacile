@inject('accounting', 'App\Helpers\Accounting')
<form id="payment-fake-form" method="POST" action="/webhook-hipay">
    @csrf
    <input type="hidden" name="status" value="118" />
    <input type="hidden" name="transaction_reference" value="TX123456" />
    <input type="hidden" name="mid" value="MERCHANT123" />
    <br><br>
    <br><br><input type="hidden" name="custom_data[with_subscription]" value="{{ $orderInfos['with_subscription'] }}" />
    <br><br><input type="hidden" name="custom_data[has_subscription]" value="{{ $orderInfos['has_subscription'] }}" />
    <br>
    <br><br><input type="hidden" name="custom_data[is_subscription_transaction]" value="" />
    <br><br><input type="hidden" name="custom_data[subscription_id]" value="" />
    <br><br><input type="hidden" name="custom_data[order_id]" value="{{ $order->id }}" />
    <input type="hidden" name="custom_data[customer_id]" value="{{ $order->customer_id }}" />
    <input
        type="hidden"
        name="authorized_amount"
        value="{{ number_format($accounting::addTax($order->amount) / 100, 2, '.', '') }}"
    />
    <!--<input class="w-full md:w-auto md:flex-1 sm:w-auto hover:outline hover:outline-offset-2 hover:outline-4 hover:outline-blue-100 text-gray-800 border-2 border-gray-200 h-14 px-3 rounded-xl inline-flex items-center gap-6 justify-center" type="submit" value="Paiement ok" />-->
</form>