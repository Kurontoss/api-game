<?php

namespace App\Service\Knight;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Service\Knight\LevelUpService;
use App\Service\Knight\EnemyFightService;
use App\Entity\Knight;
use App\Entity\Dungeon;

class ExploreService
{
    public function __construct(
        private LevelUpService $levelUpService,
        private EnemyFightService $enemyFightService
    ) {}

    public function explore(Knight $knight, Dungeon $dungeon)
    {
        if ($knight->getLevel() < $dungeon->getLevel()) {
            throw new BadRequestHttpException('Your level is too low to enter this dungeon.');
        }

        $battle = [[
            'round' => 0,
            'knight' => clone $knight,
        ]];
        $i = 1;

        foreach ($dungeon->getEnemies() as $enemy) {
            $outcome = $this->enemyFightService->fight($knight, $enemy);

            $battle[] = [
                'round' => $i++,
                'enemy' => $enemy,
                'knight' => clone $knight,
            ];

            if (!$outcome) {
                return $battle;
            }
        }

        $knight->setExp($knight->getExp() + $dungeon->getExp());
        $this->levelUpService->levelUp($knight);

        return $battle;
    }
}