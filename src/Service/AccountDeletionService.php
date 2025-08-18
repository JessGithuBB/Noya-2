<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\DeletionAudit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AccountDeletionService
{
    private EntityManagerInterface $em;
    private string $projectDir;
    private string $appSecret;

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $params, string $appSecret)
    {
        $this->em = $em;
        $this->projectDir = $projectDir;
        $this->appSecret = $appSecret;
    }

    public function anonymiseUser(User $user): void
    {
        $hash = hash('sha256', $user->getEmail() . $this->appSecret);

        if ($user->getAvatar()) {
            $fs = new Filesystem();
            $path = $this->projectDir . '/public/uploads/' . $user->getAvatar();
            if ($fs->exists($path)) {
                $fs->remove($path);
            }
        }

        $user->setFirstName('ANON')
             ->setLastName(substr($hash, 0, 6))
             ->setEmail('anon-' . substr($hash, 0, 8) . '@deleted.local')
             ->setPhoneNumber(null)
             ->setPassword(bin2hex(random_bytes(16)))
             ->setRoles([])
             ->setIsVerified(false)
             ->setUpdatedAt(new \DateTimeImmutable())
             ->setPendingDeletionAt(null);

        foreach ($user->getAddresses() as $address) {
            $this->em->remove($address);
        }

        $this->em->getConnection()->executeStatement("
            UPDATE orders 
            SET user_id = NULL, 
                user_hash = :hash, 
                billing_name = 'ANON', 
                billing_email = CONCAT('anon-', SUBSTRING(:hash, 1, 8), '@deleted.local'), 
                billing_address = NULL 
            WHERE user_id = :uid
        ", ['hash' => $hash, 'uid' => $user->getId()]);

        $audit = new DeletionAudit($hash);
        $this->em->persist($audit);

        $this->em->remove($user);
        $this->em->flush();
    }
}
