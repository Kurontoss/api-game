<?php

namespace App\DTO\Knight;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class CreateDTO
{
    #[Groups(['knight:write'])]
    #[OA\Property(type: 'string', maxLength: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public $name;
}