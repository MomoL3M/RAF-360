<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Données d'un message de contact (formulaire public).
 * Mêmes contraintes côté serveur que le formulaire (§14.3).
 */
final class ContactRequest
{
    #[Assert\NotBlank(message: 'Indiquez votre nom.')]
    #[Assert\Length(min: 2, max: 120)]
    public string $nom = '';

    #[Assert\NotBlank(message: 'Indiquez votre adresse email.')]
    #[Assert\Email(message: 'Cette adresse email ne semble pas valide.')]
    public string $email = '';

    #[Assert\Length(max: 160)]
    public string $societe = '';

    #[Assert\Length(max: 30)]
    public string $telephone = '';

    #[Assert\NotBlank(message: 'Écrivez votre message.')]
    #[Assert\Length(min: 10, max: 4000, minMessage: 'Votre message est trop court (10 caractères minimum).')]
    public string $message = '';
}
