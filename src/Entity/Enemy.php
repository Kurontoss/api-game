<?php

namespace App\Entity;

use App\Repository\EnemyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EnemyRepository::class)]
class Enemy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['enemy:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[Groups(['enemy:read'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $name = '';

    #[ORM\Column(nullable: false)]
    #[Groups(['enemy:read'])]
    #[Assert\Positive]
    private int $hp = 1;

    #[ORM\Column(nullable: false)]
    #[Groups(['enemy:read'])]
    #[Assert\Positive]
    private int $strength = 1;

    #[ORM\ManyToOne(inversedBy: 'enemies')]
    #[Groups(['enemy_dungeon:read'])]
    private ?Dungeon $dungeon = null;

    #[ORM\Column(nullable: false)]
    #[Groups(['enemy:read'])]
    #[Assert\PositiveOrZero]
    private int $exp = 0;

    #[ORM\ManyToOne]
    #[Groups(['enemy_loot_pool:read'])]
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
