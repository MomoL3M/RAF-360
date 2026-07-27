<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\FluxTresorerie;
use PHPUnit\Framework\TestCase;

/**
 * Vérifie les règles de trésorerie re-exprimées depuis l'inventaire R2 §C.
 */
final class TresorerieTest extends TestCase
{
    public function testEcartEstSortiesMoinsEntrees(): void
    {
        $flux = (new FluxTresorerie())
            ->setEntreesCentimes(10_000_00)
            ->setSortiesCentimes(12_000_00);

        self::assertSame(2_000_00, $flux->ecartCentimes());
    }

    public function testSoldeSousSeuil(): void
    {
        $flux = new FluxTresorerie();

        $flux->setSoldeCentimes(FluxTresorerie::SEUIL_ALERTE_CENTIMES - 1);
        self::assertTrue($flux->estSousSeuil(), 'Sous le seuil de 40 k€.');

        $flux->setSoldeCentimes(FluxTresorerie::SEUIL_ALERTE_CENTIMES);
        self::assertFalse($flux->estSousSeuil(), 'Au seuil exact : plus en alerte.');
    }
}
