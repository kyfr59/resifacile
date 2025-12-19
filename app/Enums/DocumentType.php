<?php

namespace App\Enums;

enum DocumentType: string
{
    case JPG = 'JPG';
    case PDF = 'PDF';
    case TEMPLATE = 'TEMPLATE';
    case HANDWRITE = 'HANDWRITE';
    case TXT = 'TXT';
    case WORD = 'WORD';
    case SERVICE_AGREEMENT = 'SERVICE_AGREEMENT';

    public function label(): string
    {
        return match($this)
        {
            self::JPG => 'Jpg',
            self::SERVICE_AGREEMENT, self::PDF, self::TEMPLATE, self::HANDWRITE => 'Pdf',
            self::TXT => 'Text',
            self::WORD => 'Word',
        };
    }

    public function status(): string
    {
        return match($this)
        {
            self::SERVICE_AGREEMENT => 'conditions générales de vente',
            self::TEMPLATE => 'modèle de lettre',
            self::HANDWRITE => 'lettre rédigée',
            self::JPG, self::PDF, self::TXT, self::WORD => 'Document transmit',
        };
    }
}
