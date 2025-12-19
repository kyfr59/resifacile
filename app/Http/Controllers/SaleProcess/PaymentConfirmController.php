<?php

namespace App\Http\Controllers\SaleProcess;

use App\Contracts\Cart;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Registries\PaymentGatewayRegistry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class PaymentConfirmController extends Controller
{
    /**
     * @return View|RedirectResponse
     */
    public function __invoke(Request $request, Cart $cart)
    {
        if (
            $request->has('token') ||
            $request->has('hash') ||
            $request->has('orderid')
        ) {
            $order = Order::where('number', $request->input('orderid'))->first();

            Session::flush();

            return view('sale-process.letter-payment-confirmation', [
                'message' => 'Bravo ! Votre courrier sera bientôt pris en charge.',
                'price' => $order->amount,
                'transaction_id' => $order->number,
                'with_subscription' => $order->with_subscription,
            ]);
        }

        Session::flush();
        return redirect('/');
    }
}
