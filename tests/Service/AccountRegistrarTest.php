<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Service\AccountRegistrar;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AccountRegistrarTest extends TestCase
{
    public function testRegisterHacheLeMotDePasseNormaliseLEmailEtAssigneDirigeant(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::once())->method('persist')->with(self::isInstanceOf(Utilisateur::class));
        $em->expects(self::once())->method('flush');
        $em->method('getClassMetadata')->willReturn(new ClassMetadata(Utilisateur::class));

        $hasher = $this->createMock(UserPasswordHasherInterface::class);
        $hasher->expects(self::once())->method('hashPassword')->willReturn('$argon2id$fake');

        $registrar = new AccountRegistrar($em, $hasher, $this->repository($em));
        $utilisateur = $registrar->register('  Awa@Example.COM ', 'motdepassefort2026', 'Awa', 'Ndiaye');

        self::assertSame('awa@example.com', $utilisateur->getEmail(), 'l\'e-mail est normalisé (minuscule, trim)');
        self::assertContains('ROLE_DIRIGEANT', $utilisateur->getRoles());
        self::assertSame('$argon2id$fake', $utilisateur->getPassword(), 'le mot de passe stocké est le haché, jamais le clair');
    }

    /** Construit le vrai repository (final) via un ManagerRegistry stubé — non appelé par register(). */
    private function repository(EntityManagerInterface $em): UtilisateurRepository
    {
        $registry = $this->createStub(ManagerRegistry::class);
        $registry->method('getManagerForClass')->willReturn($em);

        return new UtilisateurRepository($registry);
    }
}
