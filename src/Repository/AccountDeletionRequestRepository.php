<?php

namespace App\Repository;

use App\Entity\AccountDeletionRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AccountDeletionRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountDeletionRequest::class);
    }

    /**
     * Récupère toutes les demandes de suppression expirées (scheduled_at <= now et non annulées)
     *
     * @param \DateTimeInterface $now
     * @return AccountDeletionRequest[]
     */
    public function findExpiredRequests(\DateTimeInterface $now): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.scheduledAt <= :now')
            ->andWhere('r.cancelledAt IS NULL')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifie si un utilisateur a déjà une demande active
     */
    public function hasActiveRequest(int $userId): bool
    {
        $count = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.user = :uid')
            ->andWhere('r.cancelledAt IS NULL')
            ->setParameter('uid', $userId)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }
}
