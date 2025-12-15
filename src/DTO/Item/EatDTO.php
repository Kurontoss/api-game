<?php

namespace App\DTO\Item;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;
use App\Entity\Knight;
use App\Entity\Item\InventoryItem;

class EatDTO
{
    #[Groups(['inventory_item:write'])]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[Assert\Positive]
    #[AppAssert\EntityExists(entityClass: Knight::class)]
    public $knightId;

    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[Assert\Positive]
    #[AppAssert\EntityExists(entityClass: InventoryItem::class)]
    public $inventoryItemId;

    #[Groups(['inventory_item:write'])]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[Assert\Positive]
    public $amount;
}