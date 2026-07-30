<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AlerteEncaissement;
use App\Entity\Entreprise;
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

    /**
     * Les encaissements attendus d'une entreprise, montant décroissant (§16.1).
     *
     * @return AlerteEncaissement[]
     */
    public function findForEntreprise(Entreprise $entreprise): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.entreprise = :ent')
            ->setParameter('ent', $entreprise)
            ->orderBy('a.montantCentimes', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Supprime toutes les lignes rattachées à une entreprise.
     * Le DQL vit ici et nulle part ailleurs (§14.1) ; utilisé par le peuplement de démonstration.
     */
    public function deleteForEntreprise(Entreprise $entreprise): void
    {
        $this->createQueryBuilder('a')
            ->delete()
            ->andWhere('a.entreprise = :ent')
            ->setParameter('ent', $entreprise)
            ->getQuery()
            ->execute();
    }
}
