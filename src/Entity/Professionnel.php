<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\DomaineProfessionnel;
use App\Repository\ProfessionnelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Professionnel habilité du réseau (data room), consultable et joignable.
 * Structure re-exprimée depuis la spécification gelée (inventaire R2, `PRO_TREE`) —
 * aucune donnée de démonstration n'est reprise. Modèle à confirmer en R1.
 */
#[ORM\Entity(repositoryClass: ProfessionnelRepository::class)]
#[ORM\Index(name: 'idx_professionnel_domaine', fields: ['domaine'])]
class Professionnel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private string $nom;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $cabinet = null;

    #[ORM\Column(enumType: DomaineProfessionnel::class)]
    private DomaineProfessionnel $domaine;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $specialite = null;

    /** Délai indicatif de prise en charge (ex. « Sous 72h »). */
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $delaiIndicatif = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCabinet(): ?string
    {
        return $this->cabinet;
    }

    public function setCabinet(?string $cabinet): static
    {
        $this->cabinet = $cabinet;

        return $this;
    }

    public function getDomaine(): DomaineProfessionnel
    {
        return $this->domaine;
    }

    public function setDomaine(DomaineProfessionnel $domaine): static
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(?string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getDelaiIndicatif(): ?string
    {
        return $this->delaiIndicatif;
    }

    public function setDelaiIndicatif(?string $delaiIndicatif): static
    {
        $this->delaiIndicatif = $delaiIndicatif;

        return $this;
    }
}
