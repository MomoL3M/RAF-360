<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Document;
use App\Entity\Facture;
use App\Enum\DomaineDocument;
use App\Enum\StatutFacture;

/**
 * Mise en forme des PIÈCES pour les écrans /app : factures et documents.
 * Les obligations et actions sont traitées par AppViewFactory (§14.1, §2.3).
 */
final readonly class PieceViewFactory
{
    /** Mois abrégés (aucune dépendance à l'extension intl). */
    private const array MOIS = ['', 'janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];

    // --- Factures ---

    /**
     * @param Facture[] $rows
     *
     * @return list<array<string, string|bool>>
     */
    public function factures(array $rows): array
    {
        return array_map($this->factureEnVue(...), $rows);
    }

    /**
     * @param Facture[] $rows
     *
     * @return array<string, int>
     */
    public function factureKpis(array $rows): array
    {
        $aTraiter = $validees = $doublons = $efac = 0;
        foreach ($rows as $facture) {
            if (StatutFacture::A_TRAITER === $facture->getStatut()) {
                ++$aTraiter;
            } elseif (StatutFacture::VALIDEE === $facture->getStatut()) {
                ++$validees;
            } elseif (StatutFacture::DOUBLON === $facture->getStatut()) {
                ++$doublons;
            }
            if ($facture->isEFacture()) {
                ++$efac;
            }
        }

        return [
            'aTraiter' => $aTraiter,
            'validees' => $validees,
            'doublons' => $doublons,
            'efacturePct' => [] !== $rows ? (int) round($efac / \count($rows) * 100) : 0,
        ];
    }

    /**
     * @return array<string, string|bool>
     */
    private function factureEnVue(Facture $facture): array
    {
        $statut = $facture->getStatut();
        $col = match ($statut) {
            StatutFacture::VALIDEE => 'green',
            StatutFacture::DOUBLON => 'red',
            default => 'gold',
        };
        $date = $facture->getDateEmission();

        return [
            'numero' => $facture->getNumero(),
            'tiers' => $facture->getTiers(),
            'montant' => number_format($facture->getMontantCentimes() / 100, 0, ',', ' ').' €',
            'statut' => $statut->label(),
            'col' => $col,
            'date' => $date->format('d').' '.self::MOIS[(int) $date->format('n')],
            'efacture' => $facture->isEFacture(),
        ];
    }

    // --- Documents ---

    /**
     * Documents groupés par domaine (structure de l'arborescence). Groupes vides omis.
     *
     * @param Document[] $docs
     *
     * @return list<array{key: string, icon: string, label: string, files: list<array<string, string|int>>}>
     */
    public function documentsGroupes(array $docs): array
    {
        $groupes = [
            ['key' => 'corp', 'icon' => '🏛️', 'label' => 'Documents corporate', 'dom' => DomaineDocument::CORPORATE],
            ['key' => 'biz', 'icon' => '🤝', 'label' => 'Documents business', 'dom' => DomaineDocument::BUSINESS],
            ['key' => 'rh', 'icon' => '👥', 'label' => 'Documents RH', 'dom' => DomaineDocument::RH],
        ];

        $out = [];
        foreach ($groupes as $groupe) {
            $files = [];
            foreach ($docs as $doc) {
                if ($doc->getDomaine() !== $groupe['dom']) {
                    continue;
                }
                $date = $doc->getDateDepot();
                $files[] = [
                    'n' => $doc->getNom(),
                    'type' => $doc->getTypeDocument(),
                    'conf' => $doc->getScoreConfiance() ?? 0,
                    'date' => $date->format('d').' '.self::MOIS[(int) $date->format('n')],
                ];
            }
            if ([] !== $files) {
                $out[] = ['key' => $groupe['key'], 'icon' => $groupe['icon'], 'label' => $groupe['label'], 'files' => $files];
            }
        }

        return $out;
    }

    /**
     * @param Document[] $docs
     *
     * @return array<string, int>
     */
    public function documentKpis(array $docs): array
    {
        $scores = [];
        $aFiabiliser = 0;
        foreach ($docs as $doc) {
            $score = $doc->getScoreConfiance();
            if (null !== $score) {
                $scores[] = $score;
            }
            if (!$doc->estFiable()) {
                ++$aFiabiliser;
            }
        }

        return [
            'total' => \count($docs),
            'scoreMoyen' => [] !== $scores ? (int) round(array_sum($scores) / \count($scores)) : 0,
            'aFiabiliser' => $aFiabiliser,
        ];
    }
}
