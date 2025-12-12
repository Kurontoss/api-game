<?php

namespace App\DTO\User;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class CreateDTO
{
    #[Groups(['user:write'])]
    #[Assert\Email]
    #[Assert\NotBlank]
    #[Assert\Length(max: 512)]
    public $email;

    #[Groups(['user:write'])]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public $name;

    #[Groups(['user:write'])]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Assert\NotCompromisedPassword]
    public $password;
}