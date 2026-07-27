<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Domaine de classement d'un document dans le coffre-fort.
 * Re-exprimé depuis la spécification gelée (inventaire R2 §C, `DOC_TREE`).
 */
enum DomaineDocument: string
{
    case CORPORATE = 'corporate';
    case BUSINESS = 'business';
    case RH = 'rh';

    public function label(): string
    {
        return match ($this) {
            self::CORPORATE => 'Corporate',
            self::BUSINESS => 'Business',
            self::RH => 'Ressources humaines',
        };
    }
}
