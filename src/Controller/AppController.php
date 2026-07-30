<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Professionnel;
use App\Entity\RendezVous;
use App\Entity\Utilisateur;
use App\Repository\ActionRepository;
use App\Repository\AlerteEncaissementRepository;
use App\Repository\DocumentRepository;
use App\Repository\EcheanceRepository;
use App\Repository\FactureRepository;
use App\Repository\FluxTresorerieRepository;
use App\Repository\ProfessionnelRepository;
use App\Repository\RendezVousRepository;
use App\Service\AppViewFactory;
use App\Service\PieceViewFactory;
use App\Service\TresorerieViewFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Espace applicatif connecté (`/app`) — non indexé (voir AppNoindexSubscriber).
 * Chaque écran ne lit QUE les données de l'entreprise de l'utilisateur connecté
 * (cloisonnement par tenant, §16.1). La mise en forme vit dans les services de vue (§14.1).
 */
#[Route('/app')]
#[IsGranted('ROLE_USER')]
final class AppController extends AbstractController
{
    public function __construct(
        private readonly AppViewFactory $vue,
        private readonly PieceViewFactory $vuePiece,
        private readonly TresorerieViewFactory $vueTreso,
    ) {
    }

    #[Route('', name: 'app_accueil', methods: ['GET'])]
    public function accueil(): Response
    {
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/dashboard', name: 'app_dashboard', methods: ['GET'])]
    public function dashboard(
        FluxTresorerieRepository $flux,
        EcheanceRepository $echeances,
        ActionRepository $actions,
        DocumentRepository $documents,
    ): Response {
        $entreprise = $this->entrepriseCourante();
        if (!$entreprise instanceof Entreprise) {
            return $this->redirectToRoute('onboarding');
        }

        $serieFlux = $flux->findSerieForEntreprise($entreprise);
        $lignesEcheances = $echeances->findForEntreprise($entreprise);
        $lignesActions = $actions->findForEntreprise($entreprise);

        return $this->render('app/dashboard.html.twig', [
            'entreprise' => $entreprise,
            'utilisateur' => $this->getUser(),
            'treso' => $this->vueTreso->kpis($serieFlux),
            'serie' => $this->vueTreso->serie($serieFlux),
            'echeanceKpis' => $this->vue->echeanceKpis($lignesEcheances),
            'echeances' => \array_slice($this->vue->echeances($lignesEcheances), 0, 4),
            'actions' => \array_slice($this->vue->actions($lignesActions), 0, 4),
            'actionKpis' => $this->vue->actionKpis($lignesActions),
            'documentKpis' => $this->vuePiece->documentKpis($documents->findForEntreprise($entreprise)),
            'charges' => $this->vue->chargesBars($lignesEcheances),
        ]);
    }

    #[Route('/treasury', name: 'app_treasury', methods: ['GET'])]
    public function treasury(FluxTresorerieRepository $flux, AlerteEncaissementRepository $alertes): Response
    {
        $entreprise = $this->entrepriseCourante();
        if (!$entreprise instanceof Entreprise) {
            return $this->redirectToRoute('onboarding');
        }

        $serie = $flux->findSerieForEntreprise($entreprise);
        $encaissements = $alertes->findForEntreprise($entreprise);

        return $this->render('app/treasury.html.twig', [
            'entreprise' => $entreprise,
            'utilisateur' => $this->getUser(),
            'serie' => $this->vueTreso->serie($serie),
            'kpis' => $this->vueTreso->kpis($serie),
            'encaissements' => $this->vueTreso->encaissements($encaissements),
            'totalAttendu' => $this->vueTreso->totalAttendu($encaissements),
        ]);
    }

    #[Route('/calendar', name: 'app_calendar', methods: ['GET'])]
    public function calendar(EcheanceRepository $echeances): Response
    {
        $entreprise = $this->entrepriseCourante();
        if (!$entreprise instanceof Entreprise) {
            return $this->redirectToRoute('onboarding');
        }

        $rows = $echeances->findForEntreprise($entreprise);

        return $this->render('app/echeances.html.twig', [
            'entreprise' => $entreprise,
            'utilisateur' => $this->getUser(),
            'echeances' => $this->vue->echeances($rows),
            'kpis' => $this->vue->echeanceKpis($rows),
        ]);
    }

    #[Route('/documents', name: 'app_documents', methods: ['GET'])]
    public function documents(DocumentRepository $documents): Response
    {
        $entreprise = $this->entrepriseCourante();
        if (!$entreprise instanceof Entreprise) {
            return $this->redirectToRoute('onboarding');
        }

        $rows = $documents->findForEntreprise($entreprise);

        return $this->render('app/documents.html.twig', [
            'entreprise' => $entreprise,
            'utilisateur' => $this->getUser(),
            'tree' => $this->vuePiece->documentsGroupes($rows),
            'kpis' => $this->vuePiece->documentKpis($rows),
        ]);
    }

    #[Route('/factures', name: 'app_factures', methods: ['GET'])]
    public function factures(FactureRepository $factures): Response
    {
        $entreprise = $this->entrepriseCourante();
        if (!$entreprise instanceof Entreprise) {
            return $this->redirectToRoute('onboarding');
        }

        $rows = $factures->findForEntreprise($entreprise);

        return $this->render('app/factures.html.twig', [
            'entreprise' => $entreprise,
            'utilisateur' => $this->getUser(),
            'factures' => $this->vuePiece->factures($rows),
            'kpis' => $this->vuePiece->factureKpis($rows),
        ]);
    }

    #[Route('/dataroom', name: 'app_dataroom', methods: ['GET'])]
    public function dataroom(ProfessionnelRepository $professionnels, RendezVousRepository $rendezVous): Response
    {
        $entreprise = $this->entrepriseCourante();
        if (!$entreprise instanceof Entreprise) {
            return $this->redirectToRoute('onboarding');
        }

        $catalogue = array_map(
            static fn (Professionnel $pro): array => [
                'domaine' => $pro->getDomaine()->label(),
                'nom' => $pro->getNom(),
                'specialite' => $pro->getSpecialite() ?? '',
                'delai' => $pro->getDelaiIndicatif() ?? '',
            ],
            $professionnels->findCatalogue(),
        );

        $rdv = array_map(
            static fn (RendezVous $r): array => [
                'pro' => $r->getProfessionnel()->getNom(),
                'creneau' => $r->getCreneau()->format('d/m/Y \à H\hi'),
                'confirme' => $r->isConfirme(),
            ],
            $rendezVous->findForEntreprise($entreprise),
        );

        return $this->render('app/dataroom.html.twig', [
            'entreprise' => $entreprise,
            'utilisateur' => $this->getUser(),
            'catalogue' => $catalogue,
            'rendezVous' => $rdv,
        ]);
    }

    #[Route('/actions', name: 'app_actions', methods: ['GET'])]
    public function actions(ActionRepository $actions): Response
    {
        $entreprise = $this->entrepriseCourante();
        if (!$entreprise instanceof Entreprise) {
            return $this->redirectToRoute('onboarding');
        }

        $rows = $actions->findForEntreprise($entreprise);

        return $this->render('app/actions.html.twig', [
            'entreprise' => $entreprise,
            'utilisateur' => $this->getUser(),
            'actions' => $this->vue->actions($rows),
            'kpis' => $this->vue->actionKpis($rows),
        ]);
    }

    #[Route('/assistant', name: 'app_assistant', methods: ['GET'])]
    public function assistant(EcheanceRepository $echeances, ActionRepository $actions): Response
    {
        $entreprise = $this->entrepriseCourante();
        if (!$entreprise instanceof Entreprise) {
            return $this->redirectToRoute('onboarding');
        }

        // Le copilote n'est pas encore branché sur un modèle (§18.3) : l'écran expose
        // les vrais indicateurs de l'entreprise et annonce l'assistance à venir.
        return $this->render('app/assistant.html.twig', [
            'entreprise' => $entreprise,
            'utilisateur' => $this->getUser(),
            'echeanceKpis' => $this->vue->echeanceKpis($echeances->findForEntreprise($entreprise)),
            'actionKpis' => $this->vue->actionKpis($actions->findForEntreprise($entreprise)),
        ]);
    }

    /** Entreprise de l'utilisateur connecté (null s'il n'a pas terminé l'onboarding). */
    private function entrepriseCourante(): ?Entreprise
    {
        $user = $this->getUser();

        return $user instanceof Utilisateur ? $user->getEntreprise() : null;
    }
}
