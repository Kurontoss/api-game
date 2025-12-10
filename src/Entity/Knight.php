<?php

namespace App\Entity;

use App\Repository\KnightRepository;
use App\Entity\Item\InventoryItem;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: KnightRepository::class)]
class Knight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['knight:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 256)]
    #[Groups(['knight:read', 'knight:write'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['knight:read'])]
    private ?int $level = null;

    #[ORM\ManyToOne(inversedBy: 'knights')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    #[Groups(['knight:read'])]
    private ?int $exp = null;

    #[ORM\Column]
    #[Groups(['knight:read'])]
    private ?int $expToNextLevel = null;

    #[ORM\Column]
    #[Groups(['knight:read'])]
    private ?int $hp = null;

    #[ORM\Column]
    #[Groups(['knight:read'])]
    private ?int $maxHp = null;

    /**
     * @var Collection<int, InventoryItem>
     */
    #[ORM\OneToMany(targetEntity: InventoryItem::class, mappedBy: 'knight')]
    #[Groups(['inventory:read'])]
    private Collection $inventory;

    public function __construct()
    {
        $this->inventory = new ArrayCollection();
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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getExp(): ?int
    {
        return $this->exp;
    }

    public function setExp(int $exp): static
    {
        $this->exp = $exp;

        return $this;
    }

    public function getExpToNextLevel(): ?int
    {
        return $this->expToNextLevel;
    }

    public function setExpToNextLevel(int $expToNextLevel): static
    {
        $this->expToNextLevel = $expToNextLevel;

        return $this;
    }

    public function getHp(): ?int
    {
        return $this->hp;
    }

    public function setHp(int $hp): static
    {
        $this->hp = $hp;

        return $this;
    }

    public function getMaxHp(): ?int
    {
        return $this->maxHp;
    }

    public function setMaxHp(int $maxHp): static
    {
        $this->maxHp = $maxHp;

        return $this;
    }

    /**
     * @return Collection<int, InventoryItem>
     */
    public function getInventory(): Collection
    {
        return $this->inventory;
    }

    public function setInventory(Collection $inventory): static
    {
        $this->inventory = $inventory;

        return $this;
    }

    public function addInventoryItem(InventoryItem $inventoryItem): static
    {
        if (!$this->inventory->contains($inventoryItem)) {
            $this->inventory->add($inventoryItem);
            $inventoryItem->setKnight($this);
        }

        return $this;
    }

    public function removeInventoryItem(InventoryItem $inventoryItem): static
    {
        if ($this->inventory->removeElement($inventoryItem)) {
            // set the owning side to null (unless already changed)
            if ($inventoryItem->getKnight() === $this) {
                $inventoryItem->setKnight(null);
            }
        }

        return $this;
    }
}
