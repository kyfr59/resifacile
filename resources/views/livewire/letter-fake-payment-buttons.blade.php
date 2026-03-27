@inject('accounting', 'App\Helpers\Accounting')
<form method="POST" action="/webhook-hipay">
    @csrf
    <input type="text" name="status" value="118" />
    <input type="text" name="transaction_reference" value="TX123456" />
    <input type="text" name="mid" value="MERCHANT123" />
    <br><br>
    <br><br>with_subscription : <input type="text" name="custom_data[with_subscription]" value="{{ $orderInfos['with_subscription'] }}" />
    <br><br>has_subscription : <input type="text" name="custom_data[has_subscription]" value="{{ $orderInfos['has_subscription'] }}" />
    <br>
    <br><br>is_subscription_transaction : <input type="text" name="custom_data[is_subscription_transaction]" value="" />
    <br><br>subscription_id : <input type="text" name="custom_data[subscription_id]" value="" />
    <br><br><input type="text" name="custom_data[order_id]" value="{{ $order->id }}" />
    <input type="text" name="custom_data[customer_id]" value="{{ $order->customer_id }}" />
    <input
        type="text"
        name="authorized_amount"
        value="{{ number_format($accounting::addTax($order->amount) / 100, 2, '.', '') }}"
    />
    <input class="w-full md:w-auto md:flex-1 sm:w-auto hover:outline hover:outline-offset-2 hover:outline-4 hover:outline-blue-100 text-gray-800 border-2 border-gray-200 h-14 px-3 rounded-xl inline-flex items-center gap-6 justify-center" type="submit" value="Paiement ok" />
</form>