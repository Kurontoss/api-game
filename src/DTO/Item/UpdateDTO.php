<?php

namespace App\DTO\Item;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateDTO
{
    #[Groups(['item:write'])]
    #[OA\Property(type: 'string', maxLength: 255, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public $name;

    #[Groups(['item:write'])]
    #[OA\Property(type: 'integer', minimum: 0, nullable: true)]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    public $value;

    #[Groups(['item:write'])]
    #[OA\Property(type: 'integer', minimum: 0, nullable: true)]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    public $hpRegen;
}