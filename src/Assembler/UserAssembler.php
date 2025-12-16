<?php

namespace App\Assembler;

use App\DTO\User\CreateDTO;
use App\DTO\User\UpdateDTO;
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

    public function fromUpdateDTO(UpdateDTO $dto, User $user): User
    {
        if ($dto->email) {
            $user->setEmail($dto->email);
        }

        if ($dto->name) {
            $user->setName($dto->name);
        }

        if ($dto->password) {
            $user->setPassword($dto->password);
        }

        return $user;
    }
}