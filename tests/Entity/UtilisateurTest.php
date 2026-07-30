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

    public function testDoubleAuthentificationDesactiveeParDefautPuisActiveeAvecUnSecret(): void
    {
        $utilisateur = (new Utilisateur())->setEmail('admin@example.fr');
        self::assertFalse($utilisateur->isTotpAuthenticationEnabled(), '2FA inactive tant qu\'aucun secret n\'est défini.');

        $utilisateur->setTotpSecret('JBSWY3DPEHPK3PXP');
        self::assertTrue($utilisateur->isTotpAuthenticationEnabled());
        self::assertSame('admin@example.fr', $utilisateur->getTotpAuthenticationUsername());
        self::assertNotNull($utilisateur->getTotpAuthenticationConfiguration());
    }
}
