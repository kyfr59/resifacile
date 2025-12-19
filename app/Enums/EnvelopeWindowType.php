<?php

namespace App\Enums;

/*
 * Uniquement pour les enveloppes C6.
 */
enum EnvelopeWindowType: string
{
    case SMPL = 'SMPL';
    case DBL = 'DBL';

    /**
     * @return string
     */
    public function label(): string
    {
        return match($this)
        {
            self::SMPL => 'Enveloppe Simple fenêtre',
            self::DBL => 'Enveloppe Double fenêtre',
        };
    }
}
