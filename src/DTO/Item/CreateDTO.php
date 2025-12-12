<?php

namespace App\DTO\Item;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class CreateDTO
{
    #[Groups(['item:write'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $name = '';

    #[Groups(['item:write'])]
    #[Assert\PositiveOrZero]
    public int $value = 0;

    #[Groups(['item:write'])]
    #[Assert\Length(max: 255)]
    public string $type = '';

    #[Groups(['item:write'])]
    #[Assert\PositiveOrZero]
    public int $hpRegen = 0;
}