<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entreprise;
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

    /**
     * La série chronologique d'une entreprise, du mois le plus ancien au plus récent (§16.1).
     *
     * @return FluxTresorerie[]
     */
    public function findSerieForEntreprise(Entreprise $entreprise): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.entreprise = :ent')
            ->setParameter('ent', $entreprise)
            ->orderBy('f.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
