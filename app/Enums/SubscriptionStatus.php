<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case TRIAL = 'TRIAL';
    case CANCEL_REQUEST = 'CANCEL_REQUEST';
    case CANCELED = 'CANCELED';
    case RECURRING = 'RECURRING';
    case LATE_PAYMENT = 'LATE_PAYMENT';

    public function label(): string
    {
        return match($this)
        {
            self::TRIAL => 'Essais',
            self::CANCEL_REQUEST => 'Demande de désabonnement',
            self::CANCELED => 'Désabonné',
            self::RECURRING => 'Récurrent',
            self::LATE_PAYMENT => 'Retard de paiement',
        };
    }
}
