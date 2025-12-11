<?php

namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('tom@wp.pl');
        $user1->setName('Tom');
        $user1->setRoles(['ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword($user1, '123');
        $user1->setPassword($hashedPassword);
        $manager->persist($user1);
        $this->addReference('user_tom', $user1);

        $user2 = new User();
        $user2->setEmail('ben@wp.pl');
        $user2->setName('Ben');
        $user2->setRoles(['ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword($user2, 'abc');
        $user2->setPassword($hashedPassword);
        $manager->persist($user2);
        $this->addReference('user_ben', $user2);

        $manager->flush();
    }
}
