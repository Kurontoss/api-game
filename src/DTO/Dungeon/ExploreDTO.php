<?php

namespace App\DTO\Dungeon;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Dungeon;
use App\Entity\Knight;

class ExploreDTO
{
    #[Groups(['dungeon:write'])]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    public $knightId;

    #[Assert\Type('integer')]
    #[Assert\NotNull]
    public $dungeonId;
}