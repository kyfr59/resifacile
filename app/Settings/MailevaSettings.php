<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MailevaSettings extends Settings
{
    public string $version;

    public string $name;

    public int $sending_number;

    public ?string $media_type = null;

    public ?string $sending_prefix = null;

    public ?string $mention_one = null;

    public ?string $mention_two = null;

    public ?string $mention_three = null;

    public static function group(): string
    {
        return 'maileva';
    }
}