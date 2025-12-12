<?php

namespace App\DTO\Dungeon;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class ExploreDTO
{
    #[Groups(['dungeon:write'])]
    #[Assert\NotNull]
    #[Assert\Positive]
    public ?int $knightId = null;
}