<?php

namespace App\Helpers;

use App\Contracts\Cart;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Settings\AccountingSettings;
use App\Settings\PricingSettings;
use App\Settings\SubscriptionSettings;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Accounting
{
    public static function addTax(int $value): int
    {
        return round($value * (1 + App::make(AccountingSettings::class)->vat_rate / 100));
    }

    public static function removeTax(int $value): int
    {
        return round($value / (1 + App::make(AccountingSettings::class)->vat_rate / 100));
    }

    public static function addDiscount(int $value): int
    {
        return $value - ($value * (App::make(SubscriptionSettings::class)->discount / 100));
    }

    public static function hasSubscription(int $value): int
    {
        if(App::make(Cart::class)->getOrder()->with_subscription) {
            $value = $value - ($value * (App::make(SubscriptionSettings::class)->discount / 100));
        }

        return $value;
    }

    public static function withSubscription(int $value, bool $withSubscription): int
    {
        if($withSubscription) {
            $value = $value - ($value * (App::make(SubscriptionSettings::class)->discount / 100));
        }

        return $value;
    }

    public static function priceFormat(int $value): string
    {
        return  number_format($value / 100, 2, ',', ' ') . ' €';
    }

    public static function makeNumber(string $prefix, int $number): string
    {
        return $prefix . str_pad($number, 10, "0", STR_PAD_LEFT);
    }
}
