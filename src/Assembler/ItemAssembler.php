<?php

namespace App\Assembler;

use App\DTO\Item\CreateDTO;
use App\DTO\Item\UpdateDTO;
use App\Entity\Item\Food;
use App\Entity\Item\Item;

class ItemAssembler
{
    public function fromCreateDTO(CreateDTO $dto): Item
    {
        if ($dto->type == 'food') {
            $item = new Food();
            $item->setHpRegen($dto->hpRegen);
        } else {
            $item = new Item();
        }

        $item->setName($dto->name);
        $item->setValue($dto->value);

        return $item;
    }

    public function fromUpdateDTO(UpdateDTO $dto, Item $item): Item
    {
        if ($dto->name) {
            $item->setName($dto->name);
        }

        if ($dto->value) {
            $item->setValue($dto->value);
        }

        if($dto->hpRegen && $item instanceof Food) {
            $item->setHpRegen($dto->hpRegen);
        }
        
        return $item;
    }
}