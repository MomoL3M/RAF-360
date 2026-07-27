<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Niveau de priorité d'une action du centre d'actions.
 * Re-exprimé depuis la spécification gelée (inventaire R2 §C, `ACTIONS`).
 */
enum PrioriteAction: string
{
    case HAUTE = 'haute';
    case MOYENNE = 'moyenne';
    case BASSE = 'basse';

    public function label(): string
    {
        return match ($this) {
            self::HAUTE => 'Haute',
            self::MOYENNE => 'Moyenne',
            self::BASSE => 'Basse',
        };
    }
}
