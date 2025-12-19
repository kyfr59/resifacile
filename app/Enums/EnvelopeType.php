<?php

namespace App\Enums;

/*
 * Utilisateur pour force en C4 mais déconseiller pour moins de 4 feuilles.
 * Utilisateur pour force en C6 si moins de 5 feuilles.
 *
 * Si pas renseigné (pas porte adresse comprise) :
 * De 1 à 5 feuilles : enveloppe petit format C6
 * De 6 à 46 feuilles : enveloppe petit format C4
 *
 * Plus chère si renseigné
 */
enum EnvelopeType: string
{
    case C6 = 'C6';
    case C4 = 'C4';

    /**
     * @return string
     */
    public function label(): string
    {
        return match($this)
        {
            self::C6 => 'Enveloppe C6 (114x229mm simple ou double-fenêtre)',
            self::C4 => 'Enveloppe C4 (210x300mm double fenêtre)',
        };
    }
}
