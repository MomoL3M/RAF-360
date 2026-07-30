<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\StatutFacture;
use App\Repository\FactureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Facture (fournisseur ou client) suivie par l'application.
 * Structure re-exprimée depuis la spécification gelée (inventaire R2, module factures) —
 * aucune donnée de démonstration n'est reprise. Modèle à confirmer en R1.
 */
#[ORM\Entity(repositoryClass: FactureRepository::class)]
#[ORM\Index(name: 'idx_facture_statut', fields: ['statut'])]
#[ORM\UniqueConstraint(name: 'uniq_facture_numero', columns: ['numero'])]
#[UniqueEntity('numero')]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private string $numero;

    #[ORM\Column(length: 200)]
    private string $tiers;

    /** Montant TTC en CENTIMES d'euro. */
    #[ORM\Column]
    private int $montantCentimes;

    #[ORM\Column(enumType: StatutFacture::class)]
    private StatutFacture $statut = StatutFacture::A_TRAITER;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $dateEmission;

    /** Vrai si la facture relève du format e-facturation (réforme 2026/2027). */
    #[ORM\Column]
    private bool $eFacture = false;

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

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getTiers(): string
    {
        return $this->tiers;
    }

    public function setTiers(string $tiers): static
    {
        $this->tiers = $tiers;

        return $this;
    }

    public function getMontantCentimes(): int
    {
        return $this->montantCentimes;
    }

    public function setMontantCentimes(int $montantCentimes): static
    {
        $this->montantCentimes = $montantCentimes;

        return $this;
    }

    public function getStatut(): StatutFacture
    {
        return $this->statut;
    }

    public function setStatut(StatutFacture $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateEmission(): \DateTimeImmutable
    {
        return $this->dateEmission;
    }

    public function setDateEmission(\DateTimeImmutable $dateEmission): static
    {
        $this->dateEmission = $dateEmission;

        return $this;
    }

    public function isEFacture(): bool
    {
        return $this->eFacture;
    }

    public function setEFacture(bool $eFacture): static
    {
        $this->eFacture = $eFacture;

        return $this;
    }
}
