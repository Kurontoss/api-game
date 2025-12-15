<?php

namespace App\DTO\Enemy;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;
use App\Entity\Dungeon;
use App\Entity\LootPool;

class CreateDTO
{
    #[Groups(['enemy:write'])]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public $name;

    #[Groups(['enemy:write'])]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[Assert\Positive]
    public $hp;

    #[Groups(['enemy:write'])]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[Assert\Positive]
    public $strength;

    #[Groups(['enemy:write'])]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    public $exp;

    #[Groups(['enemy:write'])]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[AppAssert\EntityExists(entityClass: Dungeon::class)]
    public $dungeonId;

    #[Groups(['enemy:write'])]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[AppAssert\EntityExists(entityClass: LootPool::class)]
    public $lootPoolId;
}