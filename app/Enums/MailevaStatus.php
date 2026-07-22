<?php

namespace App\Enums;

enum MailevaStatus: string
{
    case ON_STATUS_ACCEPTED = 'ON_STATUS_ACCEPTED';
    case ON_STATUS_REJECTED = 'ON_STATUS_REJECTED';
    case ON_STATUS_PROCESSED = 'ON_STATUS_PROCESSED';
    case ON_CONTENT_PROOF_RECEIVED = 'ON_CONTENT_PROOF_RECEIVED';
    case ON_ACKNOWLEDGEMENT_OF_RECEIPT_RECEIVED = 'ON_ACKNOWLEDGEMENT_OF_RECEIPT_RECEIVED';
    case ON_UNDELIVERED_MAIL_RECEIVED = 'ON_UNDELIVERED_MAIL_RECEIVED';

    public function label(): string
    {
        return match($this)
        {
            self::ON_STATUS_ACCEPTED => "Envoi accepté",
            self::ON_STATUS_REJECTED => "Envoi rejeté",
            self::ON_STATUS_PROCESSED => "Envoi traité",
            self::ON_CONTENT_PROOF_RECEIVED => "Preuve de contenu disponible",
            self::ON_ACKNOWLEDGEMENT_OF_RECEIPT_RECEIVED => "Distributé, preuve de réception disponible",
            self::ON_UNDELIVERED_MAIL_RECEIVED => "Non distributé"
        };
    }

    public function description(): string
    {
        return match($this)
        {
            self::ON_STATUS_ACCEPTED => "L'envoi a été accepté par la plateforme Maileva.",
            self::ON_STATUS_REJECTED => "L'envoi a été rejeté par la plateforme Maileva.",
            self::ON_STATUS_PROCESSED => "L'envoi a été traité par la plateforme Maileva.",
            self::ON_CONTENT_PROOF_RECEIVED => "Preuve de contenu disponible",
            self::ON_ACKNOWLEDGEMENT_OF_RECEIPT_RECEIVED => "Distributé, preuve de réception disponible",
            self::ON_UNDELIVERED_MAIL_RECEIVED => "Non distributé"
        };
    }

    public function short(): string
    {
        $return = strtolower(str_replace('ON_STATUS_', '', $this->value));
        $return = str_replace('on_', '', $return);
        return $return;
    }
}

