<?php

namespace App\Service\Knight;

use App\Entity\Knight;
use App\Entity\Enemy;

class EnemyFightService
{
    public function fight(
        Knight $knight,
        Enemy $enemy
    ): ?int {
        $rounds = (int)ceil($enemy->getHp() / $knight->getLevel()) - 1;
        $knight->setHp($knight->getHp() - $enemy->getStrength() * $rounds);

        if ($knight->getHp() <= 0) {
            $knight->setHp(1);
            return null;
        }

        return $enemy->getExp();
    }
}