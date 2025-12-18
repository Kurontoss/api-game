<?php

namespace App\DTO\Enemy;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Dungeon;
use App\Entity\LootPool;

class UpdateDTO
{
    #[Groups(['enemy:write'])]
    #[OA\Property(type: 'string', maxLength: 255, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public $name;

    #[Groups(['enemy:write'])]
    #[OA\Property(type: 'integer', minimum: 1, nullable: true)]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    public $hp;

    #[Groups(['enemy:write'])]
    #[OA\Property(type: 'integer', minimum: 1, nullable: true)]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    public $strength;

    #[Groups(['enemy:write'])]
    #[OA\Property(type: 'integer', minimum: 0, nullable: true)]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    public $exp;

    #[Groups(['enemy:write'])]
    #[OA\Property(type: 'integer', minimum: 1, nullable: true)]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    public $dungeonId;

    #[Groups(['enemy:write'])]
    #[OA\Property(type: 'integer', minimum: 1, nullable: true)]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    public $lootPoolId;
}