<?php

namespace App\Entity;

use App\Entity\Item\Item;
use App\Repository\LootPoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LootPoolRepository::class)]
class LootPool
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['loot_pool:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['loot_pool:read'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $name = '';

    /**
     * @var Collection<int, Item>
     */
    #[ORM\ManyToMany(targetEntity: Item::class)]
    #[Groups(['loot_pool:read'])]
    #[Assert\Count(min: 1)]
    private Collection $items;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['loot_pool:read'])]
    #[Assert\Count(min: 1)]
    #[Assert\All([
        new Assert\Type('float'),
        new Assert\GreaterThan(value: 0),
        new Assert\LessThanOrEqual(value: 1),
    ])]
    private array $chances = [];

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['loot_pool:read'])]
    #[Assert\Count(min: 1)]
    #[Assert\All([
        new Assert\Type('integer'),
        new Assert\Positive,
    ])]
    private array $minAmounts = [];

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['loot_pool:read'])]
    #[Assert\Count(min: 1)]
    #[Assert\All([
        new Assert\Type('integer'),
        new Assert\Positive,
    ])]
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
