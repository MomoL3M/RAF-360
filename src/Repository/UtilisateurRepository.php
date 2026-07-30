<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * Accès aux utilisateurs — seul endroit où vit le DQL correspondant (§14.1).
 *
 * @extends ServiceEntityRepository<Utilisateur>
 */
final class UtilisateurRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    /** Réencode le mot de passe si l'algorithme a évolué (rehash transparent). */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Utilisateur) {
            throw new UnsupportedUserException(\sprintf('Instances de "%s" non prises en charge.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /** Récupère un utilisateur par son e-mail (ou null). */
    public function findParEmail(string $email): ?Utilisateur
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * Nombre de comptes rattachés à une entreprise. Sert à décider si la suppression
     * d'un compte doit emporter les données de l'entreprise ou les laisser à ses collègues.
     */
    public function compterPourEntreprise(Entreprise $entreprise): int
    {
        return (int) $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->andWhere('u.entreprise = :ent')
            ->setParameter('ent', $entreprise)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Comptes dont la dernière activité (connexion, à défaut création) est antérieure à
     * la limite et qui ne sont pas déjà anonymisés — support de la purge (§17.1).
     *
     * @return Utilisateur[]
     */
    public function findInactifsAvant(\DateTimeImmutable $limite): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.anonymiseLe IS NULL')
            ->andWhere('COALESCE(u.derniereConnexion, u.creeLe) < :limite')
            ->setParameter('limite', $limite)
            ->getQuery()
            ->getResult();
    }
}
