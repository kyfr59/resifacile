<?php

namespace App\Enums;

enum PaymentMethodStatus: string
{
    case ENABLED = 'ENABLED';
    case DISABLED = 'DISABLED';


    public function label(): string
    {
        return match($this)
        {
            self::ENABLED =>"Activé",
            self::DISABLED => 'Désactivé',
        };
    }
}
