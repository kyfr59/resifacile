<?php

namespace App\Enums;

enum AppType: string
{
    case TERMINATION_LETTER = 'TERMINATION_LETTER';
    case REGISTERED_LETTER = 'REGISTERED_LETTER';
    case POST_OFFICE = 'POST_OFFICE';
}
