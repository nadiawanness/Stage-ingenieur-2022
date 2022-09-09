<?php

namespace App\Entity;

use App\Repository\CoreUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CoreUserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class CoreUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const TYPE_ADMIN_ADDITIONAL = 'core_admin_additional';
    public const TYPE_USER_ADDITIONAL = 'core_user_additional';
    public const CIVILITY_MR = 'mr';
    public const CIVILITY_MS = 'ms';
    public const CIVILITY_MRS = 'mrs';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['coreuser:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Username is required !')]
    #[Groups('coreuser:read')]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    #[Groups('coreuser:read')]
    private ?string $usernameCanonical = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Email is required !')]
    #[Groups(['coreuser:read'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups('coreuser:read')]
    private ?string $emailCanonical = null;

    #[ORM\Column(length: 255)]
    // #[Assert\NotBlank(message: 'Password is required !')]
    // #[Assert\Length(min: 6)]
    #[Groups('coreuser:read')]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('coreuser:read')]
    private ?string $salt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups('coreuser:read')]
    private ?\DateTimeInterface $lastLogin = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('coreuser:read')]
    private ?string $confirmationToken = null;

    #[ORM\Column(nullable: true)]
    #[Groups('coreuser:read')]
    private ?\DateTimeImmutable $passwordRequestedAt = null;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('coreuser:read')]
    private ?string $locale = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('coreuser:read')]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('coreuser:read')]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('coreuser:read')]
    private ?string $functionUser = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('coreuser:read')]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    // #[Assert\Choice(callback: 'getCivilities')]
    #[Assert\Choice([CoreUser::CIVILITY_MR, CoreUser::CIVILITY_MRS, CoreUser::CIVILITY_MS])]
    #[Assert\NotBlank(message: 'Civility is required !')]
    #[Groups('coreuser:read')]
    private ?string $civility = null;

    #[ORM\Column(length: 255)]
    #[Groups('coreuser:read')]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('coreuser:read')]
    private ?string $idErp = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('coreuser:read')]
    private ?string $confirmPassword = null;

    #[ORM\Column]
    #[Groups('coreuser:read')]
    private ?bool $enabled = null;

    #[ORM\Column]
    #[Groups('coreuser:read')]
    private ?bool $hasDelegate = null;

    #[ORM\Column]
    #[Groups('coreuser:read')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups('coreuser:read')]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: CoreOrganization::class)]
    #[ORM\JoinTable(name: 'core_user_organization')]
    #[ORM\JoinColumn(name: 'core_user_additional_id', referencedColumnName: 'id')]
    #[Groups(['coreuser:read'])]
    private Collection $organizations;

    #[ORM\OneToMany(mappedBy: 'coreUser', targetEntity: CoreUserAgencies::class, orphanRemoval: true)]
    #[Groups('coreuser:read')]
    private Collection $coreUserAgencies;

    #[ORM\OneToMany(mappedBy: 'assignedTo', targetEntity: CoreOrganization::class)]
    // #[Groups('coreuser:read')]
    private Collection $coreOrganizations;

    #[Groups('coreuser:read')]
    #[ORM\OneToMany(mappedBy: 'coreUser', targetEntity: CoreCountry::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $coreCountries;

    #[ORM\OneToMany(mappedBy: 'coreUser', targetEntity: CoreUserRole::class, orphanRemoval: true)]
    #[Groups('coreuser:read')]
    private Collection $coreUserRoles;

    #[ORM\OneToMany(mappedBy: 'coreUser', targetEntity: AccessToken::class, orphanRemoval: true)]
    private Collection $accessTokens;

    /* #[ORM\OneToMany(mappedBy: 'creatorId', targetEntity: CoreAgency::class, orphanRemoval: true)]
    private Collection $agencies; */

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->organizations = new ArrayCollection();
        $this->coreUserAgencies = new ArrayCollection();
        $this->agencies = new ArrayCollection();
        $this->coreOrganizations = new ArrayCollection();
        $this->coreCountries = new ArrayCollection();
        $this->enabled = true;
        $this->hasDelegate = false;
        $this->locale = 'en';
        $this->coreUserRoles = new ArrayCollection();
        $this->accessTokens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUsernameCanonical(): ?string
    {
        return $this->usernameCanonical;
    }

    public function setUsernameCanonical(string $usernameCanonical): self
    {
        $this->usernameCanonical = $usernameCanonical;

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

    public function getEmailCanonical(): ?string
    {
        return $this->emailCanonical;
    }

    public function setEmailCanonical(string $emailCanonical): self
    {
        $this->emailCanonical = $emailCanonical;

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

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    public function getPasswordRequestedAt(): ?\DateTimeImmutable
    {
        return $this->passwordRequestedAt;
    }

    public function setPasswordRequestedAt(?\DateTimeImmutable $passwordRequestedAt): self
    {
        $this->passwordRequestedAt = $passwordRequestedAt;

        return $this;
    }

     public function getRoles(): array
     {
         $roles = $this->roles;
         // guarantee every user at least has ROLE_USER
         // $roles[] = 'ROLE_USER';

         return array_unique($roles);
     }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFunctionUser(): ?string
    {
        return $this->functionUser;
    }

    public function setFunctionUser(?string $functionUser): self
    {
        $this->functionUser = $functionUser;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCivility(): ?string
    {
        return $this->civility;
    }

    public function setCivility(?string $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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

    public function isHasDelegate(): ?bool
    {
        return $this->hasDelegate;
    }

    public function setHasDelegate(bool $hasDelegate): self
    {
        $this->hasDelegate = $hasDelegate;

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
     * @return Collection<int, CoreOrganization>
     */
    public function getOrganizations(): Collection
    {
        return $this->organizations;
    }

    public function addOrganization(CoreOrganization $organization): self
    {
        if (!$this->organizations->contains($organization)) {
            $this->organizations->add($organization);
        }

        return $this;
    }

    public function removeOrganization(CoreOrganization $organization): self
    {
        $this->organizations->removeElement($organization);

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
            $coreUserAgency->setCoreUser($this);
        }

        return $this;
    }

    public function removeCoreUserAgency(CoreUserAgencies $coreUserAgency): self
    {
        if ($this->coreUserAgencies->removeElement($coreUserAgency)) {
            // set the owning side to null (unless already changed)
            if ($coreUserAgency->getCoreUser() === $this) {
                $coreUserAgency->setCoreUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CoreOrganization>
     */
    public function getCoreOrganizations(): Collection
    {
        return $this->coreOrganizations;
    }

    public function addCoreOrganization(CoreOrganization $coreOrganization): self
    {
        if (!$this->coreOrganizations->contains($coreOrganization)) {
            $this->coreOrganizations->add($coreOrganization);
            $coreOrganization->setAssignedTo($this);
        }

        return $this;
    }

    public function removeCoreOrganization(CoreOrganization $coreOrganization): self
    {
        if ($this->coreOrganizations->removeElement($coreOrganization)) {
            // set the owning side to null (unless already changed)
            if ($coreOrganization->getAssignedTo() === $this) {
                $coreOrganization->setAssignedTo(null);
            }
        }

        return $this;
    }

   /* /**
     * @return Collection<int, CoreAgency>
     */
    /* public function getAgencies(): Collection
    {
        return $this->agencies;
    }

    public function addAgency(CoreAgency $agency): self
    {
        if (!$this->agencies->contains($agency)) {
            $this->agencies->add($agency);
            $agency->setCreatorId($this);
        }

        return $this;
    }

    public function removeAgency(CoreAgency $agency): self
    {
        if ($this->agencies->removeElement($agency)) {
            // set the owning side to null (unless already changed)
            if ($agency->getCreatorId() === $this) {
                $agency->setCreatorId(null);
            }
        }

        return $this;
    } */

    /**
     * @return Collection<int, coreCountry>
     */
    public function getCoreCountries(): Collection
    {
        return $this->coreCountries;
    }

    public function addCoreCountry(coreCountry $coreCountry): self
    {
        if (!$this->coreCountries->contains($coreCountry)) {
            $this->coreCountries->add($coreCountry);
            $coreCountry->setCoreUser($this);
        }

        return $this;
    }

    public function removeCoreCountry(coreCountry $coreCountry): self
    {
        if ($this->coreCountries->removeElement($coreCountry)) {
            // set the owning side to null (unless already changed)
            if ($coreCountry->getCoreUser() === $this) {
                $coreCountry->setCoreUser(null);
            }
        }

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
            $coreUserRole->setCoreUser($this);
        }

        return $this;
    }

    public function removeCoreUserRole(CoreUserRole $coreUserRole): self
    {
        if ($this->coreUserRoles->removeElement($coreUserRole)) {
            // set the owning side to null (unless already changed)
            if ($coreUserRole->getCoreUser() === $this) {
                $coreUserRole->setCoreUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AccessToken>
     */
    public function getAccessTokens(): Collection
    {
        return $this->accessTokens;
    }

    public function addAccessToken(AccessToken $accessToken): self
    {
        if (!$this->accessTokens->contains($accessToken)) {
            $this->accessTokens->add($accessToken);
            $accessToken->setCoreUser($this);
        }

        return $this;
    }

    public function removeAccessToken(AccessToken $accessToken): self
    {
        if ($this->accessTokens->removeElement($accessToken)) {
            // set the owning side to null (unless already changed)
            if ($accessToken->getCoreUser() === $this) {
                $accessToken->setCoreUser(null);
            }
        }

        return $this;
    }
}
