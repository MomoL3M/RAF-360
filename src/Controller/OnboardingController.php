<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use App\Enum\RegimeTva;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Onboarding en 3 étapes (SIREN → régime de TVA → activité & site).
 * Réservé aux utilisateurs CONNECTÉS (cf. access_control ^/onboarding) : il configure
 * l'entreprise du compte, il ne crée plus de compte. L'inscription (mot de passe) se fait
 * en amont via /inscription (§16.1) — l'ancien accès express sans mot de passe est supprimé.
 */
final class OnboardingController extends AbstractController
{
    #[Route('/onboarding', name: 'onboarding', methods: ['GET', 'POST'])]
    public function onboarding(
        Request $request,
        EntityManagerInterface $em,
        EntrepriseRepository $entreprises,
    ): Response {
        $utilisateur = $this->getUser();
        if (!$utilisateur instanceof Utilisateur) {
            return $this->redirectToRoute('inscription');
        }

        $data = ['siren' => '', 'regimeTva' => '', 'secteurActivite' => '', 'siteWeb' => ''];
        $errors = [];

        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid('onboarding', (string) $request->request->get('_token'))) {
                $errors['_global'] = 'Votre session a expiré. Merci de recommencer.';

                return $this->render('onboarding.html.twig', ['data' => $data, 'errors' => $errors]);
            }

            $siren = preg_replace('/\D+/', '', (string) $request->request->get('siren', '')) ?? '';
            $regime = RegimeTva::tryFrom((string) $request->request->get('regimeTva', ''));
            $secteur = trim((string) $request->request->get('secteurActivite', ''));
            $site = trim((string) $request->request->get('siteWeb', ''));
            $data = ['siren' => $siren, 'regimeTva' => $regime instanceof RegimeTva ? $regime->value : '', 'secteurActivite' => $secteur, 'siteWeb' => $site];

            if (!preg_match('/^\d{9}$/', $siren)) {
                $errors['siren'] = 'Le SIREN doit comporter 9 chiffres.';
            }
            if (!$regime instanceof RegimeTva) {
                $errors['regimeTva'] = 'Sélectionnez votre régime de TVA.';
            }

            if ([] === $errors) {
                $entreprise = $utilisateur->getEntreprise()
                    ?? $entreprises->findOneBy(['siren' => $siren])
                    ?? new Entreprise();
                if (null === $entreprise->getId()) {
                    $entreprise->setRaisonSociale('Entreprise '.$siren);
                }
                $entreprise->setSiren($siren)
                    ->setRegimeTva($regime)
                    ->setSecteurActivite('' !== $secteur ? $secteur : null)
                    ->setSiteWeb('' !== $site ? $site : null);
                $em->persist($entreprise);

                $utilisateur->setEntreprise($entreprise);
                $em->flush();
                // Étape 8 de l'entonnoir (docs/plan-mesure.md), annoncée par le serveur.
                $this->addFlash('conversion', 'onboarding_termine');

                return $this->redirectToRoute('app_dashboard');
            }
        }

        return $this->render('onboarding.html.twig', ['data' => $data, 'errors' => $errors]);
    }
}
