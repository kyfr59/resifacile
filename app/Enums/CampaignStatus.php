<?php

namespace App\Enums;

enum CampaignStatus: string
{
    case DRAFT = 'DRAFT';
    case ACTIVE = 'ACTIVE';
    case PENDING_EXECUTION = 'PENDING_EXECUTION';
    case EXECUTED = 'EXECUTED';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Brouillon',
            self::ACTIVE => 'En cours de collecte',
            self::PENDING_EXECUTION => 'En attente',
            self::EXECUTED => 'Terminer',
        };
    }

    public static function values(): array
    {
        return [
            self::DRAFT->value => self::DRAFT->label(),
            self::ACTIVE->value => self::ACTIVE->label(),
            self::PENDING_EXECUTION->value => self::PENDING_EXECUTION->label(),
            self::EXECUTED->value => self::EXECUTED->label(),
        ];
    }
}
