<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Domaine d'expertise d'un professionnel du réseau (data room).
 * Re-exprimé depuis la spécification gelée (inventaire R2 §C, `PRO_TREE`).
 */
enum DomaineProfessionnel: string
{
    case COMPTABLE = 'comptable';
    case JURIDIQUE = 'juridique';
    case FISCAL = 'fiscal';
    case SOCIAL = 'social';
    case DAF = 'daf';

    public function label(): string
    {
        return match ($this) {
            self::COMPTABLE => 'Expertise comptable',
            self::JURIDIQUE => 'Juridique',
            self::FISCAL => 'Fiscal',
            self::SOCIAL => 'Social / paie',
            self::DAF => 'Direction financière externalisée',
        };
    }
}
