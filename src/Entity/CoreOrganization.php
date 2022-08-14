<?php

namespace App\Entity;

use App\Repository\CoreOrganizationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoreOrganizationRepository::class)]
class CoreOrganization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $companyNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $companyName = null;

    #[ORM\Column(nullable: true)]
    private ?int $vat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomenclature = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $createdBy = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $branch = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $branchStatus = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $currentStatus = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $companyType = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dissolutionDate = null;

    #[ORM\Column(nullable: true)]
    private ?bool $inactive = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $incorporationDate = null;

    /* #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $industryCodes = []; */

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $jurisdictionCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nativeCompanyNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $openCorporatesUrl = null;

    /* #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $previousNames = []; */

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $registryUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $restrictedForMarketing = null;

   /*  #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $source = []; */

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customTranslationPath = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $retrivedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'coreOrganizations')]
    #[ORM\JoinColumn(name: 'assigned_to', referencedColumnName: 'id')]
    private ?CoreUser $assignedTo = null;

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

    /* public function getIndustryCodes(): array
    {
        return $this->industryCodes;
        // guarantee every user at least has ROLE_USER
        $industryCodes[] = 'Codes List';

        return array_unique($industryCodes);
    }

    public function setIndustryCodes(?array $industryCodes): self
    {
        $this->industryCodes = $industryCodes;

        return $this;
    } */

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

    /* public function getPreviousNames(): array
    {
        return $this->previousNames;
    }

    public function setPreviousNames(?array $previousNames): self
    {
        $this->previousNames = $previousNames;

        return $this;
    } */

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
}
