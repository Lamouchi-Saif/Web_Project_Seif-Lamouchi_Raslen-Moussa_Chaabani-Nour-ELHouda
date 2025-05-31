<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    // Inject password hasher (optional, recommended for real password hashing)
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('john_batata');
        $user->setEmail('john@example.com');
        $user->setPhone('123456789');
        $user->setAddress('123 Main St');
        
        // If you use a password hasher service:
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
        $user->setPasswordHash($hashedPassword);
        
        

        $user->setIsAdmin(false);
        $now = new \DateTimeImmutable();
        $user->setCreatedAt($now);
        $user->setUpdatedAt($now);

        $manager->persist($user);
        
        // You can add more users here...
        $user = new User();
        $user->setUsername('john_doe');
        $user->setEmail('john@example.com');
        $user->setPhone('123456789');
        $user->setAddress('123 Main St');
        
        // If you use a password hasher service:
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
        $user->setPasswordHash($hashedPassword);
        
        

        $user->setIsAdmin(false);
        $now = new \DateTimeImmutable();
        $user->setCreatedAt($now);
        $user->setUpdatedAt($now);

        $manager->persist($user);
        
        // You can add more users here...
        $manager->flush();
    }
}
