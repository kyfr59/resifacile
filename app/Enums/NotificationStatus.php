<?php

namespace App\Enums;

enum NotificationStatus: string
{
    case OK = 'OK';
    case NOK = 'NOK';
    case ACCEPT = 'ACCEPT';
    case NACCEPT = 'NACCEPT';
}
