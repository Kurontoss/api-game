<?php

namespace App\Service\Dungeon;

use App\Service\Knight\LevelUpService;
use App\Service\Knight\EnemyFightService;
use App\Entity\Knight;
use App\Entity\Dungeon;
use App\DTO\Knight\BattleDTO;
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

        $battleStart = new BattleDTO();
        $battleStart->round = 0;
        $battleStart->knight = clone $knight;

        $battleSummary = new BattleSummaryDTO();
        $battleSummary->battle = [$battleStart];
        $battleSummary->expGained = 0;

        $i = 1;

        foreach ($dungeon->getEnemies() as $enemy) {
            $outcome = $this->enemyFightService->fight($knight, $enemy);

            $battleRound = new BattleDTO();
            $battleRound->round = $i++;
            $battleRound->enemy = $enemy;
            $battleRound->knight = $knight;
            $battleSummary->battle[] = $battleRound;

            if (!$outcome) {
                return $battleSummary;
            }

            $battleSummary->expGained += $outcome;
        }

        $battleSummary->expGained += $dungeon->getExp();

        return $battleSummary;
    }
}