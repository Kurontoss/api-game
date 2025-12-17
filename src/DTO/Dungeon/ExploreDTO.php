<?php

namespace App\DTO\Dungeon;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Dungeon;
use App\Entity\Knight;

class ExploreDTO
{
    #[Groups(['dungeon:write'])]
    #[OA\Property(type: 'integer', minimum: 1)]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    public $knightId;

    #[OA\Property(type: 'integer', minimum: 1)]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    public $dungeonId;
}