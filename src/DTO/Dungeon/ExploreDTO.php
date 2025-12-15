<?php

namespace App\DTO\Dungeon;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Dungeon;
use App\Entity\Knight;
use App\Validator\Constraints as AppAssert;

class ExploreDTO
{
    #[Groups(['dungeon:write'])]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[AppAssert\EntityExists(entityClass: Knight::class)]
    public $knightId;

    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[AppAssert\EntityExists(entityClass: Dungeon::class)]
    public $dungeonId;
}