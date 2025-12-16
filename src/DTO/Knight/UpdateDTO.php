<?php

namespace App\DTO\Knight;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateDTO
{
    #[Groups(['knight:write'])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public $name;
}