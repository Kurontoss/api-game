<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Enemy;
use App\Entity\Dungeon;
use App\Entity\LootPool;
use App\DataFixtures\DungeonFixtures;
use App\DataFixtures\LootPoolFixtures;

class EnemyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $cave = $this->getReference('dungeon_cave', Dungeon::class);
        $castle = $this->getReference('dungeon_castle', Dungeon::class);
        $catacombs = $this->getReference('dungeon_catacombs', Dungeon::class);


        $goblin = new Enemy();
        $goblin->setName('goblin');
        $goblin->setHp(3);
        $goblin->setStrength(1);
        $goblin->setExp(3);
        $goblin->setLootPool($this->getReference('loot_pool_goblin', LootPool::class));

        $skeleton = new Enemy();
        $skeleton->setName('skeleton');
        $skeleton->setHp(2);
        $skeleton->setStrength(2);
        $skeleton->setExp(3);
        $skeleton->setLootPool($this->getReference('loot_pool_skeleton', LootPool::class));

        $ogre = new Enemy();
        $ogre->setName('ogre');
        $ogre->setHp(10);
        $ogre->setStrength(2);
        $ogre->setExp(10);
        $ogre->setLootPool($this->getReference('loot_pool_ogre', LootPool::class));

        $demon = new Enemy();
        $demon->setName('demon');
        $demon->setHp(8);
        $demon->setStrength(5);
        $demon->setExp(15);
        $demon->setLootPool($this->getReference('loot_pool_demon', LootPool::class));

        $wizard = new Enemy();
        $wizard->setName('wizard');
        $wizard->setHp(16);
        $wizard->setStrength(7);
        $wizard->setExp(30);
        $wizard->setLootPool($this->getReference('loot_pool_wizard', LootPool::class));



        // CAVE

        $enemy1 = clone $goblin;
        $enemy1->setDungeon($cave);
        $manager->persist($enemy1);

        $enemy2 = clone $goblin;
        $enemy2->setDungeon($cave);
        $manager->persist($enemy2);

        $enemy3 = clone $skeleton;
        $enemy3->setDungeon($cave);
        $manager->persist($enemy3);

        $enemy4 = clone $skeleton;
        $enemy4->setDungeon($cave);
        $manager->persist($enemy4);


        // CASTLE

        $enemy5 = clone $skeleton;
        $enemy5->setDungeon($castle);
        $manager->persist($enemy5);

        $enemy6 = clone $skeleton;
        $enemy6->setDungeon($castle);
        $manager->persist($enemy6);

        $enemy7 = clone $ogre;
        $enemy7->setDungeon($castle);
        $manager->persist($enemy7);

        $enemy8 = clone $skeleton;
        $enemy8->setDungeon($castle);
        $manager->persist($enemy8);

        $enemy9 = clone $ogre;
        $enemy9->setDungeon($castle);
        $manager->persist($enemy9);

        $enemy10 = clone $demon;
        $enemy10->setDungeon($castle);
        $manager->persist($enemy10);


        // CATACOMBS

        $enemy11 = clone $goblin;
        $enemy11->setDungeon($catacombs);
        $manager->persist($enemy11);

        $enemy12 = clone $demon;
        $enemy12->setDungeon($catacombs);
        $manager->persist($enemy12);

        $enemy13 = clone $goblin;
        $enemy13->setDungeon($catacombs);
        $manager->persist($enemy13);
        
        $enemy14 = clone $goblin;
        $enemy14->setDungeon($catacombs);
        $manager->persist($enemy14);

        $enemy15 = clone $demon;
        $enemy15->setDungeon($catacombs);
        $manager->persist($enemy15);

        $enemy16 = clone $wizard;
        $enemy16->setDungeon($catacombs);
        $manager->persist($enemy16);


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DungeonFixtures::class,
            LootPoolFixtures::class,
        ];
    }
}
