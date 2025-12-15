<?php

namespace App\Assembler;

use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Knight;
use App\DTO\Knight\CreateDTO;

class KnightAssembler
{
    public function __construct(
        private Security $security,
    ) {}

    public function fromCreateDTO(CreateDTO $dto): Knight
    {
        $knight = new Knight();
        $knight->setName($dto->name);
        $knight->setLevel(1);
        $knight->setExp(0);
        $knight->setExpToNextLevel(10);
        $knight->setHp(10);
        $knight->setMaxHp(10);
        $knight->setUser($this->security->getUser());

        return $knight;
    }
}