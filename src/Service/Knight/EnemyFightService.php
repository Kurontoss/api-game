<?php

namespace App\Service\Knight;

use App\Service\Knight\LevelUpService;
use App\Entity\Knight;
use App\Entity\Enemy;

class EnemyFightService
{
    public function __construct(
        private LevelUpService $levelUpService
    ) {}

    public function fight(
        Knight $knight,
        Enemy $enemy
    ) {
        $rounds = (int)ceil($enemy->getHp() / $knight->getLevel()) - 1;
        $knight->setHp($knight->getHp() - $enemy->getStrength() * $rounds);

        if ($knight->getHp() <= 0) {
            $knight->setHp(1);
            return false;
        }

        $knight->setExp($knight->getExp() + $enemy->getExp());
        $this->levelUpService->levelUp($knight);

        return true;
    }
}