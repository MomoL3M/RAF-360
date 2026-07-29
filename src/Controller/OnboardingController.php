<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use App\Enum\RegimeTva;
use App\Repository\EntrepriseRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Onboarding en 3 étapes (SIREN → régime de TVA → activité & site).
 * À la fin : l'entreprise est enregistrée et un accès express (sans mot de passe)
 * ouvre l'espace /app. La sécurisation par identifiants viendra avec l'inscription.
 */
final class OnboardingController extends AbstractController
{
    #[Route('/onboarding', name: 'onboarding', methods: ['GET', 'POST'])]
    public function onboarding(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        Security $security,
        EntrepriseRepository $entreprises,
        UtilisateurRepository $utilisateurs,
    ): Response {
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
                $entreprise = $entreprises->findOneBy(['siren' => $siren]) ?? new Entreprise();
                if (null === $entreprise->getId()) {
                    $entreprise->setRaisonSociale('Entreprise '.$siren);
                }
                $entreprise->setSiren($siren)
                    ->setRegimeTva($regime)
                    ->setSecteurActivite('' !== $secteur ? $secteur : null)
                    ->setSiteWeb('' !== $site ? $site : null);
                $em->persist($entreprise);

                $email = 'espace-'.$siren.'@demo.raf360.fr';
                $utilisateur = $utilisateurs->findOneBy(['email' => $email]);
                if (!$utilisateur instanceof Utilisateur) {
                    $utilisateur = new Utilisateur();
                    $utilisateur->setEmail($email)
                        ->setNom('Dirigeant')
                        ->setPrenom('Démo')
                        ->setRoles(['ROLE_USER'])
                        ->setPassword($hasher->hashPassword($utilisateur, bin2hex(random_bytes(16))));
                }
                $utilisateur->setEntreprise($entreprise);
                $em->persist($utilisateur);
                $em->flush();

                $security->login($utilisateur);

                return $this->redirectToRoute('app_dashboard');
            }
        }

        return $this->render('onboarding.html.twig', ['data' => $data, 'errors' => $errors]);
    }
}
