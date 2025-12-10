<?php

namespace App\Service\Item;

use App\Repository\Item\InventoryItemRepository;

class MergeService
{
    public function __construct(
        private InventoryItemRepository $inventoryItemRepo
    ) {}

    public function merge(array $items): array
    {
        $mergedItems = [];

        foreach ($items as $item) {
            $merged = false;

            foreach ($mergedItems as $mergedItem) {
                if ($item->getItem() === $mergedItem->getItem()) {
                    $mergedItem->setAmount($mergedItem->getAmount() + $item->getAmount());
                    $this->inventoryItemRepo->delete($item);

                    $merged = true;
                    break;
                }
            }

            if (!$merged) {
                $mergedItems[] = $item;
            }
        }

        return $mergedItems;
    }
}