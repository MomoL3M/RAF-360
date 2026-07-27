<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FluxTresorerie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Accès aux points de trésorerie — seul endroit où vit le DQL correspondant (§14.1).
 *
 * @extends ServiceEntityRepository<FluxTresorerie>
 */
final class FluxTresorerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FluxTresorerie::class);
    }

    /**
     * La série chronologique complète (du mois le plus ancien au plus récent).
     *
     * @return FluxTresorerie[]
     */
    public function findSerie(): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
