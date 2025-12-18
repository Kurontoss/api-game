<?php

namespace App\DTO\User;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateDTO
{
    #[Groups(['user:write'])]
    #[OA\Property(type: 'string', maxLength: 512, nullable: true)]
    #[Assert\Email]
    #[Assert\Length(max: 512)]
    public $email;

    #[Groups(['user:write'])]
    #[OA\Property(type: 'string', maxLength: 255, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public $name;

    #[Groups(['user:write'])]
    #[OA\Property(type: 'string', maxLength: 255, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public $password;
}