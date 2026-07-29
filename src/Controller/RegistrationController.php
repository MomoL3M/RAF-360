<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\RegistrationRequest;
use App\Service\AccountRegistrar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Inscription sécurisée (§16.1) : e-mail + mot de passe haché (Argon2id). Protégée par
 * jeton CSRF et limitation de débit. À la réussite, connexion puis redirection vers
 * l'onboarding (configuration de l'entreprise). Remplace l'ancien accès express sans mot de passe.
 */
final class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'inscription', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        ValidatorInterface $validator,
        RateLimiterFactory $registrationLimiter,
        AccountRegistrar $registrar,
        Security $security,
    ): Response {
        // Déjà connecté : pas de raison de s'inscrire à nouveau.
        if ($this->getUser()) {
            return $this->redirectToRoute('onboarding');
        }

        $data = new RegistrationRequest();
        $errors = [];

        if ($request->isMethod('POST')) {
            $data->prenom = trim((string) $request->request->get('prenom', ''));
            $data->nom = trim((string) $request->request->get('nom', ''));
            $data->email = trim((string) $request->request->get('email', ''));
            $data->plainPassword = (string) $request->request->get('plainPassword', '');
            $data->plainPasswordConfirm = (string) $request->request->get('plainPasswordConfirm', '');

            $errors = $this->guard($request, $registrationLimiter, $validator, $data);

            if ([] === $errors && $registrar->emailExists($data->email)) {
                $errors['email'] = 'Un compte existe déjà avec cette adresse. Connectez-vous.';
            }

            if ([] === $errors) {
                $utilisateur = $registrar->register($data->email, $data->plainPassword, $data->prenom, $data->nom);
                $security->login($utilisateur);

                return $this->redirectToRoute('onboarding');
            }
        }

        return $this->render('security/inscription.html.twig', ['data' => $data, 'errors' => $errors]);
    }

    /**
     * @return array<string, string> erreurs par champ (`_global` pour les erreurs transverses)
     */
    private function guard(Request $request, RateLimiterFactory $registrationLimiter, ValidatorInterface $validator, RegistrationRequest $data): array
    {
        if (!$this->isCsrfTokenValid('inscription', (string) $request->request->get('_token'))) {
            return ['_global' => 'Votre session a expiré. Merci de renvoyer le formulaire.'];
        }

        if (!$registrationLimiter->create($request->getClientIp() ?? 'anon')->consume(1)->isAccepted()) {
            return ['_global' => 'Trop de tentatives. Merci de réessayer dans quelques minutes.'];
        }

        $errors = [];
        foreach ($validator->validate($data) as $violation) {
            $field = $violation->getPropertyPath();
            $errors[$field] ??= (string) $violation->getMessage();
        }

        return $errors;
    }
}
