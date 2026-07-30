<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Service\PurgeDonnees;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * La purge écrit dans des comptes réels : ses garde-fous (exclusion des administrateurs,
 * mode simulation) doivent être vérifiés, pas supposés (§17.1).
 */
final class PurgeDonneesTest extends TestCase
{
    public function testUnCompteClientInactifEstAnonymise(): void
    {
        $compte = $this->compte(42, ['ROLE_DIRIGEANT']);
        $compte->setTotpSecret('SECRET32');
        $ancienMotDePasse = $compte->getPassword();

        $traites = $this->purge(flushAttendu: true)->anonymiserLot([$compte]);

        self::assertSame([42], $traites);
        self::assertSame('anonyme-42@supprime.raf360.invalid', $compte->getEmail());
        self::assertSame(['ROLE_USER'], $compte->getRoles(), 'les rôles métier sont retirés');
        self::assertNull($compte->getTotpSecret(), 'le second facteur est effacé');
        self::assertNotSame($ancienMotDePasse, $compte->getPassword(), 'aucun mot de passe ne peut plus correspondre');
        self::assertNotNull($compte->getAnonymiseLe(), 'l\'horodatage rend la purge idempotente');
    }

    public function testUnAdministrateurNestJamaisAnonymiseAutomatiquement(): void
    {
        $admin = $this->compte(7, ['ROLE_ADMIN']);

        $traites = $this->purge(flushAttendu: false)->anonymiserLot([$admin]);

        self::assertSame([], $traites);
        self::assertSame('inactif@example.com', $admin->getEmail(), 'le compte d\'administration est intact');
    }

    public function testLaSimulationNecritRien(): void
    {
        $compte = $this->compte(11, ['ROLE_DIRIGEANT']);

        $traites = $this->purge(flushAttendu: false)->anonymiserLot([$compte], simulation: true);

        self::assertSame([11], $traites, 'la simulation rapporte ce qui SERAIT purgé');
        self::assertSame('inactif@example.com', $compte->getEmail(), 'mais ne modifie aucune donnée');
        self::assertNull($compte->getAnonymiseLe());
    }

    /**
     * @param list<string> $roles
     */
    private function compte(int $id, array $roles): Utilisateur
    {
        $compte = new Utilisateur();
        $compte->setEmail('inactif@example.com')
            ->setPrenom('Awa')
            ->setNom('Ndiaye')
            ->setPassword('$argon2id$origine')
            ->setRoles($roles);

        // L'identifiant est généré par Doctrine : on le pose ici pour un scénario réaliste.
        $reflexion = new \ReflectionProperty(Utilisateur::class, 'id');
        $reflexion->setValue($compte, $id);

        return $compte;
    }

    private function purge(bool $flushAttendu): PurgeDonnees
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($flushAttendu ? self::once() : self::never())->method('flush');

        $registry = $this->createStub(ManagerRegistry::class);
        $registry->method('getManagerForClass')->willReturn($em);

        return new PurgeDonnees($em, new UtilisateurRepository($registry), $this->createStub(LoggerInterface::class));
    }
}
