<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ArticleBlogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Article du blog (contenu éditorial public).
 * Structure re-exprimée depuis la spécification gelée (inventaire R2, `blog.ts`) —
 * aucun contenu de démonstration n'est repris. Modèle à confirmer en R1.
 */
#[ORM\Entity(repositoryClass: ArticleBlogRepository::class)]
#[ORM\Index(name: 'idx_article_date', fields: ['datePublication'])]
#[ORM\UniqueConstraint(name: 'uniq_article_slug', columns: ['slug'])]
#[UniqueEntity('slug')]
class ArticleBlog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** Identifiant d'URL (SEO), unique et stable. */
    #[ORM\Column(length: 150)]
    private string $slug;

    #[ORM\Column(length: 200)]
    private string $titre;

    /** Chapô / accroche affichée en tête d'article et en liste. */
    #[ORM\Column(length: 300)]
    private string $chapo;

    #[ORM\Column(type: Types::TEXT)]
    private string $contenu;

    #[ORM\Column(length: 120)]
    private string $auteur;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $datePublication;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getChapo(): string
    {
        return $this->chapo;
    }

    public function setChapo(string $chapo): static
    {
        $this->chapo = $chapo;

        return $this;
    }

    public function getContenu(): string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getAuteur(): string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getDatePublication(): \DateTimeImmutable
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeImmutable $datePublication): static
    {
        $this->datePublication = $datePublication;

        return $this;
    }
}
