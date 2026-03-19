<?php

namespace App\Enums;

enum MailevaStatus: string
{
    case ON_STATUS_ACCEPTED = 'ON_STATUS_ACCEPTED';
    case ON_STATUS_REJECTTED = 'ON_STATUS_REJECTTED';

    public function label(): string
    {
        return match($this)
        {
            self::ON_STATUS_ACCEPTED => "Envoi accepté",
            self::ON_STATUS_REJECTTED => "Envoi rejeté",
        };
    }

    public function description(): string
    {
        return match($this)
        {
            self::ON_STATUS_ACCEPTED => "L'envoi a été accepté par la plateforme Maileva.",
            self::ON_STATUS_REJECTTED => "L'envoi a été rejeté par la plateforme Maileva.",
        };
    }

    public function short(): string
    {
        return strtolower(str_replace('ON_STATUS_', '', $this->value));
    }
}

