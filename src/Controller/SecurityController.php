<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Connexion / déconnexion de l'espace applicatif.
 */
final class SecurityController extends AbstractController
{
    /**
     * Affiche le formulaire de connexion et l'éventuelle erreur d'authentification.
     */
    #[Route('/connexion', name: 'connexion')]
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/connexion.html.twig', [
            'derniere_erreur' => $authenticationUtils->getLastAuthenticationError(),
            'dernier_email' => $authenticationUtils->getLastUsername(),
        ]);
    }

    /**
     * Interceptée par la clé `logout` du firewall — le corps n'est jamais exécuté.
     */
    #[Route('/deconnexion', name: 'deconnexion')]
    public function deconnexion(): never
    {
        throw new \LogicException('Cette méthode est interceptée par la clé logout du firewall.');
    }
}
