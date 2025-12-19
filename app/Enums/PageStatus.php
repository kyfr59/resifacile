<?php

namespace App\Enums;

enum PageStatus: string
{
    case DRAFT = 'DRAFT';
    case HIDDEN = 'HIDDEN';
    case VISIBLE = 'VISIBLE';

    public function label(): string
    {
        return match($this)
        {
            self::DRAFT => 'Brouillon',
            self::HIDDEN => 'Inaccessible',
            self::VISIBLE => 'Accessible',
        };
    }
}
