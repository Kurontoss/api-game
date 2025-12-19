<?php

namespace App\Service\Knight;

use App\Entity\Knight;

class LevelUpService
{
    public function levelUp(
        Knight $knight,
    ): void {
        if ($knight->getExp() >= $knight->getExpToNextLevel()) {
            $knight->setExp($knight->getExp() - $knight->getExpToNextLevel());
            $knight->setExpToNextLevel((int)floor($knight->getExpToNextLevel() * 1.5));
            $knight->setLevel($knight->getLevel() + 1);

            $knight->setMaxHp((int)floor($knight->getMaxHp() * 1.2));

            $this->levelUp($knight);
        }
    }
}