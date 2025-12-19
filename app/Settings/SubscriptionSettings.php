<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SubscriptionSettings extends Settings
{
    public float $amount;

    public float $recurring_amount;

    public int $period;

    public int $trial_period;

    public string $label;

    public int $discount;

    public string $mention_one;

    public string $mention_two;

    public string $mention_three;

    public string $mention_four;

    public static function group(): string
    {
        return 'subscription';
    }
}
