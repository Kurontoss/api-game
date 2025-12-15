<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use App\DataFixtures\ItemFixtures;
use App\Entity\Item\Food;
use App\Entity\Item\Item;
use App\Entity\LootPool;

class LootPoolFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $apple = $this->getReference('item_apple', Food::class);
        $bread = $this->getReference('item_bread', Food::class);
        $steak = $this->getReference('item_steak', Food::class);
        $healingPotion = $this->getReference('item_healing_potion', Food::class);
        $gem = $this->getReference('item_gem', Item::class);

        $lootPool1 = new LootPool();
        $lootPool1->setName('goblin');
        $lootPool1->setItems(new ArrayCollection([$apple, $bread, $gem]));
        $lootPool1->setChances([0.8, 0.19, 0.01]);
        $lootPool1->setMinAmounts([1, 1, 1]);
        $lootPool1->setMaxAmounts([2, 1, 1]);
        $manager->persist($lootPool1);
        $this->addReference('loot_pool_goblin', $lootPool1);

        $lootPool2 = new LootPool();
        $lootPool2->setName('skeleton');
        $lootPool2->setItems(new ArrayCollection([$apple, $bread]));
        $lootPool2->setChances([0.7, 0.3]);
        $lootPool2->setMinAmounts([1, 1]);
        $lootPool2->setMaxAmounts([2, 1]);
        $manager->persist($lootPool2);
        $this->addReference('loot_pool_skeleton', $lootPool2);

        $lootPool3 = new LootPool();
        $lootPool3->setName('ogre');
        $lootPool3->setItems(new ArrayCollection([$apple, $steak]));
        $lootPool3->setChances([0.5, 0.5]);
        $lootPool3->setMinAmounts([3, 1]);
        $lootPool3->setMaxAmounts([5, 1]);
        $manager->persist($lootPool3);
        $this->addReference('loot_pool_ogre', $lootPool3);

        $lootPool4 = new LootPool();
        $lootPool4->setName('demon');
        $lootPool4->setItems(new ArrayCollection([$steak, $gem]));
        $lootPool4->setChances([0.9, 0.1]);
        $lootPool4->setMinAmounts([1, 1]);
        $lootPool4->setMaxAmounts([4, 1]);
        $manager->persist($lootPool4);
        $this->addReference('loot_pool_demon', $lootPool4);

        $lootPool5 = new LootPool();
        $lootPool5->setName('wizard');
        $lootPool5->setItems(new ArrayCollection([$healingPotion, $gem]));
        $lootPool5->setChances([0.7, 0.3]);
        $lootPool5->setMinAmounts([1, 1]);
        $lootPool5->setMaxAmounts([2, 1]);
        $manager->persist($lootPool5);
        $this->addReference('loot_pool_wizard', $lootPool5);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ItemFixtures::class,
        ];
    }
}
