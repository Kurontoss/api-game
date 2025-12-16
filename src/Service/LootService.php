<?php

namespace App\Service;

use App\Entity\Item\ItemInstance;
use App\Entity\LootPool;
use App\Repository\Item\ItemInstanceRepository;

class LootService
{
    public function __construct(
        private ItemInstanceRepository $itemInstanceRepo,
    ) {}

    public function drop(
        LootPool $lootPool,
    ): ?ItemInstance {
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

        $itemInstance = new ItemInstance();
        $itemInstance->setItem($lootPool->getItems()[$i]);
        $itemInstance->setAmount($amount);

        return $itemInstance;
    }
}