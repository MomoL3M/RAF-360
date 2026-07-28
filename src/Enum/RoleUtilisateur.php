<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Rôles applicatifs (fiche projet R1 §1.2 / décision #4).
 * La valeur EST la chaîne de rôle Symfony (`ROLE_*`).
 */
enum RoleUtilisateur: string
{
    case DIRIGEANT = 'ROLE_DIRIGEANT';
    case COLLABORATEUR = 'ROLE_COLLABORATEUR';
    case EXPERT_COMPTABLE = 'ROLE_EXPERT_COMPTABLE';
    case AVOCAT = 'ROLE_AVOCAT';
    case ADMIN = 'ROLE_ADMIN';

    public function label(): string
    {
        return match ($this) {
            self::DIRIGEANT => 'Dirigeant / admin entreprise',
            self::COLLABORATEUR => 'Collaborateur interne',
            self::EXPERT_COMPTABLE => 'Expert-comptable',
            self::AVOCAT => 'Avocat',
            self::ADMIN => 'Administrateur plateforme',
        };
    }
}
