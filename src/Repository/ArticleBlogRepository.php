<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ArticleBlog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Accès aux articles de blog — seul endroit où vit le DQL correspondant (§14.1).
 *
 * @extends ServiceEntityRepository<ArticleBlog>
 */
final class ArticleBlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleBlog::class);
    }

    /**
     * Les articles les plus récents d'abord.
     *
     * @return ArticleBlog[]
     */
    public function findRecents(int $limit = 10): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.datePublication', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /** Récupère un article par son slug (ou null s'il n'existe pas). */
    public function findParSlug(string $slug): ?ArticleBlog
    {
        return $this->findOneBy(['slug' => $slug]);
    }
}
