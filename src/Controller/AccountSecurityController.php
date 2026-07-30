<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Activation de la double authentification TOTP (§16.1). L'utilisateur scanne le QR code
 * avec son application (Google Authenticator, Authy…) puis confirme un code pour l'activer.
 * Obligatoire pour les administrateurs (cf. Enforce2faSubscriber).
 */
final class AccountSecurityController extends AbstractController
{
    private const SESSION_KEY = 'pending_totp_secret';

    #[Route('/mon-compte/securite', name: 'securite_compte', methods: ['GET', 'POST'])]
    public function twoFactor(Request $request, TotpAuthenticatorInterface $totp, EntityManagerInterface $em): Response
    {
        $utilisateur = $this->getUser();
        if (!$utilisateur instanceof Utilisateur) {
            throw $this->createAccessDeniedException();
        }

        if ($utilisateur->isTotpAuthenticationEnabled()) {
            return $this->render('security/securite.html.twig', ['active' => true]);
        }

        // Secret provisoire conservé en session tant que l'activation n'est pas confirmée.
        $session = $request->getSession();
        $secret = (string) $session->get(self::SESSION_KEY, '');
        if ('' === $secret) {
            $secret = $totp->generateSecret();
            $session->set(self::SESSION_KEY, $secret);
        }
        // Appliqué en mémoire (non persisté) pour construire le QR et vérifier le code.
        $utilisateur->setTotpSecret($secret);

        $error = null;
        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid('securite_2fa', (string) $request->request->get('_token'))) {
                $error = 'Votre session a expiré. Merci de réessayer.';
            } elseif ($totp->checkCode($utilisateur, trim((string) $request->request->get('_auth_code', '')))) {
                $em->flush(); // le secret (déjà positionné) est enregistré → 2FA active
                $session->remove(self::SESSION_KEY);
                $this->addFlash('success', 'La double authentification est activée.');

                return $this->redirectToRoute('app_dashboard');
            } else {
                $error = 'Code incorrect. Vérifiez l\'heure de votre téléphone et réessayez.';
            }
        }

        $qrDataUri = (new PngWriter())
            ->write(new QrCode(data: $totp->getQRContent($utilisateur), size: 220, margin: 8))
            ->getDataUri();

        return $this->render('security/securite.html.twig', [
            'active' => false,
            'qrCode' => $qrDataUri,
            'secret' => $secret,
            'error' => $error,
        ]);
    }
}
