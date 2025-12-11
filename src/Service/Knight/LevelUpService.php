<?php

namespace App\Service\Knight;

use App\Entity\Knight;

class LevelUpService
{
    public function levelUp(
        Knight $knight
    ): void {
        if ($knight->getExp() >= $knight->getExpToNextLevel()) {
            $knight->setExp($knight->getExp() - $knight->getExpToNextLevel());
            $knight->setExpToNextLevel($knight->getExpToNextLevel() * 1.5);
            $knight->setLevel($knight->getLevel() + 1);

            $knight->setMaxHp($knight->getMaxHp() * 1.2);

            $this->levelUp($knight);
        }
    }
}