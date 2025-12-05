<?php

namespace App\Entity;

use App\Repository\KnightRepository;
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
}
