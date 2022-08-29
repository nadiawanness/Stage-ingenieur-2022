<?php

namespace App\Entity;

use App\Repository\CoreUserRoleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CoreUserRoleRepository::class)]
class CoreUserRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'coreUserRoles')]
    #[ORM\JoinColumn(name: 'core_user_id', referencedColumnName: 'id')]
    private ?CoreUser $coreUser = null;

    #[ORM\ManyToOne(inversedBy: 'coreUserRoles')]
    #[ORM\JoinColumn(name: 'core_role_id', referencedColumnName: 'id')]
    #[Groups('coreuser:read')]
    private ?CoreRole $coreRole = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoreUser(): ?CoreUser
    {
        return $this->coreUser;
    }

    public function setCoreUser(?CoreUser $coreUser): self
    {
        $this->coreUser = $coreUser;

        return $this;
    }

    public function getCoreRole(): ?CoreRole
    {
        return $this->coreRole;
    }

    public function setCoreRole(?CoreRole $coreRole): self
    {
        $this->coreRole = $coreRole;

        return $this;
    }
}
