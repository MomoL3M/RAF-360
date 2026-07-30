<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Entreprise;
use App\Repository\ActionRepository;
use App\Repository\AlerteEncaissementRepository;
use App\Repository\DocumentRepository;
use App\Repository\EcheanceRepository;
use App\Repository\FactureRepository;
use App\Repository\FluxTresorerieRepository;
use App\Repository\RendezVousRepository;

/**
 * Efface toutes les données métier d'une entreprise. Un seul endroit décrit CE QUI
 * appartient à une entreprise : la suppression de compte (§17.1) et le peuplement de
 * démonstration s'appuient tous deux dessus, pour qu'une nouvelle entité oubliée ici
 * ne soit pas oubliée à deux endroits.
 */
final readonly class EffacementDonneesEntreprise
{
    public function __construct(
        private RendezVousRepository $rendezVous,
        private EcheanceRepository $echeances,
        private FactureRepository $factures,
        private DocumentRepository $documents,
        private ActionRepository $actions,
        private FluxTresorerieRepository $flux,
        private AlerteEncaissementRepository $alertes,
    ) {
    }

    /**
     * Supprime les lignes rattachées à l'entreprise.
     * Les rendez-vous d'abord : ils référencent le catalogue de professionnels.
     */
    public function effacer(Entreprise $entreprise): void
    {
        $this->rendezVous->deleteForEntreprise($entreprise);
        $this->echeances->deleteForEntreprise($entreprise);
        $this->factures->deleteForEntreprise($entreprise);
        $this->documents->deleteForEntreprise($entreprise);
        $this->actions->deleteForEntreprise($entreprise);
        $this->flux->deleteForEntreprise($entreprise);
        $this->alertes->deleteForEntreprise($entreprise);
    }
}
