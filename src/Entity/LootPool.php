<?php

namespace App\Entity;

use App\Entity\Item\Item;
use App\Repository\LootPoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LootPoolRepository::class)]
class LootPool
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['lootPool:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 256)]
    #[Groups(['lootPool:read'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\ManyToMany(targetEntity: Item::class)]
    #[Groups(['lootPool:read'])]
    private Collection $items;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    #[Groups(['lootPool:read'])]
    private array $chances = [];

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    #[Groups(['lootPool:read'])]
    private array $minAmounts = [];

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    #[Groups(['lootPool:read'])]
    private array $maxAmounts = [];

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
        }

        return $this;
    }

    public function setItems(Collection $items): static
    {
        $this->items = $items;

        return $this;
    }

    public function removeItem(Item $item): static
    {
        $this->items->removeElement($item);

        return $this;
    }

    public function getChances(): array
    {
        return $this->chances;
    }

    public function setChances(array $chances): static
    {
        $this->chances = $chances;

        return $this;
    }

    public function getMinAmounts(): array
    {
        return $this->minAmounts;
    }

    public function setMinAmounts(array $minAmounts): static
    {
        $this->minAmounts = $minAmounts;

        return $this;
    }

    public function getMaxAmounts(): array
    {
        return $this->maxAmounts;
    }

    public function setMaxAmounts(array $maxAmounts): static
    {
        $this->maxAmounts = $maxAmounts;

        return $this;
    }
}
