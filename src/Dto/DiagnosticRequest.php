<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Données de démarrage du diagnostic gratuit (SIREN + contexte sectoriel).
 * Le SIREN est normalisé (chiffres uniquement) avant validation dans le contrôleur.
 */
final class DiagnosticRequest
{
    #[Assert\NotBlank(message: 'Indiquez le SIREN de votre entreprise.')]
    #[Assert\Regex(pattern: '/^\d{9}$/', message: 'Le SIREN doit comporter 9 chiffres.')]
    public string $siren = '';

    #[Assert\NotBlank(message: 'Indiquez votre adresse email.')]
    #[Assert\Email(message: 'Cette adresse email ne semble pas valide.')]
    public string $email = '';

    #[Assert\Length(max: 200)]
    public string $siteWeb = '';

    #[Assert\Length(max: 160)]
    public string $typeActivite = '';
}
