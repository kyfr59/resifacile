<?php

namespace App\Enums;

enum SendingStatus: string
{
    case WAITING    = 'WAITING';
    case SENDED     = 'SENDED';
    case ACCEPTED   = 'ACCEPTED';
    case REJECTED   = 'REJECTED';
    case PROCESSED  = 'PROCESSED';
    case DELIVERED  = 'DELIVERED';
    case UNDELIVERED  = 'UNDELIVERED';

    public function label(): string
    {
        return match ($this) {
            self::WAITING => "En attente d'envoi à Maileva",
            self::SENDED => 'Envoyée à Maileva',
            self::ACCEPTED => 'Acceptée par Maileva',
            self::REJECTED => 'Rejeté par Maileva',
            self::PROCESSED => 'Traitée par Maileva',
            self::DELIVERED => 'Distribué par La Poste',
            self::UNDELIVERED => 'Non distribué',
        };
    }

    public static function values(): array
    {
        return [
            self::WAITING->value => self::WAITING->label(),
            self::SENDED->value => self::SENDED->label(),
            self::ACCEPTED->value => self::ACCEPTED->label(),
            self::REJECTED->value => self::REJECTED->label(),
            self::PROCESSED->value => self::PROCESSED->label(),
            self::DELIVERED->value => self::DELIVERED->label(),
            self::UNDELIVERED->value => self::UNDELIVERED->label(),
        ];
    }
}
