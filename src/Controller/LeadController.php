<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ContactRequest;
use App\Dto\DiagnosticRequest;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Formulaires publics de génération de leads : contact et diagnostic gratuit.
 * Chaque soumission est protégée par jeton CSRF, honeypot et limitation de débit (§13.1/§16).
 */
final class LeadController extends AbstractController
{
    #[Route('/contact', name: 'contact', methods: ['GET', 'POST'])]
    public function contact(Request $request, ValidatorInterface $validator, RateLimiterFactory $leadFormLimiter, LoggerInterface $logger): Response
    {
        $data = new ContactRequest();
        $errors = [];

        if ($request->isMethod('POST')) {
            if ($this->isSpam($request)) {
                return $this->acknowledge('contact');
            }
            $data->nom = trim((string) $request->request->get('nom', ''));
            $data->email = trim((string) $request->request->get('email', ''));
            $data->societe = trim((string) $request->request->get('societe', ''));
            $data->telephone = trim((string) $request->request->get('telephone', ''));
            $data->message = trim((string) $request->request->get('message', ''));

            $errors = $this->guardAndValidate($request, $validator, $leadFormLimiter, $data);
            if ([] === $errors) {
                $logger->info('Lead contact reçu', ['email' => $data->email, 'societe' => $data->societe]);

                return $this->acknowledge('contact');
            }
        }

        return $this->render('marketing/contact.html.twig', ['data' => $data, 'errors' => $errors]);
    }

    #[Route('/diagnostic', name: 'diagnostic', methods: ['GET', 'POST'])]
    public function diagnostic(Request $request, ValidatorInterface $validator, RateLimiterFactory $leadFormLimiter, LoggerInterface $logger): Response
    {
        $data = new DiagnosticRequest();
        $errors = [];

        if ($request->isMethod('POST')) {
            if ($this->isSpam($request)) {
                return $this->acknowledge('diagnostic');
            }
            $data->siren = preg_replace('/\D+/', '', (string) $request->request->get('siren', '')) ?? '';
            $data->email = trim((string) $request->request->get('email', ''));
            $data->siteWeb = trim((string) $request->request->get('siteWeb', ''));
            $data->typeActivite = trim((string) $request->request->get('typeActivite', ''));

            $errors = $this->guardAndValidate($request, $validator, $leadFormLimiter, $data);
            if ([] === $errors) {
                $logger->info('Diagnostic demandé', ['siren' => $data->siren]);

                return $this->acknowledge('diagnostic');
            }
        }

        return $this->render('marketing/diagnostic.html.twig', ['data' => $data, 'errors' => $errors]);
    }

    /**
     * Vérifie le jeton CSRF puis la limitation de débit, et valide le DTO.
     *
     * @return array<string, string> messages d'erreur par champ (clé `_global` pour les erreurs transverses)
     */
    private function guardAndValidate(Request $request, ValidatorInterface $validator, RateLimiterFactory $leadFormLimiter, object $data): array
    {
        if (!$this->isCsrfTokenValid('lead', (string) $request->request->get('_token'))) {
            return ['_global' => 'Votre session a expiré. Merci de renvoyer le formulaire.'];
        }

        if (!$leadFormLimiter->create($request->getClientIp() ?? 'anon')->consume(1)->isAccepted()) {
            return ['_global' => 'Trop de tentatives. Merci de réessayer dans quelques minutes.'];
        }

        $errors = [];
        foreach ($validator->validate($data) as $violation) {
            $field = $violation->getPropertyPath();
            $errors[$field] ??= (string) $violation->getMessage();
        }

        return $errors;
    }

    /** Renvoie une redirection (schéma POST-redirect-GET) avec un message de confirmation. */
    private function acknowledge(string $route): Response
    {
        $this->addFlash('success', 'Merci ! Votre demande a bien été enregistrée. Nous revenons vers vous très vite.');

        return $this->redirectToRoute($route);
    }

    /** Détecte un robot via le champ piège (honeypot) qui doit rester vide. */
    private function isSpam(Request $request): bool
    {
        return '' !== trim((string) $request->request->get('website', ''));
    }
}
