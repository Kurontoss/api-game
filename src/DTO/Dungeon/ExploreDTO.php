<?php

namespace App\DTO\Dungeon;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;

class ExploreDTO
{
    #[Groups(['dungeon:write'])]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[Assert\Positive]
    public $knightId;

    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[Assert\Positive]
    public $dungeonId;
}