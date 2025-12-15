<?php

namespace App\Assembler;

use App\Entity\User;
use App\DTO\User\CreateDTO;

class UserAssembler
{
    public function fromCreateDTO(CreateDTO $dto): User
    {
        $user = new User();
        $user->setEmail($dto->email);
        $user->setName($dto->name);
        $user->setPassword($dto->password);

        return $user;
    }
}