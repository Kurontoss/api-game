<?php

namespace App\Assembler;

use App\DTO\Dungeon\CreateDTO;
use App\Entity\Dungeon;

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