<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Endpoint de santé pour la surveillance externe (uptime) — §21.
 *
 * Renvoie 200 { status: "ok" } quand l'application ET la base répondent,
 * 503 { status: "degraded" } si la base est injoignable. Ne divulgue aucun
 * détail interne (§14.4) : le message technique va dans les logs, pas dans la réponse.
 */
final class HealthController extends AbstractController
{
    #[Route('/health', name: 'health', methods: ['GET'])]
    public function index(Connection $connection, LoggerInterface $logger): JsonResponse
    {
        $database = 'ok';
        $status = 'ok';

        try {
            $connection->executeQuery('SELECT 1');
        } catch (\Throwable $e) {
            $database = 'ko';
            $status = 'degraded';
            $logger->error('Health check: base injoignable', ['exception' => $e]);
        }

        return new JsonResponse(
            ['status' => $status, 'database' => $database],
            'ok' === $status ? Response::HTTP_OK : Response::HTTP_SERVICE_UNAVAILABLE,
        );
    }
}
