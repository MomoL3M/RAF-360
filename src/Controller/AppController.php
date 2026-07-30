<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Echeance;
use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use App\Enum\StatutEcheance;
use App\Enum\TypeMontant;
use App\Repository\EcheanceRepository;
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

    private function euros(int $cents): string
    {
        return number_format($cents / 100, 0, ',', ' ').' €';
    }
}
