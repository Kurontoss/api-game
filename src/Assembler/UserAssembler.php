<?php

namespace App\Assembler;

use App\DTO\User\CreateDTO;
use App\Entity\User;

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