<?php

namespace App\Enums;

enum OkapiStatus: string
{
    case DR1 = 'DR1';
    case DR2 = 'DR2';
    case PC1 = 'PC1';
    case PC2 = 'PC2';
    case ET1 = 'ET1';
    case ET2 = 'ET2';
    case ET3 = 'ET3';
    case ET4 = 'ET4';
    case EP1 = 'EP1';
    case DO1 = 'DO1';
    case DO2 = 'DO2';
    case DO3 = 'DO3';
    case PB1 = 'PB1';
    case PB2 = 'PB2';
    case MD2 = 'MD2';
    case ND1 = 'ND1';
    case AG1 = 'AG1';
    case RE1 = 'RE1';
    case DI0 = 'DI0';
    case DI1 = 'DI1';
    case DI2 = 'DI2';
    case DI3 = 'DI3';
    case ID0 = 'ID0';

    public function label(): string
    {
        return match ($this) {
            self::DR1 => 'Déclaratif réceptionné',
            self::DR2 => 'Problème lors de la préparation',
            self::PC1 => 'Pris en charge',
            self::PC2 => 'Pris en charge dans le pays d’expédition',
            self::ET1 => 'En cours de traitement',
            self::ET2 => 'En cours de traitement dans le pays d’expédition',
            self::ET3 => 'En cours de traitement dans le pays de destination',
            self::ET4 => 'En cours de traitement dans un pays de transit',
            self::EP1 => 'En attente de présentation',
            self::DO1 => 'Entrée en Douane',
            self::DO2 => 'Sortie  de Douane',
            self::DO3 => 'Retenu en Douane',
            self::PB1 => 'Problème en cours',
            self::PB2 => 'Problème résolu',
            self::MD2 => 'Mis en distribution',
            self::ND1 => 'Non distribuable',
            self::AG1 => 'En attente d\'être retiré au guichet',
            self::RE1 => 'Retourné à l\'expéditeur',
            self::DI0 => 'Distribué en lot',
            self::DI1 => 'Distribué',
            self::DI2 => 'Distribué à l\'expéditeur',
            self::DI3 => 'Retardé',
            self::ID0 => 'Informations douane',
        };
    }

    public static function values(): array
    {
        return [
            self::DR1->value => seld::DR1->label(),
            self::DR2->value => seld::DR2->label(),
            self::PC1->value => seld::PC1->label(),
            self::PC2->value => seld::PC2->label(),
            self::ET1->value => seld::ET1->label(),
            self::ET2->value => seld::ET2->label(),
            self::ET3->value => seld::ET3->label(),
            self::ET4->value => seld::ET4->label(),
            self::EP1->value => seld::EP1->label(),
            self::DO1->value => seld::DO1->label(),
            self::DO2->value => seld::DO2->label(),
            self::DO3->value => seld::DO3->label(),
            self::PB1->value => seld::PB1->label(),
            self::PB2->value => seld::PB2->label(),
            self::MD2->value => seld::MD2->label(),
            self::ND1->value => seld::ND1->label(),
            self::AG1->value => seld::AG1->label(),
            self::RE1->value => seld::RE1->label(),
            self::DI0->value => seld::DI0->label(),
            self::DI1->value => seld::DI1->label(),
            self::DI2->value => seld::DI2->label(),
            self::DI3->value => seld::DI3->label(),
            self::ID0->value => seld::ID0->label(),
        ];
    }
}
