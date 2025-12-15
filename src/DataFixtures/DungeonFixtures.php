<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Dungeon;

class DungeonFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $dungeon1 = new Dungeon();
        $dungeon1->setName('cave');
        $dungeon1->setLevel(1);
        $dungeon1->setExp(5);
        $manager->persist($dungeon1);
        $this->addReference('dungeon_cave', $dungeon1);

        $dungeon2 = new Dungeon();
        $dungeon2->setName('castle');
        $dungeon2->setLevel(5);
        $dungeon2->setExp(20);
        $manager->persist($dungeon2);
        $this->addReference('dungeon_castle', $dungeon2);

        $dungeon3 = new Dungeon();
        $dungeon3->setName('catacombs');
        $dungeon3->setLevel(8);
        $dungeon3->setExp(50);
        $manager->persist($dungeon3);
        $this->addReference('dungeon_catacombs', $dungeon3);

        $manager->flush();
    }
}
