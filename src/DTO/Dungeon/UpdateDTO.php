<?php

namespace App\DTO\Dungeon;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateDTO
{
    #[Groups(['dungeon:write'])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public $name;

    #[Groups(['dungeon:write'])]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    public $level;

    #[Groups(['dungeon:write'])]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    public $exp;
}