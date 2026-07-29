<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Données d'inscription (§16.1). Minimisation (§17.1) : strict nécessaire à la création
 * du compte. Mot de passe ≥ 12 caractères, sans règle de complexité absurde ; confirmation
 * exigée. La validation serveur fait foi (§2.1).
 */
final class RegistrationRequest
{
    #[Assert\NotBlank(message: 'Indiquez votre prénom.')]
    #[Assert\Length(max: 100)]
    public string $prenom = '';

    #[Assert\NotBlank(message: 'Indiquez votre nom.')]
    #[Assert\Length(max: 100)]
    public string $nom = '';

    #[Assert\NotBlank(message: 'Indiquez votre adresse e-mail.')]
    #[Assert\Email(message: 'Cette adresse e-mail n\'est pas valide.')]
    #[Assert\Length(max: 180)]
    public string $email = '';

    #[Assert\NotBlank(message: 'Choisissez un mot de passe.')]
    #[Assert\Length(min: 12, minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères.', max: 4096)]
    public string $plainPassword = '';

    #[Assert\EqualTo(propertyPath: 'plainPassword', message: 'Les deux mots de passe ne correspondent pas.')]
    public string $plainPasswordConfirm = '';
}
