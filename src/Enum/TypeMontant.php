<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Nature d'un montant d'échéance : chiffre réel ou estimation.
 * Re-exprimé depuis la spécification gelée (inventaire R2 §C, flag `mt`).
 */
enum TypeMontant: string
{
    case REEL = 'reel';
    case ESTIMATIF = 'estimatif';

    public function label(): string
    {
        return match ($this) {
            self::REEL => 'Montant réel',
            self::ESTIMATIF => 'Montant estimatif',
        };
    }
}
