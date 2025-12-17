<?php

namespace App\DTO\Dungeon;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateDTO
{
    #[Groups(['dungeon:write'])]
    #[OA\Property(type: 'string', maxLength: 255)]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public $name;

    #[Groups(['dungeon:write'])]
    #[OA\Property(type: 'integer', minimum: 1)]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    public $level;

    #[Groups(['dungeon:write'])]
    #[OA\Property(type: 'integer', minimum: 0)]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    public $exp;
}