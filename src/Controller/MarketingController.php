<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Pages publiques marketing (rendu côté serveur, iso-graphisme baseline).
 * Les formulaires (contact, diagnostic) vivent dans LeadController.
 */
final class MarketingController extends AbstractController
{
    #[Route('/', name: 'accueil', methods: ['GET'])]
    public function accueil(): Response
    {
        return $this->render('marketing/accueil.html.twig');
    }

    #[Route('/produit', name: 'produit', methods: ['GET'])]
    public function produit(): Response
    {
        return $this->render('marketing/produit.html.twig');
    }

    #[Route('/solutions', name: 'solutions', methods: ['GET'])]
    public function solutions(): Response
    {
        return $this->render('marketing/solutions.html.twig');
    }

    #[Route('/tarifs', name: 'tarifs', methods: ['GET'])]
    public function tarifs(): Response
    {
        return $this->render('marketing/tarifs.html.twig');
    }

    #[Route('/a-propos', name: 'a_propos', methods: ['GET'])]
    public function aPropos(): Response
    {
        return $this->render('marketing/a-propos.html.twig');
    }

    #[Route('/blog', name: 'blog', methods: ['GET'])]
    public function blog(): Response
    {
        return $this->render('marketing/blog.html.twig');
    }

    #[Route('/mentions-legales', name: 'mentions_legales', methods: ['GET'])]
    public function mentionsLegales(): Response
    {
        return $this->render('marketing/mentions-legales.html.twig');
    }

    #[Route('/politique-de-confidentialite', name: 'confidentialite', methods: ['GET'])]
    public function confidentialite(): Response
    {
        return $this->render('marketing/confidentialite.html.twig');
    }

    #[Route('/rgpd', name: 'rgpd', methods: ['GET'])]
    public function rgpd(): Response
    {
        return $this->render('marketing/rgpd.html.twig');
    }
}
