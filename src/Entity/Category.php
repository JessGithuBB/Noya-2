<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ss_category_id = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $articles_id = null;

    public function getId(): ?int
    {
        return $this->id;
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
}
