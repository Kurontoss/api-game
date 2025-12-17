<?php

namespace App\DTO\Enemy;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Dungeon;
use App\Entity\LootPool;

class CreateDTO
{
    #[Groups(['enemy:write'])]
    #[OA\Property(type: 'string', maxLength: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public $name;

    #[Groups(['enemy:write'])]
    #[OA\Property(type: 'integer', minimum: 1)]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[Assert\Positive]
    public $hp;

    #[Groups(['enemy:write'])]
    #[OA\Property(type: 'integer', minimum: 1)]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[Assert\Positive]
    public $strength;

    #[Groups(['enemy:write'])]
    #[OA\Property(type: 'integer', minimum: 0)]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    public $exp;

    #[Groups(['enemy:write'])]
    #[OA\Property(type: 'integer', minimum: 1)]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[Assert\Positive]
    public $dungeonId;

    #[Groups(['enemy:write'])]
    #[OA\Property(type: 'integer', minimum: 1)]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[Assert\Positive]
    public $lootPoolId;
}