<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AccountingSettings extends Settings
{
    public int $vat_rate;

    public int $invoice_number;

    public int $order_number;

    public int $customer_number;

    public int $subscription_number;

    public string $order_prefix;

    public string $invoice_prefix;

    public string $customer_prefix;

    public string $subscription_prefix;

    public static function group(): string
    {
        return 'accounting';
    }
}
