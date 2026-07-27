<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Document;
use App\Entity\Echeance;
use App\Enum\DomaineDocument;
use PHPUnit\Framework\TestCase;

/**
 * Vérifie les règles métier re-exprimées depuis l'inventaire R2 (§24).
 */
final class RegleMetierTest extends TestCase
{
    public function testEcheanceEnRetardSiDatePassee(): void
    {
        $echeance = (new Echeance())
            ->setLibelle('TVA CA3')
            ->setDateEcheance(new \DateTimeImmutable('2020-01-01'));

        self::assertTrue($echeance->estEnRetard(new \DateTimeImmutable('2026-01-01')));
    }

    public function testEcheancePasEnRetardSiDateFuture(): void
    {
        $echeance = (new Echeance())
            ->setLibelle('TVA CA3')
            ->setDateEcheance(new \DateTimeImmutable('2099-01-01'));

        self::assertFalse($echeance->estEnRetard(new \DateTimeImmutable('2026-01-01')));
    }

    public function testDocumentFiableSelonSeuilOcr(): void
    {
        $document = (new Document())
            ->setNom('bilan.pdf')
            ->setDomaine(DomaineDocument::CORPORATE)
            ->setTypeDocument('bilan')
            ->setDateDepot(new \DateTimeImmutable('2026-01-01'));

        $document->setScoreConfiance(Document::SEUIL_FIABILITE);
        self::assertTrue($document->estFiable(), 'Fiable au seuil exact (85).');

        $document->setScoreConfiance(Document::SEUIL_FIABILITE - 1);
        self::assertFalse($document->estFiable(), 'Non fiable sous le seuil.');

        $document->setScoreConfiance(null);
        self::assertFalse($document->estFiable(), 'Non fiable si non analysé.');
    }
}
