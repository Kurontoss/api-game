<?php

namespace App\Entity;

use App\Entity\Item\Item;
use App\Repository\DropPoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DropPoolRepository::class)]
class DropPool
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\ManyToMany(targetEntity: Item::class)]
    #[Groups(['dropPool:read'])]
    private Collection $items;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    #[Groups(['dropPool:read'])]
    private array $chances = [];

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $minAmounts = [];

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $maxAmounts = [];

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
