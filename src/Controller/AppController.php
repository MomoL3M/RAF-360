<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Document;
use App\Entity\Echeance;
use App\Entity\Entreprise;
use App\Entity\Facture;
use App\Entity\Utilisateur;
use App\Enum\DomaineDocument;
use App\Enum\PrioriteAction;
use App\Enum\StatutEcheance;
use App\Enum\StatutFacture;
use App\Enum\TypeMontant;
use App\Repository\ActionRepository;
use App\Repository\DocumentRepository;
use App\Repository\EcheanceRepository;
use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Espace applicatif connecté (`/app`) — non indexé (voir AppNoindexSubscriber).
 * Les écrans branchés sur la base lisent UNIQUEMENT les données de l'entreprise de
 * l'utilisateur connecté (cloisonnement par tenant, §16.1). Les écrans non encore
 * branchés affichent des données de démonstration (en cours de bascule).
 */
#[Route('/app')]
#[IsGranted('ROLE_USER')]
final class AppController extends AbstractController
{
    private const array MOIS = ['', 'janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];

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
            'echeances' => array_map($this->echeanceEnVue(...), $rows),
            'kpis' => $this->echeanceKpis($rows),
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
            'tree' => $this->documentsGroupes($rows),
            'kpis' => $this->documentKpis($rows),
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
            'factures' => array_map($this->factureEnVue(...), $rows),
            'kpis' => $this->factureKpis($rows),
        ]);
    }

    #[Route('/dataroom', name: 'app_dataroom', methods: ['GET'])]
    public function dataroom(): Response
    {
        return $this->render('app/dataroom.html.twig');
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
            'actions' => array_map($this->actionEnVue(...), $rows),
            'kpis' => $this->actionKpis($rows),
        ]);
    }

    #[Route('/assistant', name: 'app_assistant', methods: ['GET'])]
    public function assistant(): Response
    {
        return $this->render('app/assistant.html.twig');
    }

    /** Entreprise de l'utilisateur connecté (null s'il n'a pas terminé l'onboarding). */
    private function entrepriseCourante(): ?Entreprise
    {
        $user = $this->getUser();

        return $user instanceof Utilisateur ? $user->getEntreprise() : null;
    }

    /**
     * Traduit une échéance en ligne d'affichage (forme attendue par le script de la vue).
     *
     * @return array<string, string|bool|null>
     */
    private function echeanceEnVue(Echeance $echeance): array
    {
        $date = $echeance->getDateEcheance();
        $overdue = $echeance->estEnRetard();
        $cents = $echeance->getMontantCentimes();
        $key = $overdue ? 'retard' : (StatutEcheance::A_VALIDER === $echeance->getStatut() ? 'avalider' : 'afaire');

        return [
            't' => $echeance->getLibelle(),
            'd' => $date->format('d'),
            'm' => self::MOIS[(int) $date->format('n')],
            'statut' => $overdue ? 'En retard' : $echeance->getStatut()->label(),
            'col' => $overdue ? 'red' : ('avalider' === $key ? 'gold' : 'slate'),
            'key' => $key,
            'montant' => null !== $cents ? $this->euros($cents) : null,
            'mt' => TypeMontant::ESTIMATIF === $echeance->getTypeMontant() ? 'estimatif' : 'réel',
            'overdue' => $overdue,
        ];
    }

    /**
     * KPIs et compteurs de filtres des échéances.
     *
     * @param Echeance[] $rows
     *
     * @return array<string, int|string>
     */
    private function echeanceKpis(array $rows): array
    {
        $today = new \DateTimeImmutable('today');
        $dans30 = new \DateTimeImmutable('+30 days');
        $retard = $afaire = $avalider = $aVenir = $totalCents = $estimCents = 0;

        foreach ($rows as $echeance) {
            $over = $echeance->estEnRetard();
            if ($over) {
                ++$retard;
            } elseif (StatutEcheance::A_VALIDER === $echeance->getStatut()) {
                ++$avalider;
            } else {
                ++$afaire;
            }

            if (!$over && $echeance->getDateEcheance() >= $today && $echeance->getDateEcheance() <= $dans30) {
                ++$aVenir;
            }

            $cents = $echeance->getMontantCentimes() ?? 0;
            $totalCents += $cents;
            if (TypeMontant::ESTIMATIF === $echeance->getTypeMontant()) {
                $estimCents += $cents;
            }
        }

        return [
            'total' => \count($rows),
            'retard' => $retard,
            'afaire' => $afaire,
            'avalider' => $avalider,
            'aVenir30' => $aVenir,
            'montant' => intdiv($totalCents, 100),
            'estimatif' => $this->euros($estimCents),
        ];
    }

    /**
     * @return array<string, string|bool>
     */
    private function factureEnVue(Facture $facture): array
    {
        $statut = $facture->getStatut();
        $col = match ($statut) {
            StatutFacture::VALIDEE => 'green',
            StatutFacture::DOUBLON => 'red',
            default => 'gold',
        };
        $date = $facture->getDateEmission();

        return [
            'numero' => $facture->getNumero(),
            'tiers' => $facture->getTiers(),
            'montant' => $this->euros($facture->getMontantCentimes()),
            'statut' => $statut->label(),
            'col' => $col,
            'date' => $date->format('d').' '.self::MOIS[(int) $date->format('n')],
            'efacture' => $facture->isEFacture(),
        ];
    }

    /**
     * @param Facture[] $rows
     *
     * @return array<string, int>
     */
    private function factureKpis(array $rows): array
    {
        $aTraiter = $validees = $doublons = $efac = 0;
        foreach ($rows as $facture) {
            if (StatutFacture::A_TRAITER === $facture->getStatut()) {
                ++$aTraiter;
            } elseif (StatutFacture::VALIDEE === $facture->getStatut()) {
                ++$validees;
            } elseif (StatutFacture::DOUBLON === $facture->getStatut()) {
                ++$doublons;
            }
            if ($facture->isEFacture()) {
                ++$efac;
            }
        }

        return [
            'aTraiter' => $aTraiter,
            'validees' => $validees,
            'doublons' => $doublons,
            'efacturePct' => [] !== $rows ? (int) round($efac / \count($rows) * 100) : 0,
        ];
    }

    /**
     * Documents groupés par domaine (structure attendue par l'arborescence de la vue).
     * Les groupes vides sont omis.
     *
     * @param Document[] $docs
     *
     * @return list<array{key: string, icon: string, label: string, files: list<array<string, string|int>>}>
     */
    private function documentsGroupes(array $docs): array
    {
        $groupes = [
            ['key' => 'corp', 'icon' => '🏛️', 'label' => 'Documents corporate', 'dom' => DomaineDocument::CORPORATE],
            ['key' => 'biz', 'icon' => '🤝', 'label' => 'Documents business', 'dom' => DomaineDocument::BUSINESS],
            ['key' => 'rh', 'icon' => '👥', 'label' => 'Documents RH', 'dom' => DomaineDocument::RH],
        ];

        $out = [];
        foreach ($groupes as $groupe) {
            $files = [];
            foreach ($docs as $doc) {
                if ($doc->getDomaine() !== $groupe['dom']) {
                    continue;
                }
                $date = $doc->getDateDepot();
                $files[] = [
                    'n' => $doc->getNom(),
                    'type' => $doc->getTypeDocument(),
                    'conf' => $doc->getScoreConfiance() ?? 0,
                    'date' => $date->format('d').' '.self::MOIS[(int) $date->format('n')],
                ];
            }
            if ([] !== $files) {
                $out[] = ['key' => $groupe['key'], 'icon' => $groupe['icon'], 'label' => $groupe['label'], 'files' => $files];
            }
        }

        return $out;
    }

    /**
     * @param Document[] $docs
     *
     * @return array<string, int>
     */
    private function documentKpis(array $docs): array
    {
        $scores = [];
        $aFiabiliser = 0;
        foreach ($docs as $doc) {
            $score = $doc->getScoreConfiance();
            if (null !== $score) {
                $scores[] = $score;
            }
            if (!$doc->estFiable()) {
                ++$aFiabiliser;
            }
        }

        return [
            'total' => \count($docs),
            'scoreMoyen' => [] !== $scores ? (int) round(array_sum($scores) / \count($scores)) : 0,
            'aFiabiliser' => $aFiabiliser,
        ];
    }

    /**
     * @return array<string, string>
     */
    private function actionEnVue(Action $action): array
    {
        [$col, $stripe] = match ($action->getStatut()) {
            StatutEcheance::EN_RETARD, StatutEcheance::RISQUE => ['red', '#d64545'],
            StatutEcheance::A_VALIDER, StatutEcheance::A_CONFIRMER => ['gold', '#c9a227'],
            StatutEcheance::ESCALADE => ['navy', '#14306b'],
            default => ['slate', '#64748b'],
        };

        return [
            't' => $action->getLibelle(),
            'sub' => $action->getDescription() ?? $action->getPriorite()->label(),
            'statut' => $action->getStatut()->label(),
            'col' => $col,
            'stripe' => $stripe,
        ];
    }

    /**
     * @param Action[] $rows
     *
     * @return array<string, int>
     */
    private function actionKpis(array $rows): array
    {
        $risque = $escalade = 0;
        foreach ($rows as $action) {
            if (PrioriteAction::HAUTE === $action->getPriorite()) {
                ++$risque;
            }
            if (StatutEcheance::ESCALADE === $action->getStatut()) {
                ++$escalade;
            }
        }

        return ['total' => \count($rows), 'risque' => $risque, 'escalade' => $escalade];
    }

    private function euros(int $cents): string
    {
        return number_format($cents / 100, 0, ',', ' ').' €';
    }
}
