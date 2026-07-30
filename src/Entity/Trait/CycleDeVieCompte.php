<?php

declare(strict_types=1);

namespace App\Entity\Trait;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Horodatages du cycle de vie d'un compte. Sans eux, aucune durée de conservation ne
 * peut être APPLIQUÉE techniquement (§17.1) : ils sont la condition de la purge
 * automatique des comptes inactifs (cf. PurgeDonneesService).
 *
 * Extrait en trait pour tenir le plafond de 200 lignes de la classe Utilisateur (§2.3).
 */
trait CycleDeVieCompte
{
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $creeLe;

    /** Dernière connexion réussie : point de départ du délai d'inactivité. */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $derniereConnexion = null;

    /** Renseigné une fois le compte anonymisé — rend la purge idempotente. */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $anonymiseLe = null;

    public function getCreeLe(): \DateTimeImmutable
    {
        return $this->creeLe;
    }

    public function getDerniereConnexion(): ?\DateTimeImmutable
    {
        return $this->derniereConnexion;
    }

    public function setDerniereConnexion(?\DateTimeImmutable $date): static
    {
        $this->derniereConnexion = $date;

        return $this;
    }

    public function getAnonymiseLe(): ?\DateTimeImmutable
    {
        return $this->anonymiseLe;
    }

    public function setAnonymiseLe(?\DateTimeImmutable $date): static
    {
        $this->anonymiseLe = $date;

        return $this;
    }

    /**
     * Date servant de référence pour l'inactivité : la dernière connexion si elle existe,
     * sinon la création du compte (un compte jamais utilisé ne doit pas être éternel).
     */
    public function derniereActivite(): \DateTimeImmutable
    {
        return $this->derniereConnexion ?? $this->creeLe;
    }
}
