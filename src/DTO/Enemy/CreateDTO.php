<?php

namespace App\DTO\Enemy;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class CreateDTO
{
    #[Groups(['enemy:write'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $name = '';

    #[Groups(['enemy:write'])]
    #[Assert\Positive]
    public int $hp = 1;

    #[Groups(['enemy:write'])]
    #[Assert\Positive]
    public int $strength = 1;

    #[Groups(['enemy:write'])]
    #[Assert\PositiveOrZero]
    public int $exp = 0;

    #[Groups(['enemy:write'])]
    public ?int $dungeonId = null;

    #[Groups(['enemy:write'])]
    public ?int $lootPoolId = null;
}