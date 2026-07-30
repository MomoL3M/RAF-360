<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use App\Repository\ActionRepository;
use App\Repository\AlerteEncaissementRepository;
use App\Repository\DocumentRepository;
use App\Repository\EcheanceRepository;
use App\Repository\FactureRepository;
use App\Repository\FluxTresorerieRepository;
use App\Repository\RendezVousRepository;

/**
 * Construit l'export complet des données d'un utilisateur (droit d'accès et de
 * portabilité, §17.1). Le format est JSON, lisible par un humain comme par une machine.
 *
 * Ne contient JAMAIS le mot de passe haché ni le secret TOTP : ce sont des éléments
 * d'authentification, sans valeur pour la personne et dangereux dans un fichier
 * téléchargé (§2.1).
 */
final readonly class ExportDonneesPersonnelles
{
    public function __construct(
        private FactureRepository $factures,
        private EcheanceRepository $echeances,
        private DocumentRepository $documents,
        private ActionRepository $actions,
        private FluxTresorerieRepository $flux,
        private AlerteEncaissementRepository $encaissements,
        private RendezVousRepository $rendezVous,
    ) {
    }

    /**
     * @return array<string, mixed> structure sérialisable en JSON
     */
    public function pour(Utilisateur $utilisateur): array
    {
        $entreprise = $utilisateur->getEntreprise();

        return [
            'export' => [
                'genere_le' => (new \DateTimeImmutable())->format(\DATE_ATOM),
                'objet' => 'Données personnelles et données d\'entreprise associées au compte',
                'fondement' => 'RGPD, droit d\'accès (art. 15) et droit à la portabilité (art. 20)',
                'editeur' => 'Lindbergh Formation SAS — RAF360',
                'montants' => 'exprimés en centimes d\'euro, pour rester exacts',
            ],
            'compte' => $this->compte($utilisateur),
            'entreprise' => null === $entreprise ? null : $this->entreprise($entreprise),
            'donnees_metier' => null === $entreprise ? [] : $this->donneesMetier($entreprise),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function compte(Utilisateur $utilisateur): array
    {
        return [
            'email' => $utilisateur->getEmail(),
            'prenom' => $utilisateur->getPrenom(),
            'nom' => $utilisateur->getNom(),
            'roles' => $utilisateur->getRoles(),
            'cree_le' => $utilisateur->getCreeLe()->format(\DATE_ATOM),
            'derniere_connexion' => $utilisateur->getDerniereConnexion()?->format(\DATE_ATOM),
            'double_authentification_active' => $utilisateur->isTotpAuthenticationEnabled(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function entreprise(Entreprise $entreprise): array
    {
        return [
            'raison_sociale' => $entreprise->getRaisonSociale(),
            'siren' => $entreprise->getSiren(),
            'regime_tva' => $entreprise->getRegimeTva()?->value,
            'secteur_activite' => $entreprise->getSecteurActivite(),
            'site_web' => $entreprise->getSiteWeb(),
        ];
    }

    /**
     * @return array<string, list<array<string, mixed>>>
     */
    private function donneesMetier(Entreprise $entreprise): array
    {
        return [
            'factures' => array_map(static fn ($f) => [
                'numero' => $f->getNumero(),
                'tiers' => $f->getTiers(),
                'montant_centimes' => $f->getMontantCentimes(),
                'statut' => $f->getStatut()->value,
                'date_emission' => $f->getDateEmission()->format('Y-m-d'),
                'facture_electronique' => $f->isEFacture(),
            ], $this->factures->findForEntreprise($entreprise)),

            'echeances' => array_map(static fn ($e) => [
                'libelle' => $e->getLibelle(),
                'date_echeance' => $e->getDateEcheance()->format('Y-m-d'),
                'montant_centimes' => $e->getMontantCentimes(),
                'type_montant' => $e->getTypeMontant()?->value,
                'statut' => $e->getStatut()->value,
            ], $this->echeances->findForEntreprise($entreprise)),

            'documents' => array_map(static fn ($d) => [
                'nom' => $d->getNom(),
                'domaine' => $d->getDomaine()->value,
                'type' => $d->getTypeDocument(),
                'score_confiance' => $d->getScoreConfiance(),
                'date_depot' => $d->getDateDepot()->format('Y-m-d'),
            ], $this->documents->findForEntreprise($entreprise)),

            'actions' => array_map(static fn ($a) => [
                'libelle' => $a->getLibelle(),
                'description' => $a->getDescription(),
                'priorite' => $a->getPriorite()->value,
                'statut' => $a->getStatut()->value,
            ], $this->actions->findForEntreprise($entreprise)),

            'tresorerie' => array_map(static fn ($f) => [
                'mois' => $f->getMoisLabel(),
                'entrees_centimes' => $f->getEntreesCentimes(),
                'sorties_centimes' => $f->getSortiesCentimes(),
                'solde_centimes' => $f->getSoldeCentimes(),
                'realise' => $f->isRealise(),
            ], $this->flux->findSerieForEntreprise($entreprise)),

            'encaissements_attendus' => array_map(static fn ($a) => [
                'tiers' => $a->getTiers(),
                'montant_centimes' => $a->getMontantCentimes(),
                'statut' => $a->getStatut()->value,
            ], $this->encaissements->findForEntreprise($entreprise)),

            'rendez_vous' => array_map(static fn ($r) => [
                'professionnel' => $r->getProfessionnel()->getNom(),
                'domaine' => $r->getProfessionnel()->getDomaine()->value,
                'creneau' => $r->getCreneau()->format(\DATE_ATOM),
                'confirme' => $r->isConfirme(),
            ], $this->rendezVous->findForEntreprise($entreprise)),
        ];
    }
}
