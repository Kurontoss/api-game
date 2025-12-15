<?php

namespace App\Service\Item;

use App\Entity\Item\InventoryItem;
use App\Entity\Knight;
use App\Exception\ItemAmountTooLowException;
use App\Repository\Item\InventoryItemRepository;

class EatService
{
    public function __construct(
        private InventoryItemRepository $inventoryItemRepo,
    ) {}

    public function eat(
        Knight $knight,
        InventoryItem $item,
        int $amount,
    ): void {
        if ($amount > $item->getAmount()) {
            throw new ItemAmountTooLowException();
        }

        $healAmount = $item->getItem()->getHpRegen() * $amount;
        $knight->setHp(min($knight->getHp() + $healAmount, $knight->getMaxHp()));
        
        $item->setAmount($item->getAmount() - $amount);
        if ($item->getAmount() === 0) {
            $this->inventoryItemRepo->delete($item);
        }
    }
}