<?php

namespace App\Entity;

use App\Repository\CoreUserAgenciesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CoreUserAgenciesRepository::class)]
class CoreUserAgencies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    // #[Groups('coreuser:read')]
    private ?int $id = null;

    #[ORM\Column]
    // #[Groups('coreuser:read')]
    private ?bool $isDefault = null;

    #[ORM\Column]
    // #[Groups('coreuser:read')]
    private ?bool $enabled = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'coreUserAgencies')]
    #[ORM\JoinColumn(name: 'core_user_additional_id', referencedColumnName: 'id', nullable: false)]
    private ?CoreUser $coreUser = null;

    #[ORM\ManyToOne(inversedBy: 'coreUserAgencies')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('coreuser:read')]
    private ?CoreAgency $coreAgency = null;

    public function __construct()
    {
        $this->enabled = true;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->isDefault = false;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCoreUser(): ?CoreUser
    {
        return $this->coreUser;
    }

    public function setCoreUser(?CoreUser $coreUser): self
    {
        $this->coreUser = $coreUser;

        return $this;
    }

    public function getCoreAgency(): ?CoreAgency
    {
        return $this->coreAgency;
    }

    public function setCoreAgency(?CoreAgency $coreAgency): self
    {
        $this->coreAgency = $coreAgency;

        return $this;
    }
}
