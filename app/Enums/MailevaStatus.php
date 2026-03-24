<?php

namespace App\Enums;

enum MailevaStatus: string
{
    case ON_STATUS_ACCEPTED = 'ON_STATUS_ACCEPTED';
    case ON_STATUS_REJECTTED = 'ON_STATUS_REJECTTED';
    case ON_STATUS_PROCESSED = 'ON_STATUS_PROCESSED';

    public function label(): string
    {
        return match($this)
        {
            self::ON_STATUS_ACCEPTED => "Envoi accepté",
            self::ON_STATUS_REJECTTED => "Envoi rejeté",
            self::ON_STATUS_PROCESSED => "Envoi traité",
        };
    }

    public function description(): string
    {
        return match($this)
        {
            self::ON_STATUS_ACCEPTED => "L'envoi a été accepté par la plateforme Maileva.",
            self::ON_STATUS_REJECTTED => "L'envoi a été rejeté par la plateforme Maileva.",
            self::ON_STATUS_PROCESSED => "L'envoi a été traité par la plateforme Maileva.",
        };
    }

    public function short(): string
    {
        return strtolower(str_replace('ON_STATUS_', '', $this->value));
    }
}

