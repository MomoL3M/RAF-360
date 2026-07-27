<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Régime de TVA d'une entreprise (choisi à l'onboarding).
 * Re-exprimé depuis la spécification gelée (inventaire R2 §C, `TVA_OPTS`).
 */
enum RegimeTva: string
{
    case FRANCHISE_BASE = 'franchise_base';
    case REEL_SIMPLIFIE = 'reel_simplifie';
    case REEL_NORMAL = 'reel_normal';
    case INDETERMINE = 'indetermine';

    public function label(): string
    {
        return match ($this) {
            self::FRANCHISE_BASE => 'Franchise en base',
            self::REEL_SIMPLIFIE => 'Réel simplifié',
            self::REEL_NORMAL => 'Réel normal',
            self::INDETERMINE => 'Je ne sais pas',
        };
    }
}
