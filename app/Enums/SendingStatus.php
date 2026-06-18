<?php

namespace App\Enums;

enum SendingStatus: string
{
    case WAITING    = 'WAITING';
    case SENDED     = 'SENDED';
    case ACCEPTED   = 'ACCEPTED';
    case PROCESSED  = 'PROCESSED';
    case DELIVERED  = 'DELIVERED';

    public function label(): string
    {
        return match ($this) {
            self::WAITING => "En attente d'envoi à Maileva",
            self::SENDED => 'Envoyée à Maileva',
            self::ACCEPTED => 'Acceptée par Maileva',
            self::PROCESSED => 'Traitée par Maileva',
            self::DELIVERED => 'Distribué par La Poste',
        };
    }

    public static function values(): array
    {
        return [
            self::WAITING->value => self::WAITING->label(),
            self::SENDED->value => self::ACTIVE->label(),
            self::ACCEPTED->value => self::PENDING_EXECUTION->label(),
            self::PROCESSED->value => self::EXECUTED->label(),
            self::DELIVERED->value => self::DELIVERED->label(),
        ];
    }
}
