<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $user_id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $short_description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $long_description = null;

    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    #[ORM\Column]
    private ?float $selling_price = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $quantity = null;

    #[ORM\Column]
    private ?bool $is_new_arrival = null;

    #[ORM\Column]
    private ?bool $is_best_seller = null;

    #[ORM\Column]
    private ?bool $is_available = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToMany(targetEntity: Keyword::class, mappedBy: 'articles', cascade: ['persist'])]
    private Collection $keywords;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: ArticleImage::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $images;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'articles')]
    #[ORM\JoinTable(name: 'articles_categories')]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: SsCategory::class)]
    #[ORM\JoinTable(name: 'articles_ss_categories')]
    private Collection $ssCategories;

    public function __construct()
    {
        $this->keywords = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->ssCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->user_id;
    }

    public function setUserId(?string $user_id): static
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
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

    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    public function setShortDescription(string $short_description): static
    {
        $this->short_description = $short_description;
        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->long_description;
    }

    public function setLongDescription(?string $long_description): static
    {
        $this->long_description = $long_description;
        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;
        return $this;
    }

    public function getSellingPrice(): ?float
    {
        return $this->selling_price;
    }

    public function setSellingPrice(float $selling_price): static
    {
        $this->selling_price = $selling_price;
        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function isNewArrival(): ?bool
    {
        return $this->is_new_arrival;
    }

    public function setIsNewArrival(bool $is_new_arrival): static
    {
        $this->is_new_arrival = $is_new_arrival;
        return $this;
    }

    public function isBestSeller(): ?bool
    {
        return $this->is_best_seller;
    }

    public function setIsBestSeller(bool $is_best_seller): static
    {
        $this->is_best_seller = $is_best_seller;
        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->is_available;
    }

    public function setIsAvailable(bool $is_available): static
    {
        $this->is_available = $is_available;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
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
 * @return Collection<int, Category>
 */
public function getCategories(): Collection
{
    return $this->categories;
}

public function addCategory(Category $category): static
{
    if (!$this->categories->contains($category)) {
        $this->categories->add($category);
    }

    return $this;
}

public function removeCategory(Category $category): static
{
    $this->categories->removeElement($category);
    return $this;
}


    /**
     * @return Collection<int, Keyword>
     */
    public function getKeywords(): Collection
    {
        return $this->keywords;
    }

    public function addKeyword(Keyword $keyword): static
    {
        if (!$this->keywords->contains($keyword)) {
            $this->keywords->add($keyword);
            $keyword->addArticle($this);
        }
        return $this;
    }

    public function removeKeyword(Keyword $keyword): static
    {
        if ($this->keywords->removeElement($keyword)) {
            $keyword->removeArticle($this);
        }
        return $this;
    }

    /**
     * @return Collection<int, ArticleImage>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ArticleImage $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setArticle($this);
        }
        return $this;
    }

    public function removeImage(ArticleImage $image): static
    {
        if ($this->images->removeElement($image)) {
            if ($image->getArticle() === $this) {
                $image->setArticle(null);
            }
        }
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
            $this->ssCategories->add($ssCategory);
        }
        return $this;
    }

    public function removeSsCategory(SsCategory $ssCategory): static
    {
        $this->ssCategories->removeElement($ssCategory);
        return $this;
    }
}
