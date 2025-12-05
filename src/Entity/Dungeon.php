<?php

namespace App\Entity;

use App\Repository\DungeonRepository;
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
    private ?int $difficulty = null;

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

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }
}
