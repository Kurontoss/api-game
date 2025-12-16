<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Entity\Item\ItemInstance;
use App\Repository\KnightRepository;

#[ORM\Entity(repositoryClass: KnightRepository::class)]
class Knight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['knight:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['knight:read'])]
    private string $name = '';

    #[ORM\Column]
    #[Groups(['knight:read'])]
    private int $level = 1;

    #[ORM\ManyToOne(inversedBy: 'knights')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['knight_user:read'])]
    private ?User $user = null;

    #[ORM\Column]
    #[Groups(['knight:read'])]
    private int $exp = 0;

    #[ORM\Column]
    #[Groups(['knight:read'])]
    private int $expToNextLevel = 1;

    #[ORM\Column]
    #[Groups(['knight:read'])]
    private int $hp = 1;

    #[ORM\Column]
    #[Groups(['knight:read'])]
    private int $maxHp = 1;

    /**
     * @var Collection<int, ItemInstance>
     */
    #[ORM\OneToMany(targetEntity: ItemInstance::class, mappedBy: 'knight')]
    #[Groups(['knight_inventory:read'])]
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
     * @return Collection<int, ItemInstance>
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

    public function addItemInstance(ItemInstance $itemInstance): static
    {
        if (!$this->inventory->contains($itemInstance)) {
            $this->inventory->add($itemInstance);
            $itemInstance->setKnight($this);
        }

        return $this;
    }

    public function removeItemInstance(ItemInstance $itemInstance): static
    {
        if ($this->inventory->removeElement($itemInstance)) {
            // set the owning side to null (unless already changed)
            if ($itemInstance->getKnight() === $this) {
                $itemInstance->setKnight(null);
            }
        }

        return $this;
    }
}
