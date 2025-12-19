<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class PricingSettings extends Settings
{
    public int $receipt;

    public int $sms_notification;

    public array $followed_letter;

    public array $green_letter;

    public array $registered_letter;

    public array $black_print;

    public array $color_print;

    public float $recto_verso;

    public static function group(): string
    {
        return 'pricing';
    }
}
