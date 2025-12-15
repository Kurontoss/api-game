<?php

namespace App\Assembler;

use App\Repository\Item\ItemRepository;
use App\Entity\LootPool;
use App\DTO\LootPool\CreateDTO;

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
}