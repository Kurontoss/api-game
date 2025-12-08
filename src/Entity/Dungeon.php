<?php

namespace App\Entity;

use App\Repository\DungeonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DungeonRepository::class)]
class Dungeon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['dungeon:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 256)]
    #[Groups(['dungeon:read', 'dungeon:write'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['dungeon:read', 'dungeon:write'])]
    private ?int $level = null;

    #[ORM\Column]
    #[Groups(['dungeon:read', 'dungeon:write'])]
    private ?int $exp = null;

    /**
     * @var Collection<int, Enemy>
     */
    #[ORM\OneToMany(targetEntity: Enemy::class, mappedBy: 'dungeon')]
    private Collection $enemies;

    public function __construct()
    {
        $this->enemies = new ArrayCollection();
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

    public function getExp(): ?int
    {
        return $this->exp;
    }

    public function setExp(int $exp): static
    {
        $this->exp = $exp;

        return $this;
    }

    /**
     * @return Collection<int, Enemy>
     */
    public function getEnemies(): Collection
    {
        return $this->enemies;
    }

    public function addEnemy(Enemy $enemy): static
    {
        if (!$this->enemies->contains($enemy)) {
            $this->enemies->add($enemy);
            $enemy->setDungeon($this);
        }

        return $this;
    }

    public function removeEnemy(Enemy $enemy): static
    {
        if ($this->enemies->removeElement($enemy)) {
            // set the owning side to null (unless already changed)
            if ($enemy->getDungeon() === $this) {
                $enemy->setDungeon(null);
            }
        }

        return $this;
    }
}
