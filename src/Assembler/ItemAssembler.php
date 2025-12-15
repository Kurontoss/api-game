<?php

namespace App\Assembler;

use App\Entity\Item\Item;
use App\Entity\Item\Food;
use App\DTO\Item\CreateDTO;

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
}