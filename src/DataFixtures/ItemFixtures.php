<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Item\Item;
use App\Entity\Item\Food;

class ItemFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $item1 = new Food();
        $item1->setName('apple');
        $item1->setValue(3);
        $item1->setHpRegen(2);
        $manager->persist($item1);
        $this->addReference('item_apple', $item1);

        $item2 = new Food();
        $item2->setName('bread');
        $item2->setValue(6);
        $item2->setHpRegen(4);
        $manager->persist($item2);
        $this->addReference('item_bread', $item2);

        $item3 = new Food();
        $item3->setName('steak');
        $item3->setValue(18);
        $item3->setHpRegen(10);
        $manager->persist($item3);
        $this->addReference('item_steak', $item3);

        $item4 = new Food();
        $item4->setName('health potion');
        $item4->setValue(32);
        $item4->setHpRegen(20);
        $manager->persist($item4);
        $this->addReference('item_healing_potion', $item4);

        $item5 = new Item();
        $item5->setName('gem');
        $item5->setValue(100);
        $manager->persist($item5);
        $this->addReference('item_gem', $item5);

        $manager->flush();
    }
}
