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
            'message' => $this->clip($payload['message'] ?? ''),
            'source' => $this->clip($payload['source'] ?? ''),
            'stack' => $this->clip($payload['stack'] ?? '', 1500),
            'url' => $this->clip($payload['url'] ?? ''),
            'ua' => $this->clip($request->headers->get('User-Agent') ?? ''),
        ]);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /** Tronque et normalise une valeur reçue du client (jamais réutilisée dans une réponse). */
    private function clip(mixed $value, int $max = self::MAX_LEN): string
    {
        return mb_substr(\is_scalar($value) ? (string) $value : '', 0, $max);
    }
}
