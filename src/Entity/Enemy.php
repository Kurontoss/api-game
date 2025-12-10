<?php

namespace App\Entity;

use App\Repository\EnemyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EnemyRepository::class)]
class Enemy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['enemy:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 256)]
    #[Groups(['enemy:read', 'enemy:write'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['enemy:read', 'enemy:write'])]
    private ?int $hp = null;

    #[ORM\Column]
    #[Groups(['enemy:read', 'enemy:write'])]
    private ?int $strength = null;

    #[ORM\ManyToOne(inversedBy: 'enemies')]
    private ?Dungeon $dungeon = null;

    #[ORM\Column]
    #[Groups(['enemy:read', 'enemy:write'])]
    private ?int $exp = null;

    #[ORM\ManyToOne]
    private ?LootPool $lootPool = null;

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

    public function getHp(): ?int
    {
        return $this->hp;
    }

    public function setHp(int $hp): static
    {
        $this->hp = $hp;

        return $this;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): static
    {
        $this->strength = $strength;

        return $this;
    }

    public function getDungeon(): ?Dungeon
    {
        return $this->dungeon;
    }

    public function setDungeon(?Dungeon $dungeon): static
    {
        $this->dungeon = $dungeon;

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

    public function getLootPool(): ?LootPool
    {
        return $this->lootPool;
    }

    public function setLootPool(?LootPool $lootPool): static
    {
        $this->lootPool = $lootPool;

        return $this;
    }
}
