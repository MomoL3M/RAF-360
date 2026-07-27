<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Statut d'un encaissement attendu (alertes de trésorerie).
 * Re-exprimé depuis la spécification gelée (inventaire R2 §C, `CASH_ALERTS`).
 */
enum StatutEncaissement: string
{
    case EN_RETARD = 'en_retard';
    case ATTENDU = 'attendu';

    public function label(): string
    {
        return match ($this) {
            self::EN_RETARD => 'En retard',
            self::ATTENDU => 'Encaissement attendu',
        };
    }
}
