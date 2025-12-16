<?php

namespace App\Assembler;

use App\DTO\Enemy\CreateDTO;
use App\DTO\Enemy\UpdateDTO;
use App\Entity\Enemy;
use App\Repository\DungeonRepository;
use App\Repository\LootPoolRepository;

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

    public function fromUpdateDTO(UpdateDTO $dto, Enemy $enemy): Enemy
    {
        if ($dto->name) {
            $enemy->setName($dto->name);
        }
        
        if ($dto->hp) {
            $enemy->setHp($dto->hp);
        }
        
        if ($dto->strength) {
            $enemy->setStrength($dto->strength);
        }
        
        if ($dto->exp) {
            $enemy->setExp($dto->exp);
        }
        
        if ($dto->dungeonId) {
            $dungeon = $this->dungeonRepo->find($dto->dungeonId);
            $enemy->setDungeon($dungeon);
        }
        
        if ($dto->lootPoolId) {
            $lootPool = $this->lootPoolRepo->find($dto->lootPoolId);
            $enemy->setLootPool($lootPool);
        }

        return $enemy;
    }
}