<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 512)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 256)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 256)]
    #[Groups(['user:write'])]
    private ?string $password = null;

    /**
     * @var Collection<int, Knight>
     */
    #[ORM\OneToMany(targetEntity: Knight::class, mappedBy: 'user')]
    private Collection $knights;

    public function __construct()
    {
        $this->knights = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }


    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
    }

    /**
     * @return Collection<int, Knight>
     */
    public function getKnights(): Collection
    {
        return $this->knights;
    }

    public function addKnight(Knight $knight): static
    {
        if (!$this->knights->contains($knight)) {
            $this->knights->add($knight);
            $knight->setUser($this);
        }

        return $this;
    }

    public function removeKnight(Knight $knight): static
    {
        if ($this->knights->removeElement($knight)) {
            // set the owning side to null (unless already changed)
            if ($knight->getUser() === $this) {
                $knight->setUser(null);
            }
        }

        return $this;
    }
}
