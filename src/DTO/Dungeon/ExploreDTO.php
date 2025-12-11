<?php

namespace App\DTO\Dungeon;

use Symfony\Component\Serializer\Annotation\Groups;

class ExploreDTO
{
    #[Groups(['dungeon:write'])]
    public ?int $knightId = null;
}