<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\SsCategory;


#[UniqueEntity(
    fields: ['name'],
    message: 'Cette catégorie existe déjà, Veuillez en choisir une autre ',
)]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message:"Le nom est obligtoire")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom ne doit pas dépasser{{ limit }} caractères',
    )]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: SsCategory::class, orphanRemoval: true)]
    private Collection $ssCategories;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    public function __construct()
    {
        $this->ssCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, SsCategory>
     */
    public function getSsCategories(): Collection
    {
        return $this->ssCategories;
    }

    public function addSsCategory(SsCategory $ssCategory): static
    {
        if (!$this->ssCategories->contains($ssCategory)) {
            $this->ssCategories[] = $ssCategory;
            $ssCategory->setCategory($this);
        }

        return $this;
    }

    public function removeSsCategory(SsCategory $ssCategory): static
    {
        if ($this->ssCategories->removeElement($ssCategory)) {
            // set the owning side to null (unless already changed)
            if ($ssCategory->getCategory() === $this) {
                $ssCategory->setCategory(null);
            }
        }

        return $this;
    }
}
