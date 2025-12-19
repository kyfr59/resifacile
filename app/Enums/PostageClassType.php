<?php

namespace App\Enums;

enum PostageClassType: string
{
    case STANDARD = 'STANDARD';
    case SLOW = 'SLOW';
    case LETTRE_GRAND_COMPTE = 'LETTRE_GRAND_COMPTE';
    case ECOPLI_GRAND_COMPTE = 'ECOPLI_GRAND_COMPTE';
    case LETTRE_VERTE = 'LETTRE_VERTE';
    case LRE = 'LRE';
    case LRE_AR = 'LRE_AR';
    case RECOMMANDE = 'RECOMMANDE';
    case RECOMMANDE_AR = 'RECOMMANDE_AR';
    case LSM = 'LSM';

    public function label(): string
    {
        return match($this)
        {
            self::STANDARD => 'Lettre',
            self::SLOW => 'Ecopli',
            self::LETTRE_GRAND_COMPTE => 'Lettre Grand Compte',
            self::ECOPLI_GRAND_COMPTE => 'Ecopli Grand Compte',
            self::LETTRE_VERTE => 'Lettre Verte',
            self::LRE => 'Lettre Recommandée en Ligne sans Avis de Réception',
            self::LRE_AR => 'Lettre Recommandée en Ligne avec Avis de Réception',
            self::RECOMMANDE => 'Lettre recommandée sans Avis de Réception',
            self::RECOMMANDE_AR => 'Lettre recommandée avec Avis de Réception',
            self::LSM => 'Lettre suivie',
        };
    }
}
