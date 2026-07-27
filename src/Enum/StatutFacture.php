<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Statut de traitement d'une facture.
 * Re-exprimé depuis la spécification gelée (inventaire R2 §C, KPIs factures).
 */
enum StatutFacture: string
{
    case A_TRAITER = 'a_traiter';
    case VALIDEE = 'validee';
    case DOUBLON = 'doublon';

    public function label(): string
    {
        return match ($this) {
            self::A_TRAITER => 'À traiter',
            self::VALIDEE => 'Validée',
            self::DOUBLON => 'Doublon',
        };
    }
}
