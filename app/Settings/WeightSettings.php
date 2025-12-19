<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class WeightSettings extends Settings
{

    public array $paper;

    public array $envelope;

    public static function group(): string
    {
        return 'weight';
    }
}
