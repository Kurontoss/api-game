<?php

namespace App\Service\Knight;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Service\Knight\LevelUpService;
use App\Entity\Knight;
use App\Entity\Dungeon;

class ExploreService
{
    public function __construct(
        private LevelUpService $levelUpService
    ) {}

    public function explore(Knight $knight, Dungeon $dungeon)
    {
        if ($knight->getLevel() < $dungeon->getLevel()) {
            throw new BadRequestHttpException('Your level is too low to enter this dungeon.');
        }

        $knight->setExp($knight->getExp() + $dungeon->getExp());
        $this->levelUpService->levelUp($knight);
    }
}