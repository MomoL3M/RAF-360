<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Service\ExportDonneesPersonnelles;
use App\Service\SuppressionCompte;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Exercice des droits de la personne sur ses données : accès/portabilité (export) et
 * effacement (suppression du compte) — §17.1. Ces droits doivent être exerçables par
 * l'intéressé lui-même, sans passer par une demande par e-mail.
 *
 * Contrôleur fin : l'export et la suppression vivent dans des services (§14.1).
 */
final class AccountDataController extends AbstractController
{
    #[Route('/mon-compte/donnees', name: 'donnees_compte', methods: ['GET'])]
    public function index(SuppressionCompte $suppression): Response
    {
        return $this->render('security/donnees.html.twig', [
            'emporteEntreprise' => $suppression->emporteraLesDonneesEntreprise($this->utilisateur()),
        ]);
    }

    /** Télécharge l'ensemble des données du compte au format JSON (droit d'accès et portabilité). */
    #[Route('/mon-compte/donnees/export', name: 'donnees_export', methods: ['GET'])]
    public function export(ExportDonneesPersonnelles $export): JsonResponse
    {
        $reponse = new JsonResponse(
            $export->pour($this->utilisateur()),
            Response::HTTP_OK,
            [],
        );
        $reponse->setEncodingOptions(\JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES | \JSON_PRETTY_PRINT);
        $reponse->headers->set(
            'Content-Disposition',
            $reponse->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                \sprintf('raf360-mes-donnees-%s.json', (new \DateTimeImmutable())->format('Y-m-d')),
            ),
        );
        // Un export de données personnelles ne doit jamais être mis en cache par un proxy.
        $reponse->headers->set('Cache-Control', 'no-store, private');

        return $reponse;
    }

    /**
     * Supprime définitivement le compte. Action irréversible : elle exige le jeton CSRF,
     * le mot de passe en cours et une confirmation explicite (§5.4, §16.1).
     */
    #[Route('/mon-compte/donnees/supprimer', name: 'compte_supprimer', methods: ['POST'])]
    public function supprimer(
        Request $request,
        UserPasswordHasherInterface $hasher,
        SuppressionCompte $suppression,
        Security $security,
    ): Response {
        $utilisateur = $this->utilisateur();

        if (!$this->isCsrfTokenValid('compte_supprimer', (string) $request->request->get('_token'))) {
            $this->addFlash('error', 'Votre session a expiré. Merci de recommencer.');

            return $this->redirectToRoute('donnees_compte');
        }

        if ('SUPPRIMER' !== trim((string) $request->request->get('confirmation'))) {
            $this->addFlash('error', 'Saisissez SUPPRIMER en majuscules pour confirmer.');

            return $this->redirectToRoute('donnees_compte');
        }

        if (!$hasher->isPasswordValid($utilisateur, (string) $request->request->get('mot_de_passe'))) {
            $this->addFlash('error', 'Mot de passe incorrect.');

            return $this->redirectToRoute('donnees_compte');
        }

        $suppression->supprimer($utilisateur);
        // La session référence un compte qui n'existe plus : on la ferme immédiatement.
        $security->logout(false);
        $request->getSession()->invalidate();

        return $this->redirectToRoute('compte_supprime');
    }

    /** Page de sortie : le compte n'existe plus, l'utilisateur n'est plus authentifié. */
    #[Route('/compte-supprime', name: 'compte_supprime', methods: ['GET'])]
    public function confirme(): Response
    {
        return $this->render('security/compte_supprime.html.twig');
    }

    /** L'accès est déjà restreint par access_control ; ce garde-fou satisfait le typage. */
    private function utilisateur(): Utilisateur
    {
        $utilisateur = $this->getUser();
        if (!$utilisateur instanceof Utilisateur) {
            throw $this->createAccessDeniedException();
        }

        return $utilisateur;
    }
}
