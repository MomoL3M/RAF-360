<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Collecteur d'erreurs JavaScript de production (§4.5) : les erreurs front ne sont
 * jamais perdues silencieusement. Le beacon (assets/app.js) POSTe un petit JSON ici ;
 * l'erreur est journalisée (LoggerInterface -> Sentry côté serveur si DSN configuré).
 * Endpoint public → limité en débit et en taille, aucune donnée renvoyée.
 */
final class ClientErrorController extends AbstractController
{
    private const MAX_BODY = 4096;
    private const MAX_LEN = 500;

    #[Route('/log/client-error', name: 'client_error', methods: ['POST'])]
    public function report(Request $request, RateLimiterFactory $clientErrorLimiter, LoggerInterface $logger): Response
    {
        if (!$clientErrorLimiter->create($request->getClientIp() ?? 'anon')->consume(1)->isAccepted()) {
            return new JsonResponse(null, Response::HTTP_TOO_MANY_REQUESTS);
        }

        $raw = (string) $request->getContent();
        if ('' === $raw || \strlen($raw) > self::MAX_BODY) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        try {
            /** @var array<string, mixed> $payload */
            $payload = json_decode($raw, true, 4, \JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        $logger->error('Erreur JavaScript front', [
            'message' => $this->champ($payload, 'message'),
            'source' => $this->champ($payload, 'source'),
            'stack' => $this->champ($payload, 'stack', 1500),
            'url' => $this->champ($payload, 'url'),
            'ua' => mb_substr($request->headers->get('User-Agent') ?? '', 0, self::MAX_LEN),
        ]);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Lit un champ du corps JSON, le normalise en chaîne et le tronque.
     * Une valeur non scalaire (objet, tableau) est ignorée : rien n'est renvoyé au client.
     *
     * @param array<string, mixed> $payload corps JSON décodé (contenu non fiable, donc hétérogène)
     */
    private function champ(array $payload, string $cle, int $max = self::MAX_LEN): string
    {
        $valeur = $payload[$cle] ?? '';

        return mb_substr(\is_scalar($valeur) ? (string) $valeur : '', 0, $max);
    }
}
