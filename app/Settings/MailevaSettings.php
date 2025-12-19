<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MailevaSettings extends Settings
{
    public string $version;

    public string $name;

    public int $campaign_number;

    public string $media_type;

    public string $campaign_prefix;

    public string $mention_one;

    public string $mention_two;

    public string $mention_three;

    public static function group(): string
    {
        return 'maileva';
    }
}
