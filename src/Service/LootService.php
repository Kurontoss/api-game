<?php

namespace App\Service;

use App\Entity\LootPool;
use App\Repository\Item\InventoryItemRepository;
use App\Entity\Item\InventoryItem;

class LootService
{
    public function __construct(
        private InventoryItemRepository $inventoryItemRepo
    ) {}

    public function drop(LootPool $lootPool): ?InventoryItem
    {
        $chance = (double)rand(1, 10000) / 10000;

        for ($i = 0; $i < count($lootPool->getItems()); $i++) {
            $chance -= $lootPool->getChances()[$i];
            if ($chance <= 0) {
                break;
            }
        }

        if ($lootPool->getItems()[$i] === null) {
            return null;
        }

        $amount = rand($lootPool->getMinAmounts()[$i], $lootPool->getMaxAmounts()[$i]);

        $inventoryItem = new InventoryItem();
        $inventoryItem->setItem($lootPool->getItems()[$i]);
        $inventoryItem->setAmount($amount);

        return $inventoryItem;
    }
}