<?php

namespace App\Repository;

use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Articles>
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    /**
     * Retourne les articles liés aux catégories données.
     *
     * @param int[] $categoryIds
     * @return Articles[]
     */
    public function findByCategories(array $categoryIds): array
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.categories', 'c')
            ->addSelect('c'); // utile si tu veux accéder aux catégories ensuite

        if (!empty($categoryIds)) {
            $qb->andWhere('c.id IN (:ids)')
               ->setParameter('ids', $categoryIds);
        }

        return $qb->getQuery()->getResult();
    }
}
