<?php

namespace App\Entity\Item;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Entity\Item\Item;
use App\Entity\Knight;
use App\Repository\Item\ItemInstanceRepository;

#[ORM\Entity(repositoryClass: ItemInstanceRepository::class)]
class ItemInstance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['item_instance:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['item_instance:read'])]
    private ?Item $item = null;

    #[ORM\ManyToOne(inversedBy: 'inventories')]
    #[Groups(['item_instance_knight:read'])]
    private ?Knight $knight = null;

    #[ORM\Column]
    #[Groups(['item_instance:read'])]
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
            $knight->addItemInstance($this);
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
