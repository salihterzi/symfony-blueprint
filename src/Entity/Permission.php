<?php

namespace App\Entity;

use App\Repository\PermissionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
#[ORM\Table(name: 'permissions')]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    #[ORM\Column(type: 'string', length: 100)]
    private $displayName;

    #[ORM\ManyToOne(targetEntity: PermissionGroup::class, inversedBy: "permissions")]
    private $permissionGroup;

    #[ORM\ManyToMany(targetEntity: Role::class, mappedBy: "permissions")]
    private Collection $roles;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @return PermissionGroup
     */
    public function getPermissionGroup(): ?PermissionGroup
    {
        return $this->permissionGroup;
    }

    /**
     * @param PermissionGroup $permissionGroup
     */
    public function setPermissionGroup(PermissionGroup $permissionGroup): self
    {
        $this->permissionGroup = $permissionGroup;
        return $this;
    }
}
