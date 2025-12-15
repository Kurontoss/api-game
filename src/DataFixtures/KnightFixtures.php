<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use App\DataFixtures\UserFixtures;
use App\Entity\Knight;
use App\Entity\User;

class KnightFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $knight1 = new Knight();
        $knight1->setName('Lancelot');
        $knight1->setLevel(1);
        $knight1->setExp(0);
        $knight1->setExpToNextLevel(10);
        $knight1->setHp(10);
        $knight1->setMaxHp(10);
        $knight1->setUser($this->getReference('user_tom', User::class));
        $manager->persist($knight1);
        $this->addReference('knight_lancelot', $knight1);

        $knight2 = new Knight();
        $knight2->setName('Arthur');
        $knight2->setLevel(8);
        $knight2->setExp(0);
        $knight2->setExpToNextLevel(170);
        $knight2->setHp(20);
        $knight2->setMaxHp(50);
        $knight2->setUser($this->getReference('user_tom', User::class));
        $manager->persist($knight2);
        $this->addReference('knight_arthur', $knight2);

        $knight3 = new Knight();
        $knight3->setName('Billy');
        $knight3->setLevel(1);
        $knight3->setExp(0);
        $knight3->setExpToNextLevel(10);
        $knight3->setHp(10);
        $knight3->setMaxHp(10);
        $knight3->setUser($this->getReference('user_ben', User::class));
        $manager->persist($knight3);
        $this->addReference('knight_billy', $knight3);

        $knight4 = new Knight();
        $knight4->setName('Henry');
        $knight4->setLevel(8);
        $knight4->setExp(0);
        $knight4->setExpToNextLevel(170);
        $knight4->setHp(1);
        $knight4->setMaxHp(100);
        $knight4->setUser($this->getReference('user_ben', User::class));
        $manager->persist($knight4);
        $this->addReference('knight_henry', $knight4);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
