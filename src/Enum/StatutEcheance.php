<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Statut d'une échéance/obligation.
 * Valeurs re-exprimées depuis la spécification gelée (inventaire R2 §C, `statutLabel`).
 */
enum StatutEcheance: string
{
    case A_FAIRE = 'a_faire';
    case A_VALIDER = 'a_valider';
    case EN_RETARD = 'en_retard';
    case RISQUE = 'risque';
    case A_CONFIRMER = 'a_confirmer';
    case ESCALADE = 'escalade';
    case ATTENTE = 'attente';

    /** Libellé lisible (français) — source unique pour l'affichage. */
    public function label(): string
    {
        return match ($this) {
            self::A_FAIRE => 'À faire',
            self::A_VALIDER => 'À valider',
            self::EN_RETARD => 'En retard',
            self::RISQUE => 'Risque élevé',
            self::A_CONFIRMER => 'À confirmer',
            self::ESCALADE => 'Escalade avocat',
            self::ATTENTE => 'En attente',
        };
    }
}
