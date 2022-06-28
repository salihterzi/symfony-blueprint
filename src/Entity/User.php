<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Serializable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use function Symfony\Component\Translation\t;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, Serializable, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastName;

    #[ORM\Column(type: 'string')]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

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

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }


    public function serialize()
    {
        return serialize($this->__serialize());
    }

    public function unserialize(string $data)
    {
        list (
            $this->id,
            $this->email,
            $this->firstName,
            $this->lastName,
            // see section on salt below
            // $this->salt
            ) = unserialize($data);
    }

    public function __serialize(): array
    {
        return [
            $this->id,
            $this->email,
            $this->firstName,
            $this->lastName];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->email = $data['email'];
        $this->firstName = $data['firstName'];
        $this->lastName = $data['lastName'];
    }
}
