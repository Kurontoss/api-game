<?php

namespace App\DTO\User;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateDTO
{
    #[Groups(['user:write'])]
    #[Assert\Email]
    #[Assert\Length(max: 512)]
    public $email;

    #[Groups(['user:write'])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public $name;

    #[Groups(['user:write'])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public $password;
}