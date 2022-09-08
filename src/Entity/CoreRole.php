<?php

namespace App\Entity;

use App\Repository\CoreRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CoreRoleRepository::class)]
class CoreRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['coreuser:read', 'corerole:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $visibility = null;

    #[ORM\Column]
    private ?bool $isDefault = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'coreRole', targetEntity: CoreUserRole::class, orphanRemoval: true)]
    private Collection $coreUserRoles;

    public function __construct()
    {
        $this->isDefault = true;
        $this->enabled = true;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutabe();
        $this->coreUserRoles = new ArrayCollection();
    }

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

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(?string $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function isIsDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, CoreUserRole>
     */
    public function getCoreUserRoles(): Collection
    {
        return $this->coreUserRoles;
    }

    public function addCoreUserRole(CoreUserRole $coreUserRole): self
    {
        if (!$this->coreUserRoles->contains($coreUserRole)) {
            $this->coreUserRoles->add($coreUserRole);
            $coreUserRole->setCoreRole($this);
        }

        return $this;
    }

    public function removeCoreUserRole(CoreUserRole $coreUserRole): self
    {
        if ($this->coreUserRoles->removeElement($coreUserRole)) {
            // set the owning side to null (unless already changed)
            if ($coreUserRole->getCoreRole() === $this) {
                $coreUserRole->setCoreRole(null);
            }
        }

        return $this;
    }
}
