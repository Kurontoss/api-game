<?php

namespace App\Service;

use App\Entity\Item\ItemInstance;
use App\Entity\LootPool;
use App\Service\RandomNumberGeneratorService;

class LootService
{
    public function __construct(
        private RandomNumberGeneratorService $randomNumberGenerator,
    ) {}

    public function drop(
        LootPool $lootPool,
    ): ?ItemInstance {
        $chance = $this->randomNumberGenerator->generateFloat();
        $index = null;

        foreach ($lootPool->getItems() as $i => $item) {
            $chance -= $lootPool->getChances()[$i];
            if ($chance <= 0) {
                $index = $i;
                break;
            }
        }

        if ($index === null || $lootPool->getItems()[$index] === null) {
            return null;
        }

        $amount = $this->randomNumberGenerator->generateIntFromRange(
            $lootPool->getMinAmounts()[$index],
            $lootPool->getMaxAmounts()[$index]
        );

        $itemInstance = new ItemInstance();
        $itemInstance->setItem($lootPool->getItems()[$index]);
        $itemInstance->setAmount($amount);

        return $itemInstance;
    }
}