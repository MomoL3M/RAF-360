<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Echeance;
use App\Entity\Entreprise;
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

    /**
     * Toutes les échéances d'une entreprise, de la plus proche à la plus lointaine.
     * Cloisonnement par tenant (§16.1) : un utilisateur ne voit que SES données.
     *
     * @return Echeance[]
     */
    public function findForEntreprise(Entreprise $entreprise): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.entreprise = :ent')
            ->setParameter('ent', $entreprise)
            ->orderBy('e.dateEcheance', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Supprime toutes les lignes rattachées à une entreprise.
     * Le DQL vit ici et nulle part ailleurs (§14.1) ; utilisé par le peuplement de démonstration.
     */
    public function deleteForEntreprise(Entreprise $entreprise): void
    {
        $this->createQueryBuilder('e')
            ->delete()
            ->andWhere('e.entreprise = :ent')
            ->setParameter('ent', $entreprise)
            ->getQuery()
            ->execute();
    }
}
