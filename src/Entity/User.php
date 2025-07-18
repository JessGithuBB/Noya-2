<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Impossible de créer un compte avec cet email, il est déjà utilisé.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom ne doit pas dépasser {{ limit }} caractères',
    )]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L'email ne doit pas dépasser {{ limit }} caractères",
    )]
    #[Assert\Email(
        message: "L'email est invalide ",
    )]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private bool $isVerified = false;

    /**
     * @var string The hashed password
     */
    #[Assert\NotBlank(message: 'Le mot de passe est obligatoire.')]
    #[Assert\Length(
        min: 12,
        max: 255,
        minMessage: 'Le mot de passe doit contenir au moins {{ limit}} caractères',
        maxMessage: 'Le mot de passe ne doit pas dépasser {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: "/^(?=.*[a-zà-ÿ])(?=.*\d)(?=.*[^a-zà-ÿA-Z0-9]).{11,255}$/iu",
        match: true,
        message: 'Le mot de passe doit contenir au moins une lettre minuscule, un chiffre, un caractère spécial et avoir entre 11 et 255 caractères.'
    )]
    #[ORM\Column]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $verifiedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

       // Adresse postale détaillée
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'L’adresse est obligatoire.')]
    private ?string $street = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $addressComplement = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'Le code postal est obligatoire.')]
    private ?string $postalCode = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'La ville est obligatoire.')]
    private ?string $city = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le pays est obligatoire.')]
    private ?string $country = null;

    
    #[ORM\Column(length:20, nullable:true)]
     
    private ?string $phoneNumber = null;

    public function getStreet(): ?string { return $this->street; }
    public function setStreet(string $street): self { $this->street = $street; return $this; }

    public function getAddressComplement(): ?string { return $this->addressComplement; }
    public function setAddressComplement(?string $addressComplement): self { $this->addressComplement = $addressComplement; return $this; }

    public function getPostalCode(): ?string { return $this->postalCode; }
    public function setPostalCode(string $postalCode): self { $this->postalCode = $postalCode; return $this; }

    public function getCity(): ?string { return $this->city; }
    public function setCity(string $city): self { $this->city = $city; return $this; }

    public function getCountry(): ?string { return $this->country; }
    public function setCountry(string $country): self { $this->country = $country; return $this; }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
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

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(?\DateTimeImmutable $verifiedAt): static
    {
        $this->verifiedAt = $verifiedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
