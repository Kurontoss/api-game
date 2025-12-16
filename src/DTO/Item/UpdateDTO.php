<?php

namespace App\DTO\Item;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateDTO
{
    #[Groups(['item:write'])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public $name;

    #[Groups(['item:write'])]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    public $value;

    #[Groups(['item:write'])]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    public $hpRegen;
}