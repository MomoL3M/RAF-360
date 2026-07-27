<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AlerteEncaissement;
use App\Enum\StatutEncaissement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Accès aux alertes d'encaissement — seul endroit où vit le DQL correspondant (§14.1).
 *
 * @extends ServiceEntityRepository<AlerteEncaissement>
 */
final class AlerteEncaissementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlerteEncaissement::class);
    }

    /**
     * Les encaissements en retard (à relancer en priorité).
     *
     * @return AlerteEncaissement[]
     */
    public function findEnRetard(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.statut = :statut')
            ->setParameter('statut', StatutEncaissement::EN_RETARD)
            ->getQuery()
            ->getResult();
    }
}
