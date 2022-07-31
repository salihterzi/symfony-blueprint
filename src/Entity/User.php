<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['auth'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['auth'])]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['auth'])]
    private $lastName;

    #[ORM\Column(type: 'string')]
    #[Groups(['auth'])]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: "users")]
    #[ORM\JoinTable(name: 'user_roles')]
    private Collection $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    #[Groups(['auth'])]
    public function getRoles(): array
    {
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        foreach ($this->roles as $role) {
            $roles[] = 'ROLE_' . strtoupper($role->getName());
        }
        return $roles;
    }

    public function getRolesAsCollection()
    {
        return $this->roles;
    }

    public function addRole(Role $role): static
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }
        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

}
