<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Accès aux entreprises — seul endroit où vit le DQL correspondant (§14.1).
 *
 * @extends ServiceEntityRepository<Entreprise>
 */
final class EntrepriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entreprise::class);
    }

    /** Récupère une entreprise par son SIREN (ou null). */
    public function findParSiren(string $siren): ?Entreprise
    {
        return $this->findOneBy(['siren' => $siren]);
    }
}
