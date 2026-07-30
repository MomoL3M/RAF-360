<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Action;
use App\Entity\Entreprise;
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

    /**
     * Toutes les actions d'une entreprise, dans l'ordre de création (§16.1).
     *
     * @return Action[]
     */
    public function findForEntreprise(Entreprise $entreprise): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.entreprise = :ent')
            ->setParameter('ent', $entreprise)
            ->orderBy('a.id', 'ASC')
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
