<?php

namespace App\Assembler;

use Doctrine\Common\Collections\ArrayCollection;

use App\DTO\LootPool\CreateDTO;
use App\DTO\LootPool\UpdateDTO;
use App\Entity\LootPool;
use App\Repository\Item\ItemRepository;

class LootPoolAssembler
{
    public function __construct(
        private ItemRepository $itemRepo,
    ) {}

    public function fromCreateDTO(CreateDTO $dto): LootPool
    {
        $lootPool = new LootPool();
        $lootPool->setName($dto->name);

        foreach($dto->items as $id) {
            $item = $this->itemRepo->find($id);
            if ($item) {
                $lootPool->addItem($item);
            }
        }

        $lootPool->setChances($dto->chances);
        $lootPool->setMinAmounts($dto->minAmounts);
        $lootPool->setMaxAmounts($dto->maxAmounts);

        return $lootPool;
    }

    public function fromUpdateDTO(UpdateDTO $dto, LootPool $lootPool): LootPool
    {
        if ($dto->name) {
            $lootPool->setName($dto->name);
        }

        if ($dto->items) {
            $lootPool->setItems(new ArrayCollection());

            foreach($dto->items as $id) {
                $item = $this->itemRepo->find($id);
                if ($item) {
                    $lootPool->addItem($item);
                }
            }
        }

        if ($dto->chances) {
            $lootPool->setChances($dto->chances);
        }
        
        if ($dto->minAmounts) {
            $lootPool->setMinAmounts($dto->minAmounts);
        }

        if ($dto->maxAmounts) {
            $lootPool->setMaxAmounts($dto->maxAmounts);
        }
        
        return $lootPool;
    }
}