<?php

namespace App\Assembler;

use App\DTO\Dungeon\CreateDTO;
use App\DTO\Dungeon\UpdateDTO;
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

    public function fromUpdateDTO(UpdateDTO $dto, Dungeon $dungeon): Dungeon
    {
        if ($dto->name) {
            $dungeon->setName($dto->name);
        }

        if ($dto->level) {
            $dungeon->setLevel($dto->level);
        }
        
        if ($dto->exp) {
            $dungeon->setExp($dto->exp);
        }

        return $dungeon;
    }
}