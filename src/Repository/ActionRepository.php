<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Action;
use App\Enum\PrioriteAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Accès aux actions — seul endroit où vit le DQL correspondant (§14.1).
 *
 * @extends ServiceEntityRepository<Action>
 */
final class ActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Action::class);
    }

    /**
     * Les actions d'un niveau de priorité donné.
     *
     * @return Action[]
     */
    public function findByPriorite(PrioriteAction $priorite): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.priorite = :priorite')
            ->setParameter('priorite', $priorite)
            ->getQuery()
            ->getResult();
    }
}
