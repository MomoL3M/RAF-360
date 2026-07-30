<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Professionnel;
use App\Enum\DomaineProfessionnel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Accès aux professionnels — seul endroit où vit le DQL correspondant (§14.1).
 *
 * @extends ServiceEntityRepository<Professionnel>
 */
final class ProfessionnelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Professionnel::class);
    }

    /**
     * Les professionnels d'un domaine d'expertise, triés par nom.
     *
     * @return Professionnel[]
     */
    public function findByDomaine(DomaineProfessionnel $domaine): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.domaine = :domaine')
            ->setParameter('domaine', $domaine)
            ->orderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * L'annuaire complet, groupable par domaine. C'est un CATALOGUE de plateforme
     * (partagé, non cloisonné par entreprise) : le réseau n'appartient à personne.
     *
     * @return Professionnel[]
     */
    public function findCatalogue(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.domaine', 'ASC')
            ->addOrderBy('p.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
