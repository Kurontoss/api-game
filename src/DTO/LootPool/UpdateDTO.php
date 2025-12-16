<?php

namespace App\DTO\LootPool;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Item\Item;
use App\Validator\Constraints as AppAssert;

#[AppAssert\MinLessThanMax(
    minField: 'minAmounts',
    maxField: 'maxAmounts'
)]
#[AppAssert\ArraysEqualLength(
    arrayFields: [
        'items',
        'chances',
        'minAmounts',
        'maxAmounts',
    ]
)]
class UpdateDTO
{
    #[Groups(['loot_pool:write'])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public $name;

    #[Groups(['loot_pool:write'])]
    #[Assert\Type('array')]
    #[Assert\Count(min: 1)]
    #[Assert\All([
        new Assert\Type('integer'),
        new AppAssert\EntityExists(entityClass: Item::class),
    ])]
    public $items;
    
    #[Groups(['loot_pool:write'])]
    #[Assert\Type('array')]
    #[Assert\Count(min: 1)]
    #[AppAssert\AddToOne]
    #[Assert\All([
        new Assert\Type('float'),
        new Assert\GreaterThan(value: 0),
        new Assert\LessThanOrEqual(value: 1),
    ])]
    public $chances;

    #[Groups(['loot_pool:write'])]
    #[Assert\Type('array')]
    #[Assert\Count(min: 1)]
    #[Assert\All([
        new Assert\Type('integer'),
        new Assert\Positive,
    ])]
    public $minAmounts;

    #[Groups(['loot_pool:write'])]
    #[Assert\Type('array')]
    #[Assert\Count(min: 1)]
    #[Assert\All([
        new Assert\Type('integer'),
        new Assert\Positive,
    ])]
    public $maxAmounts;
}