<?php

namespace App\Http\Controllers\SaleProcess;

use App\Helpers\Accounting;
use App\Http\Controllers\Controller;
use App\Settings\PricingSettings;
use App\Settings\SubscriptionSettings;
use Illuminate\Contracts\View\View;

class PostageController extends Controller
{
    /**
     * @return View
     */
    public function show(
        PricingSettings $pricingSettings,
        SubscriptionSettings $subscriptionSettings,
        Accounting $accounting
    ): View
    {
        return view('sale-process.letter-postage', [
            'pricing' => $pricingSettings,
            'subscription' => $subscriptionSettings,
            'accounting' => $accounting,
        ]);
    }
}
