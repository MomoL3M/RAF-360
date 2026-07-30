<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\DomaineDocument;
use App\Repository\DocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Pièce déposée dans le coffre-fort documentaire.
 * Structure re-exprimée depuis la spécification gelée (inventaire R2, `DOC_TREE`) —
 * aucune donnée de démonstration n'est reprise. Modèle à confirmer en R1.
 */
#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ORM\Index(name: 'idx_document_domaine', fields: ['domaine'])]
class Document
{
    /** Seuil de fiabilité du score OCR (inventaire R2 §C : fiable si ≥ 85). */
    public const int SEUIL_FIABILITE = 85;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $nom;

    #[ORM\Column(enumType: DomaineDocument::class)]
    private DomaineDocument $domaine;

    #[ORM\Column(length: 100)]
    private string $typeDocument;

    /** Score de confiance de l'OCR, de 0 à 100. Null si non analysé. */
    #[ORM\Column(nullable: true)]
    private ?int $scoreConfiance = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $dateDepot;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Entreprise $entreprise;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntreprise(): Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(Entreprise $entreprise): static
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDomaine(): DomaineDocument
    {
        return $this->domaine;
    }

    public function setDomaine(DomaineDocument $domaine): static
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getTypeDocument(): string
    {
        return $this->typeDocument;
    }

    public function setTypeDocument(string $typeDocument): static
    {
        $this->typeDocument = $typeDocument;

        return $this;
    }

    public function getScoreConfiance(): ?int
    {
        return $this->scoreConfiance;
    }

    public function setScoreConfiance(?int $scoreConfiance): static
    {
        $this->scoreConfiance = $scoreConfiance;

        return $this;
    }

    public function getDateDepot(): \DateTimeImmutable
    {
        return $this->dateDepot;
    }

    public function setDateDepot(\DateTimeImmutable $dateDepot): static
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    /** Règle R2 §C : document fiable si le score OCR atteint le seuil. */
    public function estFiable(): bool
    {
        return null !== $this->scoreConfiance && $this->scoreConfiance >= self::SEUIL_FIABILITE;
    }
}
