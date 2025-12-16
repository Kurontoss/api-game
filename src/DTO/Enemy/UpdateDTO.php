<?php

namespace App\DTO\Enemy;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Dungeon;
use App\Entity\LootPool;
use App\Validator\Constraints as AppAssert;

class UpdateDTO
{
    #[Groups(['enemy:write'])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public $name;

    #[Groups(['enemy:write'])]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    public $hp;

    #[Groups(['enemy:write'])]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    public $strength;

    #[Groups(['enemy:write'])]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    public $exp;

    #[Groups(['enemy:write'])]
    #[Assert\Type('integer')]
    #[AppAssert\EntityExists(entityClass: Dungeon::class)]
    public $dungeonId;

    #[Groups(['enemy:write'])]
    #[Assert\Type('integer')]
    #[AppAssert\EntityExists(entityClass: LootPool::class)]
    public $lootPoolId;
}