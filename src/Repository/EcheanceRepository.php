<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Echeance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Accès aux échéances — seul endroit où vit le QueryBuilder/DQL des échéances (§14.1).
 *
 * @extends ServiceEntityRepository<Echeance>
 */
final class EcheanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Echeance::class);
    }

    /**
     * Les prochaines échéances, de la plus proche à la plus lointaine
     * (règle de tri de l'inventaire R2 §C).
     *
     * @return Echeance[]
     */
    public function findProchaines(int $limit = 4): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.dateEcheance', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
