<?php

namespace App\Entity;

use App\Repository\CoreCountryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CoreCountryRepository::class)]
class CoreCountry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['coreuser:read', 'corecountry:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $topLevelDomain = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $alpha3Code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $callingCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $capital = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $spelling = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $region = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subRegion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $population = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numericCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $latitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $longitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $demonym = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $area = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gini = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $timezones = null;

    #[ORM\Column(nullable: true)]
    private array $borders = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nativeName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $currency = null;

    #[ORM\Column(nullable: true)]
    private array $languages = [];

    #[ORM\Column(nullable: true)]
    private array $translations = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $flag = null;

    #[ORM\Column]
    private array $regionalBlocs = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cioc = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'coreCountries', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'core_user_id', referencedColumnName: 'id')]
    private ?CoreUser $coreUser = null;

    public function __construct()
    {
        $this->enabled = true;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->languages = [];
        $this->translations = [];
        $this->regionalBlocs = [];
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

    public function getTopLevelDomain(): ?string
    {
        return $this->topLevelDomain;
    }

    public function setTopLevelDomain(?string $topLevelDomain): self
    {
        $this->topLevelDomain = $topLevelDomain;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAlpha3Code(): ?string
    {
        return $this->alpha3Code;
    }

    public function setAlpha3Code(string $alpha3Code): self
    {
        $this->alpha3Code = $alpha3Code;

        return $this;
    }

    public function getCallingCode(): ?string
    {
        return $this->callingCode;
    }

    public function setCallingCode(?string $callingCode): self
    {
        $this->callingCode = $callingCode;

        return $this;
    }

    public function getCapital(): ?string
    {
        return $this->capital;
    }

    public function setCapital(?string $capital): self
    {
        $this->capital = $capital;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getSpelling(): ?string
    {
        return $this->spelling;
    }

    public function setSpelling(?string $spelling): self
    {
        $this->spelling = $spelling;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getSubRegion(): ?string
    {
        return $this->subRegion;
    }

    public function setSubRegion(?string $subRegion): self
    {
        $this->subRegion = $subRegion;

        return $this;
    }

    public function getPopulation(): ?string
    {
        return $this->population;
    }

    public function setPopulation(?string $population): self
    {
        $this->population = $population;

        return $this;
    }

    public function getNumericCode(): ?string
    {
        return $this->numericCode;
    }

    public function setNumericCode(?string $numericCode): self
    {
        $this->numericCode = $numericCode;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getDemonym(): ?string
    {
        return $this->demonym;
    }

    public function setDemonym(?string $demonym): self
    {
        $this->demonym = $demonym;

        return $this;
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function setArea(?string $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getGini(): ?string
    {
        return $this->gini;
    }

    public function setGini(?string $gini): self
    {
        $this->gini = $gini;

        return $this;
    }

    public function getTimezones(): ?string
    {
        return $this->timezones;
    }

    public function setTimezones(?string $timezones): self
    {
        $this->timezones = $timezones;

        return $this;
    }

    public function getBorders(): array
    {
        return $this->borders;
    }

    public function setBorders(?array $borders): self
    {
        $this->borders = $borders;

        return $this;
    }

    public function getNativeName(): ?string
    {
        return $this->nativeName;
    }

    public function setNativeName(?string $nativeName): self
    {
        $this->nativeName = $nativeName;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getLanguages(): array
    {
        return $this->languages;
    }

    public function setLanguages(?array $languages): self
    {
        $this->languages = $languages;

        return $this;
    }

    public function getTranslations(): array
    {
        return $this->translations;
    }

    public function setTranslations(?array $translations): self
    {
        $this->translations = $translations;

        return $this;
    }

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function setFlag(?string $flag): self
    {
        $this->flag = $flag;

        return $this;
    }

    public function getRegionalBlocs(): array
    {
        return $this->regionalBlocs;
    }

    public function setRegionalBlocs(array $regionalBlocs): self
    {
        $this->regionalBlocs = $regionalBlocs;

        return $this;
    }

    public function getCioc(): ?string
    {
        return $this->cioc;
    }

    public function setCioc(?string $cioc): self
    {
        $this->cioc = $cioc;

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
