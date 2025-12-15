<?php

namespace App\Entity\Item;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Repository\Item\FoodRepository;

#[ORM\Entity(repositoryClass: FoodRepository::class)]
class Food extends Item
{
    #[ORM\Column]
    #[Groups(['item:read'])]
    private int $hpRegen = 0;

    public function getHpRegen(): ?int
    {
        return $this->hpRegen;
    }

    public function setHpRegen(int $hpRegen): static
    {
        $this->hpRegen = $hpRegen;

        return $this;
    }
}
