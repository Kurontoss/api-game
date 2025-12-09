<?php

namespace App\Service\Dungeon;

use App\Service\Knight\LevelUpService;
use App\Service\Knight\EnemyFightService;
use App\Entity\Knight;
use App\Entity\Dungeon;
use App\DTO\Knight\FightDTO;
use App\DTO\Knight\BattleSummaryDTO;
use App\Exception\LevelTooLowException;

class ExploreService
{
    public function __construct(
        private EnemyFightService $enemyFightService
    ) {}

    public function explore(
        Knight $knight,
        Dungeon $dungeon
    ): BattleSummaryDTO {
        if ($knight->getLevel() < $dungeon->getLevel()) {
            throw new LevelTooLowException();
        }

        $battleStart = new FightDTO();
        $battleStart->round = 0;
        $battleStart->knight = clone $knight;
        $battleStart->exp = 0;
        $battleStart->items = [];

        $battleSummary = new BattleSummaryDTO();
        $battleSummary->fights = [$battleStart];
        $battleSummary->exp = 0;
        $battleSummary->items = [];

        $i = 1;

        foreach ($dungeon->getEnemies() as $enemy) {
            $fight = $this->enemyFightService->fight($knight, $enemy);

            $fight->round = $i;
            $battleSummary->fights[] = $fight;

            if (!$fight->isWon) {
                return $battleSummary;
            }

            $battleSummary->exp += $fight->exp;
            $battleSummary->items[] = $fight->item;
        }

        $battleSummary->exp += $dungeon->getExp();
        $knight->setExp($knight->getExp() + $dungeon->getExp());

        return $battleSummary;
    }
}