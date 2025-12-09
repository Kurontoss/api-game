<?php

namespace App\Entity\Item;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\Item\ItemRepository;
use App\Entity\Item\Food;
use App\Entity\Knight;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap([
    "item" => Item::class,
    "food" => Food::class,
])]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['item:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 256)]
    #[Groups(['item:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['item:read'])]
    private ?int $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }
}
