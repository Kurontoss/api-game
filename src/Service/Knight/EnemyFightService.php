<?php

namespace App\Service\Knight;

use App\Service\LootService;
use App\Entity\Knight;
use App\Entity\Enemy;
use App\DTO\Knight\FightDTO;

class EnemyFightService
{
    public function __construct(
        private LootService $lootService
    ) {}

    public function fight(
        Knight $knight,
        Enemy $enemy
    ): ?FightDTO {
        $rounds = (int)ceil($enemy->getHp() / $knight->getLevel()) - 1;
        $knight->setHp($knight->getHp() - $enemy->getStrength() * $rounds);

        $fight = new FightDTO();
        $fight->enemy =$enemy;

        if ($knight->getHp() <= 0) {
            $knight->setHp(1);
            $fight->knight = clone $knight;
            $fight->isWon = false;
            return $fight;
        }

        $fight->exp = $enemy->getExp();
        $knight->setExp($knight->getExp() + $enemy->getExp());
        $fight->knight = clone $knight;

        if ($enemy->getLootPool()) {
            $item = $this->lootService->drop($enemy->getLootPool());
            if ($item !== null) {
                $item->setKnight($knight);
            }
        }
        $fight->item = isset($item) ? $item : null;
        
        $fight->isWon = true;

        return $fight;
    }
}