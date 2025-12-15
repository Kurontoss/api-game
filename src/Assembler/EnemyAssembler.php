<?php

namespace App\Assembler;

use App\Repository\DungeonRepository;
use App\Repository\LootPoolRepository;
use App\Entity\Enemy;
use App\DTO\Enemy\CreateDTO;

class EnemyAssembler
{
    public function __construct(
        private DungeonRepository $dungeonRepo,
        private LootPoolRepository $lootPoolRepo,
    ) {}

    public function fromCreateDTO(CreateDTO $dto): Enemy
    {
        $dungeon = $this->dungeonRepo->find($dto->dungeonId);
        $lootPool = $this->lootPoolRepo->find($dto->lootPoolId);

        $enemy = new Enemy();
        $enemy->setName($dto->name);
        $enemy->setHp($dto->hp);
        $enemy->setStrength($dto->strength);
        $enemy->setExp($dto->exp);
        $enemy->setDungeon($dungeon);
        $enemy->setLootPool($lootPool);

        return $enemy;
    }
}