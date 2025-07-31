<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[UniqueEntity(
    fields: ['name'],
    message: 'Cette catégorie existe déjà, Veuillez en choisir une autre ',
)] // toujours au dessus de la classe 
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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ss_category_id = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $articles_id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
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
    public function getSsCategoryId(): ?string
    {
        return $this->ss_category_id;
    }

    public function setSsCategoryId(string $ss_category_id): static
    {
        $this->ss_category_id = $ss_category_id;

        return $this;
    }

    public function getArticlesId(): ?string
    {
        return $this->articles_id;
    }

    public function setArticlesId(string $articles_id): static
    {
        $this->articles_id = $articles_id;

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
}
