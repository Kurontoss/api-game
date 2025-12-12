<?php

namespace App\Entity\Item;

use App\Entity\Item\Item;
use App\Entity\Knight;
use App\Repository\Item\InventoryItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InventoryItemRepository::class)]
class InventoryItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['inventory_item:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['inventory_item:read'])]
    private ?Item $item = null;

    #[ORM\ManyToOne(inversedBy: 'inventories')]
    #[Groups(['inventory_item_knight:read'])]
    private ?Knight $knight = null;

    #[ORM\Column]
    #[Groups(['inventory_item:read'])]
    #[Assert\Positive]
    private int $amount = 1;

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

        if ($knight !== null) {
            $knight->addInventoryItem($this);
        }

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}
