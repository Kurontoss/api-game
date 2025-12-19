<?php

namespace App\Service\Dungeon;

use Doctrine\Common\Collections\ArrayCollection;

use App\DTO\Battle\BattleSummaryDTO;
use App\DTO\Battle\FightDTO;
use App\Entity\Dungeon;
use App\Entity\Knight;
use App\Exception\LevelTooLowException;
use App\Service\Item\MergeService;
use App\Service\Knight\EnemyFightService;
use App\Service\Knight\LevelUpService;

class ExploreService
{
    public function __construct(
        private EnemyFightService $enemyFightService,
        private MergeService $mergeService,
        private LevelUpService $levelUpService,
    ) {}

    public function explore(
        Knight $knight,
        Dungeon $dungeon,
    ): BattleSummaryDTO {
        if ($knight->getLevel() < $dungeon->getLevel()) {
            throw new LevelTooLowException();
        }

        $battleStart = new FightDTO();
        $battleStart->index = 0;
        $battleStart->knight = clone $knight;
        $battleStart->exp = 0;
        $battleStart->item = null;

        $battleSummary = new BattleSummaryDTO();
        $battleSummary->fights = [$battleStart];
        $battleSummary->exp = 0;
        $battleSummary->items = [];

        $i = 1;

        foreach ($dungeon->getEnemies() as $enemy) {
            $fight = $this->enemyFightService->fight($knight, $enemy);

            $fight->index = $i++;
            $battleSummary->fights[] = $fight;

            if (!$fight->isWon) {
                break;
            }

            $battleSummary->exp += $fight->exp;
            $battleSummary->items[] = $fight->item;
        }

        if (isset($fight) && $fight->isWon) {
            $battleSummary->exp += $dungeon->getExp();
            $knight->setExp($knight->getExp() + $dungeon->getExp());
            $this->levelUpService->levelUp($knight);
        }

        $battleSummary->items = $this->mergeService->merge($battleSummary->items, true);

        $inventory = $knight->getInventory()->toArray();
        $mergedInventory = $this->mergeService->merge($inventory ?: []);
        $knight->setInventory(new ArrayCollection($mergedInventory));

        return $battleSummary;
    }
}