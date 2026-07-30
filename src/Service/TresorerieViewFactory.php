<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\AlerteEncaissement;
use App\Entity\FluxTresorerie;
use App\Enum\StatutEncaissement;

/**
 * Prépare les séries et indicateurs de trésorerie pour la vue (§14.1).
 * Les montants sont stockés en centimes et exposés en milliers d'euros (k€) pour le
 * graphique, en euros pour les indicateurs.
 */
final readonly class TresorerieViewFactory
{
    /**
     * Série du graphique : libellés, années, solde/encaissements/décaissements en k€,
     * et l'indice du dernier mois réalisé.
     *
     * @param FluxTresorerie[] $flux série ordonnée par position croissante
     *
     * @return array{lab: list<string>, yr: list<string>, sol: list<int>, enc: list<int>, dec: list<int>, realIdx: int, mois: list<string>}
     */
    public function serie(array $flux): array
    {
        $lab = $yr = $sol = $enc = $dec = $mois = [];
        $realIdx = 0;

        foreach ($flux as $i => $point) {
            $label = $point->getMoisLabel();
            [$nomMois, $annee] = $this->decouperLabel($label);
            $lab[] = $nomMois;
            $yr[] = $annee;
            $mois[] = $label;
            $sol[] = $this->enMilliers($point->getSoldeCentimes());
            $enc[] = $this->enMilliers($point->getEntreesCentimes());
            $dec[] = $this->enMilliers($point->getSortiesCentimes());
            if ($point->isRealise()) {
                $realIdx = $i;
            }
        }

        return ['lab' => $lab, 'yr' => $yr, 'sol' => $sol, 'enc' => $enc, 'dec' => $dec, 'realIdx' => $realIdx, 'mois' => $mois];
    }

    /**
     * Indicateurs de tête : dernier solde réalisé, encaissements et décaissements du mois.
     *
     * @param FluxTresorerie[] $flux
     *
     * @return array<string, int|string|bool>
     */
    public function kpis(array $flux): array
    {
        $dernier = null;
        foreach ($flux as $point) {
            if ($point->isRealise()) {
                $dernier = $point;
            }
        }
        $dernier ??= ([] !== $flux ? end($flux) : null);

        if (!$dernier instanceof FluxTresorerie) {
            return ['solde' => 0, 'encaissements' => 0, 'decaissements' => 0, 'mois' => '—', 'sousSeuil' => false, 'tendance' => 0];
        }

        return [
            'solde' => intdiv($dernier->getSoldeCentimes(), 100),
            'encaissements' => intdiv($dernier->getEntreesCentimes(), 100),
            'decaissements' => intdiv($dernier->getSortiesCentimes(), 100),
            'mois' => $dernier->getMoisLabel(),
            'sousSeuil' => $dernier->estSousSeuil(),
            'tendance' => $this->tendancePct($flux),
        ];
    }

    /**
     * Encaissements attendus, prêts à l'affichage.
     *
     * @param AlerteEncaissement[] $alertes
     *
     * @return list<array<string, string|bool>>
     */
    public function encaissements(array $alertes): array
    {
        return array_map(
            static fn (AlerteEncaissement $a): array => [
                'tiers' => $a->getTiers(),
                'montant' => number_format($a->getMontantCentimes() / 100, 0, ',', ' ').' €',
                'statut' => $a->getStatut()->label(),
                'retard' => StatutEncaissement::EN_RETARD === $a->getStatut(),
            ],
            $alertes,
        );
    }

    /**
     * Total attendu des encaissements, en euros.
     *
     * @param AlerteEncaissement[] $alertes
     */
    public function totalAttendu(array $alertes): int
    {
        $cents = 0;
        foreach ($alertes as $alerte) {
            $cents += $alerte->getMontantCentimes();
        }

        return intdiv($cents, 100);
    }

    /**
     * Évolution du solde entre le premier et le dernier point, en pourcentage.
     *
     * @param FluxTresorerie[] $flux
     */
    private function tendancePct(array $flux): int
    {
        if (\count($flux) < 2) {
            return 0;
        }
        $premier = reset($flux);
        $dernier = end($flux);
        $depart = $premier->getSoldeCentimes();
        if (0 === $depart) {
            return 0;
        }

        return (int) round(($dernier->getSoldeCentimes() - $depart) / abs($depart) * 100);
    }

    /**
     * Découpe « novembre 2025 » en [« Nov », « 25 »]. Retombe sur le libellé brut
     * si le format diffère (le libellé reste maîtrisé côté écriture).
     *
     * @return array{0: string, 1: string}
     */
    private function decouperLabel(string $label): array
    {
        $parties = explode(' ', trim($label));
        $annee = isset($parties[1]) ? mb_substr($parties[1], -2) : '';
        $nom = mb_substr($parties[0], 0, 3);

        return [mb_convert_case($nom, \MB_CASE_TITLE), $annee];
    }

    private function enMilliers(int $cents): int
    {
        return (int) round($cents / 100_000);
    }
}
