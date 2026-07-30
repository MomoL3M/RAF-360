<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entreprise;
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

    /**
     * Les rendez-vous d'une entreprise, du plus proche au plus lointain (§16.1).
     * Le professionnel est chargé en jointure (évite les requêtes N+1, §8.5).
     *
     * @return RendezVous[]
     */
    public function findForEntreprise(Entreprise $entreprise): array
    {
        return $this->createQueryBuilder('r')
            ->addSelect('p')
            ->join('r.professionnel', 'p')
            ->andWhere('r.entreprise = :ent')
            ->setParameter('ent', $entreprise)
            ->orderBy('r.creneau', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Supprime toutes les lignes rattachées à une entreprise.
     * Le DQL vit ici et nulle part ailleurs (§14.1) ; utilisé par le peuplement de démonstration.
     */
    public function deleteForEntreprise(Entreprise $entreprise): void
    {
        $this->createQueryBuilder('r')
            ->delete()
            ->andWhere('r.entreprise = :ent')
            ->setParameter('ent', $entreprise)
            ->getQuery()
            ->execute();
    }
}
