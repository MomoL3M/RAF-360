<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rendez-vous pris avec un professionnel du réseau sur un créneau donné.
 * Structure re-exprimée depuis la spécification gelée (inventaire R2, `APPT_SLOTS`) —
 * aucune donnée de démonstration n'est reprise. Modèle à confirmer en R1.
 */
#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
#[ORM\Index(name: 'idx_rdv_creneau', fields: ['creneau'])]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Professionnel $professionnel;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $creneau;

    /** Vrai une fois le rendez-vous confirmé (bloqué tant qu'aucun créneau, R2 §C). */
    #[ORM\Column]
    private bool $confirme = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfessionnel(): Professionnel
    {
        return $this->professionnel;
    }

    public function setProfessionnel(Professionnel $professionnel): static
    {
        $this->professionnel = $professionnel;

        return $this;
    }

    public function getCreneau(): \DateTimeImmutable
    {
        return $this->creneau;
    }

    public function setCreneau(\DateTimeImmutable $creneau): static
    {
        $this->creneau = $creneau;

        return $this;
    }

    public function isConfirme(): bool
    {
        return $this->confirme;
    }

    public function setConfirme(bool $confirme): static
    {
        $this->confirme = $confirme;

        return $this;
    }
}
