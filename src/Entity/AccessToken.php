<?php

namespace App\Entity;

use App\Repository\AccessTokenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccessTokenRepository::class)]
class AccessToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private array $attributes = [];

    #[ORM\Column(length: 2000, nullable: true)]
    private ?string $SingleUseToken = null;

    #[ORM\Column(nullable: true)]
    private ?bool $punchout = null;

    #[ORM\ManyToOne(inversedBy: 'accessTokens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CoreUser $coreUser = null;

    public function __construct()
    {
        $this->punchout = false;
        $this->attributes = [];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(?array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getSingleUseToken(): ?string
    {
        return $this->SingleUseToken;
    }

    public function setSingleUseToken(?string $SingleUseToken): self
    {
        $this->SingleUseToken = $SingleUseToken;

        return $this;
    }

    public function isPunchout(): ?bool
    {
        return $this->punchout;
    }

    public function setPunchout(?bool $punchout): self
    {
        $this->punchout = $punchout;

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
}
