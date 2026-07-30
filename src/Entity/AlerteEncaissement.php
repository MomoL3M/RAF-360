<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\StatutEncaissement;
use App\Repository\AlerteEncaissementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Encaissement attendu d'un tiers (alerte de trésorerie).
 * Structure re-exprimée depuis la spécification gelée (inventaire R2, `CASH_ALERTS`) —
 * aucune donnée de démonstration n'est reprise. Modèle à confirmer en R1.
 */
#[ORM\Entity(repositoryClass: AlerteEncaissementRepository::class)]
#[ORM\Index(name: 'idx_alerte_statut', fields: ['statut'])]
class AlerteEncaissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private string $tiers;

    #[ORM\Column]
    private int $montantCentimes;

    #[ORM\Column(enumType: StatutEncaissement::class)]
    private StatutEncaissement $statut = StatutEncaissement::ATTENDU;

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

    public function getStatut(): StatutEncaissement
    {
        return $this->statut;
    }

    public function setStatut(StatutEncaissement $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}
