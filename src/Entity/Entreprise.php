<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\RegimeTva;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entreprise cliente (locataire) à laquelle sont rattachés les utilisateurs.
 * Alimentée par l'onboarding (SIREN → régime TVA → secteur), fiche projet R1.
 */
#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
#[ORM\UniqueConstraint(name: 'uniq_entreprise_siren', columns: ['siren'])]
#[UniqueEntity('siren')]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank]
    private string $raisonSociale;

    /** SIREN à 9 chiffres (identifiant légal de l'entreprise). */
    #[ORM\Column(length: 9)]
    #[Assert\Regex(pattern: '/^\d{9}$/', message: 'Le SIREN doit comporter exactement 9 chiffres.')]
    private string $siren;

    #[ORM\Column(enumType: RegimeTva::class, nullable: true)]
    private ?RegimeTva $regimeTva = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $secteurActivite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siteWeb = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaisonSociale(): string
    {
        return $this->raisonSociale;
    }

    public function setRaisonSociale(string $raisonSociale): static
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    public function getSiren(): string
    {
        return $this->siren;
    }

    public function setSiren(string $siren): static
    {
        $this->siren = $siren;

        return $this;
    }

    public function getRegimeTva(): ?RegimeTva
    {
        return $this->regimeTva;
    }

    public function setRegimeTva(?RegimeTva $regimeTva): static
    {
        $this->regimeTva = $regimeTva;

        return $this;
    }

    public function getSecteurActivite(): ?string
    {
        return $this->secteurActivite;
    }

    public function setSecteurActivite(?string $secteurActivite): static
    {
        $this->secteurActivite = $secteurActivite;

        return $this;
    }

    public function getSiteWeb(): ?string
    {
        return $this->siteWeb;
    }

    public function setSiteWeb(?string $siteWeb): static
    {
        $this->siteWeb = $siteWeb;

        return $this;
    }
}
