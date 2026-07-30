<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Action;
use App\Entity\Echeance;
use App\Enum\PrioriteAction;
use App\Enum\StatutEcheance;
use App\Enum\TypeMontant;

/**
 * Mise en forme des OBLIGATIONS de /app : échéances, actions et répartition des charges.
 * Les pièces (factures, documents) sont traitées par PieceViewFactory (§14.1, §2.3).
 */
final readonly class AppViewFactory
{
    /** Mois abrégés (aucune dépendance à l'extension intl). */
    private const array MOIS = ['', 'janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];

    // --- Échéances ---

    /**
     * @param Echeance[] $rows
     *
     * @return list<array<string, string|bool|null>>
     */
    public function echeances(array $rows): array
    {
        return array_map($this->echeanceEnVue(...), $rows);
    }

    /**
     * KPIs et compteurs de filtres des échéances.
     *
     * @param Echeance[] $rows
     *
     * @return array<string, int|string>
     */
    public function echeanceKpis(array $rows): array
    {
        $today = new \DateTimeImmutable('today');
        $dans30 = new \DateTimeImmutable('+30 days');
        $retard = $afaire = $avalider = $aVenir = $totalCents = $estimCents = 0;

        foreach ($rows as $echeance) {
            $over = $echeance->estEnRetard();
            if ($over) {
                ++$retard;
            } elseif (StatutEcheance::A_VALIDER === $echeance->getStatut()) {
                ++$avalider;
            } else {
                ++$afaire;
            }

            if (!$over && $echeance->getDateEcheance() >= $today && $echeance->getDateEcheance() <= $dans30) {
                ++$aVenir;
            }

            $cents = $echeance->getMontantCentimes() ?? 0;
            $totalCents += $cents;
            if (TypeMontant::ESTIMATIF === $echeance->getTypeMontant()) {
                $estimCents += $cents;
            }
        }

        return [
            'total' => \count($rows),
            'retard' => $retard,
            'afaire' => $afaire,
            'avalider' => $avalider,
            'aVenir30' => $aVenir,
            'montant' => intdiv($totalCents, 100),
            'estimatif' => $this->euros($estimCents),
        ];
    }

    /**
     * @return array<string, string|bool|null>
     */
    private function echeanceEnVue(Echeance $echeance): array
    {
        $date = $echeance->getDateEcheance();
        $overdue = $echeance->estEnRetard();
        $cents = $echeance->getMontantCentimes();
        $key = $overdue ? 'retard' : (StatutEcheance::A_VALIDER === $echeance->getStatut() ? 'avalider' : 'afaire');

        return [
            't' => $echeance->getLibelle(),
            'd' => $date->format('d'),
            'm' => self::MOIS[(int) $date->format('n')],
            'statut' => $overdue ? 'En retard' : $echeance->getStatut()->label(),
            'col' => $overdue ? 'red' : ('avalider' === $key ? 'gold' : 'slate'),
            'key' => $key,
            'montant' => null !== $cents ? $this->euros($cents) : null,
            'mt' => TypeMontant::ESTIMATIF === $echeance->getTypeMontant() ? 'estimatif' : 'réel',
            'overdue' => $overdue,
        ];
    }

    // --- Actions ---

    /**
     * @param Action[] $rows
     *
     * @return list<array<string, string>>
     */
    public function actions(array $rows): array
    {
        return array_map($this->actionEnVue(...), $rows);
    }

    /**
     * @param Action[] $rows
     *
     * @return array<string, int>
     */
    public function actionKpis(array $rows): array
    {
        $risque = $escalade = 0;
        foreach ($rows as $action) {
            if (PrioriteAction::HAUTE === $action->getPriorite()) {
                ++$risque;
            }
            if (StatutEcheance::ESCALADE === $action->getStatut()) {
                ++$escalade;
            }
        }

        return ['total' => \count($rows), 'risque' => $risque, 'escalade' => $escalade];
    }

    /**
     * @return array<string, string>
     */
    private function actionEnVue(Action $action): array
    {
        [$col, $stripe] = match ($action->getStatut()) {
            StatutEcheance::EN_RETARD, StatutEcheance::RISQUE => ['red', '#d64545'],
            StatutEcheance::A_VALIDER, StatutEcheance::A_CONFIRMER => ['gold', '#c9a227'],
            StatutEcheance::ESCALADE => ['navy', '#14306b'],
            default => ['slate', '#64748b'],
        };

        return [
            't' => $action->getLibelle(),
            'sub' => $action->getDescription() ?? $action->getPriorite()->label(),
            'statut' => $action->getStatut()->label(),
            'col' => $col,
            'stripe' => $stripe,
        ];
    }

    // --- Tableau de bord ---

    /**
     * Répartition des charges à provisionner, construite depuis les échéances CHIFFRÉES
     * (aucune catégorie inventée : chaque barre est une obligation réelle et son montant).
     * Triée par montant décroissant.
     *
     * @param Echeance[] $rows
     *
     * @return list<array{0: string, 1: int, 2: string}> [libellé, montant en euros, couleur]
     */
    public function chargesBars(array $rows, int $limite = 5): array
    {
        $couleurs = ['#14306b', '#2c6fb0', '#c9a227', '#1f9d6b', '#8fbbee'];

        $charges = [];
        foreach ($rows as $echeance) {
            $cents = $echeance->getMontantCentimes();
            if (null === $cents || 0 === $cents) {
                continue;
            }
            $charges[] = [$echeance->getLibelle(), intdiv($cents, 100)];
        }

        usort($charges, static fn (array $a, array $b): int => $b[1] <=> $a[1]);
        $charges = \array_slice($charges, 0, $limite);

        $out = [];
        foreach ($charges as $i => [$libelle, $euros]) {
            $out[] = [$libelle, $euros, $couleurs[$i % \count($couleurs)]];
        }

        return $out;
    }

    /** Formate un montant en centimes vers un libellé en euros. */
    public function euros(int $cents): string
    {
        return number_format($cents / 100, 0, ',', ' ').' €';
    }
}
