<?php

namespace App\Service;

use App\Entity\DropPool;
use App\Repository\Item\InventoryItemRepository;
use App\Entity\Item\InventoryItem;

class DropService
{
    public function __construct(
        private InventoryItemRepository $inventoryItemRepo
    ) {}

    public function drop(DropPool $dropPool): ?InventoryItem
    {
        $chance = (double)rand(1, 10000) / 10000;

        for ($i = 0; $i < count($dropPool->getItems()); $i++) {
            $chance -= $dropPool->getChances()[$i];
            if ($chance <= 0) {
                break;
            }
        }

        if ($dropPool->getItems()[$i] === null) {
            return null;
        }

        $amount = rand($dropPool->getMinAmounts()[$i], $dropPool->getMaxAmounts()[$i]);

        $inventoryItem = new InventoryItem();
        $inventoryItem->setItem($dropPool->getItems()[$i]);
        $inventoryItem->setAmount($amount);

        $this->inventoryItemRepo->save($inventoryItem);

        return $inventoryItem;
    }
}