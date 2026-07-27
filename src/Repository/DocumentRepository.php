<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Document;
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
}
