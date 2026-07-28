<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Utilisateur;
use App\Enum\RoleUtilisateur;
use PHPUnit\Framework\TestCase;

/**
 * Vérifie les invariants de sécurité de l'utilisateur (§16).
 */
final class UtilisateurTest extends TestCase
{
    public function testContientToujoursRoleUser(): void
    {
        $utilisateur = new Utilisateur();

        self::assertContains('ROLE_USER', $utilisateur->getRoles());
    }

    public function testRolesSontUniquesEtIncluentRoleUser(): void
    {
        $utilisateur = (new Utilisateur())
            ->setRoles([RoleUtilisateur::DIRIGEANT->value, RoleUtilisateur::DIRIGEANT->value]);

        $roles = $utilisateur->getRoles();

        self::assertSame(array_unique($roles), $roles, 'Aucun rôle en double.');
        self::assertContains('ROLE_DIRIGEANT', $roles);
        self::assertContains('ROLE_USER', $roles);
    }

    public function testIdentifiantEstEmail(): void
    {
        $utilisateur = (new Utilisateur())->setEmail('dirigeant@example.fr');

        self::assertSame('dirigeant@example.fr', $utilisateur->getUserIdentifier());
    }
}
