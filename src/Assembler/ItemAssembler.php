<?php

namespace App\Assembler;

use App\DTO\Item\CreateDTO;
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
}