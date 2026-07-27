<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FluxTresorerieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Point mensuel de trésorerie (réalisé ou prévisionnel).
 * Structure re-exprimée depuis la spécification gelée (inventaire R2, `CASH`) —
 * aucune donnée de démonstration n'est reprise. Modèle à confirmer en R1.
 */
#[ORM\Entity(repositoryClass: FluxTresorerieRepository::class)]
#[ORM\Index(name: 'idx_flux_position', fields: ['position'])]
class FluxTresorerie
{
    /** Seuil d'alerte de solde (inventaire R2 §C : sous 40 k€ = rouge), en centimes. */
    public const int SEUIL_ALERTE_CENTIMES = 4_000_000;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** Libellé du mois affiché (ex. « Novembre 2025 »). */
    #[ORM\Column(length: 30)]
    private string $moisLabel;

    /** Rang chronologique du mois dans la série (0 = le plus ancien). */
    #[ORM\Column]
    private int $position;

    #[ORM\Column]
    private int $entreesCentimes;

    #[ORM\Column]
    private int $sortiesCentimes;

    #[ORM\Column]
    private int $soldeCentimes;

    /** Vrai si le mois est réalisé ; faux s'il est prévisionnel. */
    #[ORM\Column]
    private bool $realise;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMoisLabel(): string
    {
        return $this->moisLabel;
    }

    public function setMoisLabel(string $moisLabel): static
    {
        $this->moisLabel = $moisLabel;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getEntreesCentimes(): int
    {
        return $this->entreesCentimes;
    }

    public function setEntreesCentimes(int $entreesCentimes): static
    {
        $this->entreesCentimes = $entreesCentimes;

        return $this;
    }

    public function getSortiesCentimes(): int
    {
        return $this->sortiesCentimes;
    }

    public function setSortiesCentimes(int $sortiesCentimes): static
    {
        $this->sortiesCentimes = $sortiesCentimes;

        return $this;
    }

    public function getSoldeCentimes(): int
    {
        return $this->soldeCentimes;
    }

    public function setSoldeCentimes(int $soldeCentimes): static
    {
        $this->soldeCentimes = $soldeCentimes;

        return $this;
    }

    public function isRealise(): bool
    {
        return $this->realise;
    }

    public function setRealise(bool $realise): static
    {
        $this->realise = $realise;

        return $this;
    }

    /** Écart du mois (inventaire R2 §C : sorties - entrées). */
    public function ecartCentimes(): int
    {
        return $this->sortiesCentimes - $this->entreesCentimes;
    }

    /** Solde sous le seuil d'alerte (inventaire R2 §C). */
    public function estSousSeuil(): bool
    {
        return $this->soldeCentimes < self::SEUIL_ALERTE_CENTIMES;
    }
}
