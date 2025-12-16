<?php

namespace App\Service\Item;

use App\Repository\Item\ItemInstanceRepository;

class MergeService
{
    public function __construct(
        private ItemInstanceRepository $itemInstanceRepo,
    ) {}

    public function merge(
        array $items,
        bool $clone = false,
    ): array {
        $mergedItems = [];

        foreach ($items as $item) {
            $merged = false;

            foreach ($mergedItems as $mergedItem) {
                if ($item->getItem() === $mergedItem->getItem()) {
                    $mergedItem->setAmount($mergedItem->getAmount() + $item->getAmount());
                    if (!$clone) {
                        $this->itemInstanceRepo->delete($item);
                    }

                    $merged = true;
                    break;
                }
            }

            if (!$merged) {
                $mergedItems[] = $clone ? clone $item : $item;
            }
        }

        return $mergedItems;
    }
}