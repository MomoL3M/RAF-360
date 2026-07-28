<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Contrôle d'appartenance (§16.1) : un utilisateur n'accède qu'aux données de SON
 * entreprise. L'admin plateforme accède à tout ; la gestion exige d'être dirigeant.
 *
 * @extends Voter<string, Entreprise>
 */
final class EntrepriseVoter extends Voter
{
    public const string VIEW = 'ENTREPRISE_VIEW';
    public const string MANAGE = 'ENTREPRISE_MANAGE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::VIEW, self::MANAGE], true) && $subject instanceof Entreprise;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $utilisateur = $token->getUser();
        if (!$utilisateur instanceof Utilisateur) {
            return false;
        }

        if (\in_array('ROLE_ADMIN', $utilisateur->getRoles(), true)) {
            return true;
        }

        $entreprise = $utilisateur->getEntreprise();
        if (null === $entreprise || $entreprise->getId() !== $subject->getId()) {
            return false;
        }

        return self::MANAGE !== $attribute
            || \in_array('ROLE_DIRIGEANT', $utilisateur->getRoles(), true);
    }
}
