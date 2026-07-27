<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\StatutEcheance;
use App\Enum\TypeMontant;
use App\Repository\EcheanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Obligation/échéance à respecter (fiscale, sociale, administrative).
 * Structure re-exprimée depuis la spécification gelée (inventaire R2, `ECHEANCES`) —
 * aucune donnée de démonstration n'est reprise. Modèle à confirmer en R1.
 */
#[ORM\Entity(repositoryClass: EcheanceRepository::class)]
#[ORM\Index(name: 'idx_echeance_date', fields: ['dateEcheance'])]
class Echeance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private string $libelle;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $dateEcheance;

    /** Montant en CENTIMES d'euro (jamais un flottant). Null si non chiffré. */
    #[ORM\Column(nullable: true)]
    private ?int $montantCentimes = null;

    #[ORM\Column(enumType: TypeMontant::class, nullable: true)]
    private ?TypeMontant $typeMontant = null;

    #[ORM\Column(enumType: StatutEcheance::class)]
    private StatutEcheance $statut = StatutEcheance::A_FAIRE;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDateEcheance(): \DateTimeImmutable
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(\DateTimeImmutable $dateEcheance): static
    {
        $this->dateEcheance = $dateEcheance;

        return $this;
    }

    public function getMontantCentimes(): ?int
    {
        return $this->montantCentimes;
    }

    public function setMontantCentimes(?int $montantCentimes): static
    {
        $this->montantCentimes = $montantCentimes;

        return $this;
    }

    public function getTypeMontant(): ?TypeMontant
    {
        return $this->typeMontant;
    }

    public function setTypeMontant(?TypeMontant $typeMontant): static
    {
        $this->typeMontant = $typeMontant;

        return $this;
    }

    public function getStatut(): StatutEcheance
    {
        return $this->statut;
    }

    public function setStatut(StatutEcheance $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Règle métier (inventaire R2 §C, `isOverdue`) : l'échéance est en retard
     * si sa date est antérieure à la date de référence (aujourd'hui par défaut).
     */
    public function estEnRetard(?\DateTimeImmutable $reference = null): bool
    {
        $reference ??= new \DateTimeImmutable('today');

        return $this->dateEcheance < $reference;
    }
}
