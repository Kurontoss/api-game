<?php

namespace App\Service\Item;

use App\Entity\Item\ItemInstance;
use App\Entity\Knight;
use App\Exception\ItemAmountTooLowException;
use App\Repository\Item\ItemInstanceRepository;

class EatService
{
    public function __construct(
        private ItemInstanceRepository $itemInstanceRepo,
    ) {}

    public function eat(
        Knight $knight,
        ItemInstance $item,
        int $amount,
    ): void {
        if ($amount > $item->getAmount()) {
            throw new ItemAmountTooLowException();
        }

        $healAmount = $item->getItem()->getHpRegen() * $amount;
        $knight->setHp(min($knight->getHp() + $healAmount, $knight->getMaxHp()));
        
        $item->setAmount($item->getAmount() - $amount);
        if ($item->getAmount() === 0) {
            $this->itemInstanceRepo->delete($item);
        }
    }
}