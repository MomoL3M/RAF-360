<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\PrioriteAction;
use App\Enum\StatutEcheance;
use App\Repository\ActionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Action recommandée du centre d'actions (à faire / à valider / à risque…).
 * Structure re-exprimée depuis la spécification gelée (inventaire R2, `ACTIONS`) —
 * aucune donnée de démonstration n'est reprise. Réutilise le statut partagé
 * (StatutEcheance) conformément au mapping unique de la spécification. Modèle à confirmer en R1.
 */
#[ORM\Entity(repositoryClass: ActionRepository::class)]
#[ORM\Index(name: 'idx_action_priorite', fields: ['priorite'])]
class Action
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private string $libelle;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(enumType: PrioriteAction::class)]
    private PrioriteAction $priorite = PrioriteAction::MOYENNE;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPriorite(): PrioriteAction
    {
        return $this->priorite;
    }

    public function setPriorite(PrioriteAction $priorite): static
    {
        $this->priorite = $priorite;

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
}
