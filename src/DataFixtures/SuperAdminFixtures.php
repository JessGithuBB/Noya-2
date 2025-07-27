<?php

namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SuperAdminFixtures extends Fixture

{   
    public function __construct( private UserPasswordHasherInterface $hasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
       $superAdmin = $this->createSuperAdmin();

        $manager->persist($superAdmin);
        $manager->flush();
    }
    /**
     * Create a super admin user.
     *
     * @return User
     */
    private function createSuperAdmin(): User
    {
       $superAdmin = new User();

       $passwordHashed = $this->hasher->hashPassword($superAdmin, '1234azertyA*');

       $superAdmin->setFirstname('Super');
       $superAdmin->setLastname('Noya');
       $superAdmin->setEmail('contact.noyafr@gmail.com');
       $superAdmin->setRoles(['ROLE_SUPER_ADMIN','ROLE_ADMIN','ROLE_USER']);
       $superAdmin->isVerified(true);
       $superAdmin->setPassword($passwordHashed);
       $superAdmin->setCreatedAt(new \DateTimeImmutable());
       $superAdmin->setVerifiedAt(new \DateTimeImmutable());
       $superAdmin->setUpdatedAt(new \DateTimeImmutable());

       return $superAdmin;

    }
}
