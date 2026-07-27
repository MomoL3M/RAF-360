<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Professionnel;
use App\Entity\RendezVous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Accès aux rendez-vous — seul endroit où vit le DQL correspondant (§14.1).
 *
 * @extends ServiceEntityRepository<RendezVous>
 */
final class RendezVousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendezVous::class);
    }

    /**
     * Les rendez-vous d'un professionnel, du plus proche au plus lointain.
     *
     * @return RendezVous[]
     */
    public function findByProfessionnel(Professionnel $professionnel): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.professionnel = :pro')
            ->setParameter('pro', $professionnel)
            ->orderBy('r.creneau', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
