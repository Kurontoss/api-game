<?php

namespace App\Assembler;

use App\Entity\Dungeon;
use App\DTO\Dungeon\CreateDTO;

class DungeonAssembler
{
    public function fromCreateDTO(CreateDTO $dto): Dungeon
    {
        $dungeon = new Dungeon();
        $dungeon->setName($dto->name);
        $dungeon->setLevel($dto->level);
        $dungeon->setExp($dto->exp);

        return $dungeon;
    }
}