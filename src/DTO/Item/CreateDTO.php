<?php

namespace App\DTO\Item;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class CreateDTO
{
    #[Groups(['item:write'])]
    #[OA\Property(type: 'string', maxLength: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public $name;

    #[Groups(['item:write'])]
    #[OA\Property(type: 'integer', minimum: 0)]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    public $value;

    #[Groups(['item:write'])]
    #[OA\Property(type: 'string', maxLength: 255)]
    #[Assert\Type('string')]
    #[Assert\NotNull]
    #[Assert\Length(max: 255)]
    public $type;

    #[Groups(['item:write'])]
    #[OA\Property(type: 'integer', minimum: 0)]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    public $hpRegen;
}