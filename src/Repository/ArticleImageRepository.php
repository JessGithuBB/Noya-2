<?php

namespace App\Repository;

use App\Entity\ArticleImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArticleImage>
 *
 * @method ArticleImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleImage[]    findAll()
 * @method ArticleImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleImage::class);
    }

    // Exemple de méthode personnalisée si besoin
    public function findByArticleId(int $articleId): array
    {
        return $this->createQueryBuilder('ai')
            ->andWhere('ai.article = :articleId')
            ->setParameter('articleId', $articleId)
            ->orderBy('ai.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
