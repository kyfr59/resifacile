<?php

namespace App\Enums;

enum MailevaStatus: string
{
    case ON_STATUS_ACCEPTED = 'ON_STATUS_ACCEPTED';
    case ON_STATUS_REJECTED = 'ON_STATUS_REJECTED';
    case ON_STATUS_PROCESSED = 'ON_STATUS_PROCESSED';
    case ON_STATUS_PROCESSED_WITH_ERRORS = 'ON_STATUS_PROCESSED_WITH_ERRORS';


    public function label(): string
    {
        return match($this)
        {
            self::ON_STATUS_ACCEPTED => "Envoi accepté",
            self::ON_STATUS_REJECTED => "Envoi rejeté",
            self::ON_STATUS_PROCESSED => "Envoi traité",
            self::ON_STATUS_PROCESSED_WITH_ERRORS => "Envoi traité avec des erreurs",
        };
    }

    public function description(): string
    {
        return match($this)
        {
            self::ON_STATUS_ACCEPTED => "L'envoi a été accepté par la plateforme Maileva.",
            self::ON_STATUS_REJECTED => "L'envoi a été rejeté par la plateforme Maileva.",
            self::ON_STATUS_PROCESSED => "L'envoi a été traité par la plateforme Maileva.",
            self::ON_STATUS_PROCESSED_WITH_ERRORS => "L'envoi a été traité par la plateforme Maileva mais comporte des erreurs.",
        };
    }

    public function short(): string
    {
        return strtolower(str_replace('ON_STATUS_', '', $this->value));
    }
}

