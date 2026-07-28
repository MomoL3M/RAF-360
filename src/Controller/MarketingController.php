<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Pages publiques marketing. L'accueil est construit (iso-graphisme baseline) ;
 * les autres routes rendent une page « en construction » afin qu'aucun lien du
 * site (nav, footer, CTA) ne soit mort avant les lots suivants.
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
        return $this->enConstruction('Produit');
    }

    #[Route('/solutions', name: 'solutions', methods: ['GET'])]
    public function solutions(): Response
    {
        return $this->enConstruction('Solutions');
    }

    #[Route('/tarifs', name: 'tarifs', methods: ['GET'])]
    public function tarifs(): Response
    {
        return $this->enConstruction('Tarifs');
    }

    #[Route('/a-propos', name: 'a_propos', methods: ['GET'])]
    public function aPropos(): Response
    {
        return $this->enConstruction('À propos');
    }

    #[Route('/blog', name: 'blog', methods: ['GET'])]
    public function blog(): Response
    {
        return $this->enConstruction('Blog');
    }

    #[Route('/contact', name: 'contact', methods: ['GET'])]
    public function contact(): Response
    {
        return $this->enConstruction('Contact');
    }

    #[Route('/diagnostic', name: 'diagnostic', methods: ['GET'])]
    public function diagnostic(): Response
    {
        return $this->enConstruction('Diagnostic gratuit');
    }

    #[Route('/mentions-legales', name: 'mentions_legales', methods: ['GET'])]
    public function mentionsLegales(): Response
    {
        return $this->enConstruction('Mentions légales');
    }

    #[Route('/politique-de-confidentialite', name: 'confidentialite', methods: ['GET'])]
    public function confidentialite(): Response
    {
        return $this->enConstruction('Politique de confidentialité');
    }

    #[Route('/rgpd', name: 'rgpd', methods: ['GET'])]
    public function rgpd(): Response
    {
        return $this->enConstruction('RGPD');
    }

    private function enConstruction(string $titre): Response
    {
        return $this->render('marketing/en-construction.html.twig', ['titre' => $titre]);
    }
}
