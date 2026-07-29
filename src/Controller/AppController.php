<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Espace applicatif connecté (`/app`) — non indexé (voir AppNoindexSubscriber).
 * Page d'accueil provisoire : l'interface réelle sera construite à un lot ultérieur
 * (iso-graphisme depuis la baseline gelée).
 */
#[Route('/app')]
#[IsGranted('ROLE_USER')]
final class AppController extends AbstractController
{
    #[Route('', name: 'app_accueil', methods: ['GET'])]
    public function accueil(): Response
    {
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/dashboard', name: 'app_dashboard', methods: ['GET'])]
    public function dashboard(): Response
    {
        return $this->render('app/dashboard.html.twig');
    }

    #[Route('/treasury', name: 'app_treasury', methods: ['GET'])]
    public function treasury(): Response
    {
        return $this->render('app/treasury.html.twig');
    }

    #[Route('/calendar', name: 'app_calendar', methods: ['GET'])]
    public function calendar(): Response
    {
        return $this->render('app/echeances.html.twig');
    }

    #[Route('/documents', name: 'app_documents', methods: ['GET'])]
    public function documents(): Response
    {
        return $this->render('app/documents.html.twig');
    }

    #[Route('/factures', name: 'app_factures', methods: ['GET'])]
    public function factures(): Response
    {
        return $this->render('app/factures.html.twig');
    }

    #[Route('/dataroom', name: 'app_dataroom', methods: ['GET'])]
    public function dataroom(): Response
    {
        return $this->render('app/dataroom.html.twig');
    }

    #[Route('/actions', name: 'app_actions', methods: ['GET'])]
    public function actions(): Response
    {
        return $this->render('app/actions.html.twig');
    }

    #[Route('/assistant', name: 'app_assistant', methods: ['GET'])]
    public function assistant(): Response
    {
        return $this->render('app/assistant.html.twig');
    }
}
