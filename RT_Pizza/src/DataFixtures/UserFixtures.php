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
        
        $manager->flush();
        // Optionally, you can create an admin user
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@gmail.com');
        $admin->setPhone('987654321');
        $admin->setAddress('456 Admin St');
        $admin->setPasswordHash($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setIsAdmin(true);
        $admin->setCreatedAt($now);
        $admin->setUpdatedAt($now);
        $manager->persist($admin);
        $manager->flush();
        // Optionally, you can create more users
    }
}
