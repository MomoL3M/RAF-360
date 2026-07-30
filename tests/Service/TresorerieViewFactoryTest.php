<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Entreprise;
use App\Entity\FluxTresorerie;
use App\Service\TresorerieViewFactory;
use PHPUnit\Framework\TestCase;

/**
 * Vérifie les calculs de la vue trésorerie (série, indicateurs, tendance).
 */
final class TresorerieViewFactoryTest extends TestCase
{
    public function testSerieExposeLesMontantsEnMilliersEtLIndiceDuDernierMoisRealise(): void
    {
        $serie = (new TresorerieViewFactory())->serie([
            $this->point('janvier 2026', 0, 5_000_000, 4_000_000, 8_000_000, true),
            $this->point('février 2026', 1, 6_000_000, 4_500_000, 12_000_000, true),
            $this->point('mars 2026', 2, 6_500_000, 5_000_000, 15_000_000, false),
        ]);

        self::assertSame([80, 120, 150], $serie['sol'], 'les soldes sont exprimés en k€');
        self::assertSame([50, 60, 65], $serie['enc']);
        self::assertSame(1, $serie['realIdx'], 'le dernier mois réalisé est le deuxième point');
        self::assertSame(['Jan', 'Fév', 'Mar'], $serie['lab']);
        self::assertSame(['26', '26', '26'], $serie['yr']);
    }

    public function testKpisPrennentLeDernierMoisRealiseEtCalculentLaTendance(): void
    {
        $kpis = (new TresorerieViewFactory())->kpis([
            $this->point('janvier 2026', 0, 5_000_000, 4_000_000, 8_000_000, true),
            $this->point('février 2026', 1, 6_000_000, 4_500_000, 12_000_000, true),
            $this->point('mars 2026', 2, 6_500_000, 5_000_000, 16_000_000, false),
        ]);

        self::assertSame(120_000, $kpis['solde'], 'le solde retenu est celui du dernier mois réalisé');
        self::assertSame('février 2026', $kpis['mois']);
        self::assertSame(60_000, $kpis['encaissements']);
        self::assertSame(100, $kpis['tendance'], 'le solde double du premier au dernier point');
        self::assertFalse($kpis['sousSeuil'], '120 000 € dépasse le seuil d\'alerte de 40 000 €');
    }

    public function testSerieVideNeCasseRien(): void
    {
        $kpis = (new TresorerieViewFactory())->kpis([]);

        self::assertSame(0, $kpis['solde']);
        self::assertSame('—', $kpis['mois']);
        self::assertSame(0, $kpis['tendance']);
    }

    public function testSoldeSousLeSeuilEstSignale(): void
    {
        $kpis = (new TresorerieViewFactory())->kpis([
            $this->point('janvier 2026', 0, 1_000_000, 900_000, 3_000_000, true),
        ]);

        self::assertTrue($kpis['sousSeuil'], '30 000 € est sous le seuil de 40 000 €');
    }

    private function point(string $label, int $position, int $entrees, int $sorties, int $solde, bool $realise): FluxTresorerie
    {
        return (new FluxTresorerie())
            ->setMoisLabel($label)
            ->setPosition($position)
            ->setEntreesCentimes($entrees)
            ->setSortiesCentimes($sorties)
            ->setSoldeCentimes($solde)
            ->setRealise($realise)
            ->setEntreprise(new Entreprise());
    }
}
