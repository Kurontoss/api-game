<?php

namespace App\DTO\LootPool;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
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
class CreateDTO
{
    #[Groups(['loot_pool:write'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public ?string $name = null;

    #[Groups(['loot_pool:write'])]
    #[Assert\Count(min: 1)]
    #[Assert\All([new Assert\Type(Item::class)])]
    public array $items = [];
    
    #[Groups(['loot_pool:write'])]
    #[Assert\Count(min: 1)]
    #[Assert\All([
        new Assert\Type('float'),
        new Assert\GreaterThan(value: 0),
        new Assert\LessThanOrEqual(value: 1),
    ])]
    public array $chances = [];

    #[Groups(['loot_pool:write'])]
    #[Assert\Count(min: 1)]
    #[Assert\All([
        new Assert\Type('integer'),
        new Assert\Positive,
    ])]
    public array $minAmounts = [];

    #[Groups(['loot_pool:write'])]
    #[Assert\Count(min: 1)]
    #[Assert\All([
        new Assert\Type('integer'),
        new Assert\Positive,
    ])]
    public array $maxAmounts = [];
}