<?php

namespace App\Enums;

enum SendingStatus: string
{
    case DRAFT      = 'DRAFT';
    case SENDED     = 'SENDED';
    case ACCEPTED   = 'ACCEPTED';
    case PROCESSED  = 'PROCESSED';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Brouillon',
            self::SENDED => 'Envoyée à Maileva',
            self::ACCEPTED => 'Acceptée par Maileva',
            self::PROCESSED => 'Traitée par Maileva',
        };
    }

    public static function values(): array
    {
        return [
            self::DRAFT->value => self::DRAFT->label(),
            self::SENDED->value => self::ACTIVE->label(),
            self::ACCEPTED->value => self::PENDING_EXECUTION->label(),
            self::PROCESSED->value => self::EXECUTED->label(),
        ];
    }
}
