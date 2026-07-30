<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Document;
use App\Entity\Entreprise;
use App\Enum\DomaineDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Accès aux documents — seul endroit où vit le QueryBuilder/DQL des documents (§14.1).
 *
 * @extends ServiceEntityRepository<Document>
 */
final class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    /**
     * Documents d'un domaine donné, les plus récents d'abord.
     *
     * @return Document[]
     */
    public function findByDomaine(DomaineDocument $domaine): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.domaine = :domaine')
            ->setParameter('domaine', $domaine)
            ->orderBy('d.dateDepot', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Tous les documents d'une entreprise, dépôt le plus récent d'abord (§16.1).
     *
     * @return Document[]
     */
    public function findForEntreprise(Entreprise $entreprise): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.entreprise = :ent')
            ->setParameter('ent', $entreprise)
            ->orderBy('d.dateDepot', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Supprime toutes les lignes rattachées à une entreprise.
     * Le DQL vit ici et nulle part ailleurs (§14.1) ; utilisé par le peuplement de démonstration.
     */
    public function deleteForEntreprise(Entreprise $entreprise): void
    {
        $this->createQueryBuilder('d')
            ->delete()
            ->andWhere('d.entreprise = :ent')
            ->setParameter('ent', $entreprise)
            ->getQuery()
            ->execute();
    }
}
