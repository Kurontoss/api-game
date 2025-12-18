<?php

namespace App\DTO\LootPool;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Item\Item;
use App\Validator\Constraints as AppAssert;

class UpdateDTO
{
    #[Groups(['loot_pool:write'])]
    #[OA\Property(type: 'string', maxLength: 255, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public $name;

    #[Groups(['loot_pool:write'])]
    #[OA\Property(
        type: 'array',
        minItems: 1,
        nullable: true,
        items: new OA\Items(
            type: 'integer',
            example: 42
        )
    )]
    #[Assert\Type('array')]
    #[Assert\Count(min: 1)]
    #[Assert\All([
        new Assert\Type('integer'),
    ])]
    public $items;
    
    #[Groups(['loot_pool:write'])]
    #[OA\Property(
        type: 'array',
        minItems: 1,
        nullable: true,
        items: new OA\Items(
            type: 'number',
            format: 'float',
            exclusiveMinimum: true,
            minimum: 0,
            maximum: 1,
            example: 0.25
        )
    )]
    #[Assert\Type('array')]
    #[Assert\Count(min: 1)]
    #[Assert\All([
        new Assert\Type('float'),
        new Assert\GreaterThan(value: 0),
        new Assert\LessThanOrEqual(value: 1),
    ])]
    public $chances;

    #[Groups(['loot_pool:write'])]
    #[OA\Property(
        type: 'array',
        minItems: 1,
        nullable: true,
        items: new OA\Items(
            type: 'integer',
            minimum: 1
        )
    )]
    #[Assert\Type('array')]
    #[Assert\Count(min: 1)]
    #[Assert\All([
        new Assert\Type('integer'),
        new Assert\Positive,
    ])]
    public $minAmounts;

    #[Groups(['loot_pool:write'])]
    #[OA\Property(
        type: 'array',
        nullable: true,
        minItems: 1,
        items: new OA\Items(
            type: 'integer',
            minimum: 1
        )
    )]
    #[Assert\Type('array')]
    #[Assert\Count(min: 1)]
    #[Assert\All([
        new Assert\Type('integer'),
        new Assert\Positive,
    ])]
    public $maxAmounts;
}