<?php

namespace App\Entity;

use App\Repository\CoreOrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CoreOrganizationRepository::class)]
class CoreOrganization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    //#[Groups('coreuser:read')]
    #[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    //#[Groups('coreuser:read')]
    #[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $companyNumber = null;

    #[ORM\Column(length: 255)]
    //#[Groups('coreuser:read')]
    #[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $companyName = null;

    #[ORM\Column(nullable: true)]
    //#[Groups('coreuser:read')]
    #[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?int $vat = null;

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    #[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $nomenclature = null;

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $createdBy = null;

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $branch = null;

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $branchStatus = null;

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $currentStatus = null;

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $companyType = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?\DateTimeInterface $dissolutionDate = null;

    #[ORM\Column(nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?bool $inactive = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?\DateTimeInterface $incorporationDate = null;

    /* #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $industryCodes = []; */

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $jurisdictionCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $nativeCompanyNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $openCorporatesUrl = null;

    #[ORM\Column( nullable: true)]
    private array $previousNames = []; 

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $registryUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $restrictedForMarketing = null;

    #[ORM\Column( nullable: true)]
    private array $source = []; 

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?string $customTranslationPath = null;

    #[ORM\Column]
    //#[Groups('coreuser:read')]
    #[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?bool $enabled = null;

    #[ORM\Column(nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?\DateTimeImmutable $retrivedAt = null;

    #[ORM\Column(nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    //#[Groups('coreuser:read')]
    //#[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'coreOrganizations')]
    #[ORM\JoinColumn(name: 'assigned_to', referencedColumnName: 'id')]
    //#[Groups('coreuser:read')]
    #[Groups([ 'coreuser:read' , 'coreorganization:read' ])]
    private ?CoreUser $assignedTo = null;

    #[ORM\OneToMany(mappedBy: 'coreOrganization', targetEntity: CoreAgency::class, orphanRemoval: true)]
    private Collection $coreAgencies;

    public function __construct()
    {
        $this->coreAgencies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyNumber(): ?string
    {
        return $this->companyNumber;
    }

    public function setCompanyNumber(string $companyNumber): self
    {
        $this->companyNumber = $companyNumber;
        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getVat(): ?int
    {
        return $this->vat;
    }

    public function setVat(?int $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getNomenclature(): ?string
    {
        return $this->nomenclature;
    }

    public function setNomenclature(?string $nomenclature): self
    {
        $this->nomenclature = $nomenclature;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getBranch(): ?string
    {
        return $this->branch;
    }

    public function setBranch(?string $branch): self
    {
        $this->branch = $branch;

        return $this;
    }

    public function getBranchStatus(): ?string
    {
        return $this->branchStatus;
    }

    public function setBranchStatus(?string $branchStatus): self
    {
        $this->branchStatus = $branchStatus;

        return $this;
    }

    public function getCurrentStatus(): ?string
    {
        return $this->currentStatus;
    }

    public function setCurrentStatus(?string $currentStatus): self
    {
        $this->currentStatus = $currentStatus;

        return $this;
    }

    public function getCompanyType(): ?string
    {
        return $this->companyType;
    }

    public function setCompanyType(?string $companyType): self
    {
        $this->companyType = $companyType;

        return $this;
    }

    public function getDissolutionDate(): ?\DateTimeInterface
    {
        return $this->dissolutionDate;
    }

    public function setDissolutionDate(?\DateTimeInterface $dissolutionDate): self
    {
        $this->dissolutionDate = $dissolutionDate;

        return $this;
    }

    public function isInactive(): ?bool
    {
        return $this->inactive;
    }

    public function setInactive(?bool $inactive): self
    {
        $this->inactive = $inactive;

        return $this;
    }

    public function getIncorporationDate(): ?\DateTimeInterface
    {
        return $this->incorporationDate;
    }

    public function setIncorporationDate(?\DateTimeInterface $incorporationDate): self
    {
        $this->incorporationDate = $incorporationDate;

        return $this;
    }

    public function getIndustryCodes(): array
    {
        $industryCodes = $this->industryCodes;
        return array_unique($industryCodes); 
    }

    public function setIndustryCodes(?array $industryCodes): self
    {
        $this->industryCodes = $industryCodes;

        return $this;
    } 

    public function getJurisdictionCode(): ?string
    {
        return $this->jurisdictionCode;
    }

    public function setJurisdictionCode(?string $jurisdictionCode): self
    {
        $this->jurisdictionCode = $jurisdictionCode;

        return $this;
    }

    public function getNativeCompanyNumber(): ?string
    {
        return $this->nativeCompanyNumber;
    }

    public function setNativeCompanyNumber(?string $nativeCompanyNumber): self
    {
        $this->nativeCompanyNumber = $nativeCompanyNumber;

        return $this;
    }

    public function getOpenCorporatesUrl(): ?string
    {
        return $this->openCorporatesUrl;
    }

    public function setOpenCorporatesUrl(?string $openCorporatesUrl): self
    {
        $this->openCorporatesUrl = $openCorporatesUrl;

        return $this;
    }

    public function getPreviousNames(): array
    {
        $previousNames = $this->previousNames;
        return array_unique($previousNames); 
    }

    public function setPreviousNames(?array $previousNames): self
    {
        $this->previousNames = $previousNames;

        return $this;
    } 

    public function getRegistryUrl(): ?string
    {
        return $this->registryUrl;
    }

    public function setRegistryUrl(?string $registryUrl): self
    {
        $this->registryUrl = $registryUrl;

        return $this;
    }

    public function getRestrictedForMarketing(): ?string
    {
        return $this->restrictedForMarketing;
    }

    public function setRestrictedForMarketing(?string $restrictedForMarketing): self
    {
        $this->restrictedForMarketing = $restrictedForMarketing;

        return $this;
    }

    /* public function getSource(): array
    {
        return $this->source;
    }

    public function setSource(?array $source): self
    {
        $this->source = $source;

        return $this;
    } */

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getCustomTranslationPath(): ?string
    {
        return $this->customTranslationPath;
    }

    public function setCustomTranslationPath(?string $customTranslationPath): self
    {
        $this->customTranslationPath = $customTranslationPath;

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

    public function getRetrivedAt(): ?\DateTimeImmutable
    {
        return $this->retrivedAt;
    }

    public function setRetrivedAt(?\DateTimeImmutable $retrivedAt): self
    {
        $this->retrivedAt = $retrivedAt;

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

    public function getAssignedTo(): ?CoreUser
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(?CoreUser $assignedTo): self
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    /**
     * @return Collection<int, CoreAgency>
     */
    public function getCoreAgencies(): Collection
    {
        return $this->coreAgencies;
    }

    public function addCoreAgency(CoreAgency $coreAgency): self
    {
        if (!$this->coreAgencies->contains($coreAgency)) {
            $this->coreAgencies->add($coreAgency);
            $coreAgency->setCoreOrganization($this);
        }

        return $this;
    }

    public function removeCoreAgency(CoreAgency $coreAgency): self
    {
        if ($this->coreAgencies->removeElement($coreAgency)) {
            // set the owning side to null (unless already changed)
            if ($coreAgency->getCoreOrganization() === $this) {
                $coreAgency->setCoreOrganization(null);
            }
        }

        return $this;
    }
}
