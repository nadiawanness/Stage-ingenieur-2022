<?php

namespace App\Entity;

use App\Repository\CoreAgencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoreAgencyRepository::class)]
class CoreAgency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $internalCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $siret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $activity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $idErp = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $header = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $footer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $organizationType = null;

    #[ORM\Column]
    private ?int $totalItemsCount = null;

    #[ORM\Column]
    private ?bool $isDefault = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'coreAgency', targetEntity: CoreUserAgencies::class, orphanRemoval: true)]
    private Collection $coreUserAgencies;

    public function __construct()
    {
        $this->coreUserAgencies = new ArrayCollection();
        $this->coreUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInternalCode(): ?int
    {
        return $this->internalCode;
    }

    public function setInternalCode(?int $internalCode): self
    {
        $this->internalCode = $internalCode;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity(?string $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getIdErp(): ?string
    {
        return $this->idErp;
    }

    public function setIdErp(?string $idErp): self
    {
        $this->idErp = $idErp;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getHeader(): ?string
    {
        return $this->header;
    }

    public function setHeader(?string $header): self
    {
        $this->header = $header;

        return $this;
    }

    public function getFooter(): ?string
    {
        return $this->footer;
    }

    public function setFooter(?string $footer): self
    {
        $this->footer = $footer;

        return $this;
    }

    public function getOrganizationType(): ?string
    {
        return $this->organizationType;
    }

    public function setOrganizationType(?string $organizationType): self
    {
        $this->organizationType = $organizationType;

        return $this;
    }

    public function getTotalItemsCount(): ?int
    {
        return $this->totalItemsCount;
    }

    public function setTotalItemsCount(int $totalItemsCount): self
    {
        $this->totalItemsCount = $totalItemsCount;

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

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

      /**
     * @return Collection<int, CoreUserAgencies>
     */
    public function getCoreUserAgencies(): Collection
    {
        return $this->coreUserAgencies;
    }

    public function addCoreUserAgency(CoreUserAgencies $coreUserAgency): self
    {
        if (!$this->coreUserAgencies->contains($coreUserAgency)) {
            $this->coreUserAgencies->add($coreUserAgency);
            $coreUserAgency->setCoreAgency($this);
        }

        return $this;
    }

    public function removeCoreUserAgency(CoreUserAgencies $coreUserAgency): self
    {
        if ($this->coreUserAgencies->removeElement($coreUserAgency)) {
            // set the owning side to null (unless already changed)
            if ($coreUserAgency->getCoreAgency() === $this) {
                $coreUserAgency->setCoreAgency(null);
            }
        }

        return $this;
    }

}
