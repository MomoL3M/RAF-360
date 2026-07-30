<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entreprise;
use App\Entity\Facture;
use App\Enum\StatutFacture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Accès aux factures — seul endroit où vit le QueryBuilder/DQL des factures (§14.1).
 *
 * @extends ServiceEntityRepository<Facture>
 */
final class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }

    /**
     * Compte les factures par statut (alimente les KPIs du tableau de bord).
     *
     * @return array<string, int> clé = valeur du statut, valeur = nombre
     */
    public function countByStatut(): array
    {
        /** @var array<array{statut: StatutFacture, nb: int}> $rows */
        $rows = $this->createQueryBuilder('f')
            ->select('f.statut AS statut', 'COUNT(f.id) AS nb')
            ->groupBy('f.statut')
            ->getQuery()
            ->getResult();

        $counts = [];
        foreach ($rows as $row) {
            $counts[$row['statut']->value] = (int) $row['nb'];
        }

        return $counts;
    }

    /**
     * Toutes les factures d'une entreprise, émission la plus récente d'abord (§16.1).
     *
     * @return Facture[]
     */
    public function findForEntreprise(Entreprise $entreprise): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.entreprise = :ent')
            ->setParameter('ent', $entreprise)
            ->orderBy('f.dateEmission', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
