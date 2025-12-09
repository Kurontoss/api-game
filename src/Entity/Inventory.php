<?php

namespace App\Entity;

use App\Entity\Item\Item;
use App\Repository\InventoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventoryRepository::class)]
class Inventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['inventory:read'])]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['inventory:read'])]
    private ?Item $item = null;

    #[ORM\ManyToOne(inversedBy: 'inventories')]
    #[Groups(['inventory:read'])]
    private ?Knight $knight = null;

    #[ORM\Column]
    #[Groups(['inventory:read'])]
    private ?int $count = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(Item $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getKnight(): ?Knight
    {
        return $this->knight;
    }

    public function setKnight(?Knight $knight): static
    {
        $this->knight = $knight;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): static
    {
        $this->count = $count;

        return $this;
    }
}
