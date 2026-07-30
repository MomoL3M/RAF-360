<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Action;
use App\Entity\AlerteEncaissement;
use App\Entity\Document;
use App\Entity\Echeance;
use App\Entity\Entreprise;
use App\Entity\Facture;
use App\Entity\FluxTresorerie;
use App\Entity\Professionnel;
use App\Entity\RendezVous;
use App\Entity\Utilisateur;
use App\Enum\DomaineDocument;
use App\Enum\DomaineProfessionnel;
use App\Enum\PrioriteAction;
use App\Enum\RegimeTva;
use App\Enum\StatutEcheance;
use App\Enum\StatutEncaissement;
use App\Enum\StatutFacture;
use App\Enum\TypeMontant;
use App\Repository\EntrepriseRepository;
use App\Repository\ProfessionnelRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Remplit l'entreprise d'un utilisateur de données de DÉMONSTRATION réalistes
 * (bac à sable, jamais du contenu public — §2.10), pour exploiter l'espace /app tant
 * qu'aucune source réelle (connecteur banque/compta ou saisie) n'est branchée.
 * Idempotent : purge les données de l'entreprise puis les réinsère.
 */
final readonly class DemoDataSeeder
{
    private const string SIREN_DEMO = '784671695';

    /** Mois en français (aucune dépendance à l'extension intl pour un libellé court). */
    private const array MOIS = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

    public function __construct(
        private EntityManagerInterface $em,
        private EntrepriseRepository $entreprises,
        private ProfessionnelRepository $professionnels,
        private EffacementDonneesEntreprise $effacement,
    ) {
    }

    /** Rattache (ou crée) l'entreprise de démonstration puis la remplit. */
    public function seed(Utilisateur $utilisateur): Entreprise
    {
        $entreprise = $utilisateur->getEntreprise()
            ?? $this->entreprises->findOneBy(['siren' => self::SIREN_DEMO])
            ?? $this->creerEntreprise();
        $utilisateur->setEntreprise($entreprise);
        $this->em->persist($entreprise);
        $this->em->flush(); // l'entreprise doit avoir un identifiant avant le DELETE par tenant

        $this->purger($entreprise);
        $this->semerEcheances($entreprise);
        $this->semerFactures($entreprise);
        $this->semerDocuments($entreprise);
        $this->semerActions($entreprise);
        $this->semerTresorerie($entreprise);
        $this->semerEncaissements($entreprise);
        $this->semerRendezVous($entreprise);
        $this->em->flush();

        return $entreprise;
    }

    private function creerEntreprise(): Entreprise
    {
        return (new Entreprise())
            ->setRaisonSociale('Entreprise de démonstration')
            ->setSiren(self::SIREN_DEMO)
            ->setRegimeTva(RegimeTva::REEL_NORMAL)
            ->setSecteurActivite('BTP')
            ->setSiteWeb(null);
    }

    private function purger(Entreprise $entreprise): void
    {
        // Même définition du « périmètre entreprise » que la suppression de compte RGPD.
        $this->effacement->effacer($entreprise);
    }

    private function semerEcheances(Entreprise $entreprise): void
    {
        $today = new \DateTimeImmutable('today');
        $lignes = [
            ['TVA — CA3 mensuelle', '-3 days', 1284000, TypeMontant::REEL, StatutEcheance::EN_RETARD],
            ['Cotisations URSSAF', '+5 days', 862000, TypeMontant::REEL, StatutEcheance::A_FAIRE],
            ['Acompte d\'IS', '+18 days', 450000, TypeMontant::ESTIMATIF, StatutEcheance::A_VALIDER],
            ['DSN mensuelle', '+22 days', null, null, StatutEcheance::A_FAIRE],
            ['Taxe d\'apprentissage', '+41 days', 128000, TypeMontant::ESTIMATIF, StatutEcheance::A_FAIRE],
            ['CFE — acompte', '+63 days', 96000, TypeMontant::REEL, StatutEcheance::A_FAIRE],
        ];
        foreach ($lignes as [$libelle, $delta, $montant, $type, $statut]) {
            $this->em->persist(
                (new Echeance())
                    ->setLibelle($libelle)
                    ->setDateEcheance($today->modify($delta))
                    ->setMontantCentimes($montant)
                    ->setTypeMontant($type)
                    ->setStatut($statut)
                    ->setEntreprise($entreprise)
            );
        }
    }

    private function semerFactures(Entreprise $entreprise): void
    {
        $today = new \DateTimeImmutable('today');
        $lignes = [
            ['FAC-2026-0142', 'Béton Express SARL', 588000, StatutFacture::A_TRAITER, '-2 days', true],
            ['FAC-2026-0141', 'Loca-Engins SAS', 216000, StatutFacture::VALIDEE, '-9 days', true],
            ['FAC-2026-0140', 'Matériaux du Sud', 134500, StatutFacture::A_TRAITER, '-12 days', false],
            ['FAC-2026-0139', 'Béton Express SARL', 588000, StatutFacture::DOUBLON, '-12 days', true],
            ['FAC-2026-0138', 'Transports Rhône', 78200, StatutFacture::VALIDEE, '-20 days', false],
        ];
        foreach ($lignes as [$numero, $tiers, $montant, $statut, $delta, $eFacture]) {
            $this->em->persist(
                (new Facture())
                    ->setNumero($numero)
                    ->setTiers($tiers)
                    ->setMontantCentimes($montant)
                    ->setStatut($statut)
                    ->setDateEmission($today->modify($delta))
                    ->setEFacture($eFacture)
                    ->setEntreprise($entreprise)
            );
        }
    }

    private function semerDocuments(Entreprise $entreprise): void
    {
        $today = new \DateTimeImmutable('today');
        $lignes = [
            ['Statuts_2024.pdf', DomaineDocument::CORPORATE, 'Statuts', 98, '-40 days'],
            ['Kbis_juin.pdf', DomaineDocument::CORPORATE, 'Extrait Kbis', 95, '-15 days'],
            ['Bail_commercial.pdf', DomaineDocument::BUSINESS, 'Bail', 88, '-30 days'],
            ['Facture_fournisseur_scan.jpg', DomaineDocument::BUSINESS, 'Facture', 72, '-3 days'],
            ['DSN_avril.pdf', DomaineDocument::RH, 'DSN', 91, '-8 days'],
            ['Contrat_CDI_Martin.pdf', DomaineDocument::RH, 'Contrat de travail', null, '-1 days'],
        ];
        foreach ($lignes as [$nom, $domaine, $type, $score, $delta]) {
            $this->em->persist(
                (new Document())
                    ->setNom($nom)
                    ->setDomaine($domaine)
                    ->setTypeDocument($type)
                    ->setScoreConfiance($score)
                    ->setDateDepot($today->modify($delta))
                    ->setEntreprise($entreprise)
            );
        }
    }

    private function semerActions(Entreprise $entreprise): void
    {
        $lignes = [
            ['Valider la TVA du mois avant échéance', 'Contrôle des écritures puis télédéclaration.', PrioriteAction::HAUTE, StatutEcheance::A_VALIDER],
            ['Régulariser la facture en retard', 'Facture FAC-2026-0142 non réglée à échéance.', PrioriteAction::HAUTE, StatutEcheance::EN_RETARD],
            ['Vérifier un possible doublon de facture', 'FAC-2026-0139 identique à FAC-2026-0142.', PrioriteAction::MOYENNE, StatutEcheance::A_CONFIRMER],
            ['Faire relire une clause contractuelle', 'Escalade vers un avocat partenaire.', PrioriteAction::MOYENNE, StatutEcheance::ESCALADE],
            ['Compléter le dossier RH', 'Contrat CDI Martin à faire signer.', PrioriteAction::BASSE, StatutEcheance::A_FAIRE],
        ];
        foreach ($lignes as [$libelle, $description, $priorite, $statut]) {
            $this->em->persist(
                (new Action())
                    ->setLibelle($libelle)
                    ->setDescription($description)
                    ->setPriorite($priorite)
                    ->setStatut($statut)
                    ->setEntreprise($entreprise)
            );
        }
    }

    /**
     * Série de trésorerie sur 12 mois : 8 mois réalisés puis 4 prévisionnels,
     * trajectoire en hausse (58 k€ -> 287 k€), conformément à la spécification retenue.
     */
    private function semerTresorerie(Entreprise $entreprise): void
    {
        $depart = (new \DateTimeImmutable('first day of this month'))->modify('-7 months');
        // [entrées, sorties, solde] en centimes.
        $serie = [
            [8_240_000, 7_160_000, 5_800_000],
            [8_610_000, 7_390_000, 7_020_000],
            [9_050_000, 7_480_000, 8_590_000],
            [9_320_000, 8_010_000, 9_900_000],
            [9_870_000, 8_120_000, 11_650_000],
            [10_240_000, 8_460_000, 13_430_000],
            [10_780_000, 8_640_000, 15_570_000],
            [11_120_000, 8_910_000, 17_780_000],
            [11_460_000, 9_050_000, 20_190_000],
            [11_830_000, 9_240_000, 22_780_000],
            [12_240_000, 9_380_000, 25_640_000],
            [12_610_000, 9_520_000, 28_730_000],
        ];

        foreach ($serie as $position => [$entrees, $sorties, $solde]) {
            $mois = $depart->modify(\sprintf('+%d months', $position));
            $this->em->persist(
                (new FluxTresorerie())
                    ->setMoisLabel(self::MOIS[(int) $mois->format('n')].' '.$mois->format('Y'))
                    ->setPosition($position)
                    ->setEntreesCentimes($entrees)
                    ->setSortiesCentimes($sorties)
                    ->setSoldeCentimes($solde)
                    ->setRealise($position < 8)
                    ->setEntreprise($entreprise)
            );
        }
    }

    private function semerEncaissements(Entreprise $entreprise): void
    {
        $lignes = [
            ['Chantier Rive Gauche', 2_450_000, StatutEncaissement::EN_RETARD],
            ['Mairie de Longpont', 1_820_000, StatutEncaissement::ATTENDU],
            ['SCI Les Tilleuls', 960_000, StatutEncaissement::ATTENDU],
        ];
        foreach ($lignes as [$tiers, $montant, $statut]) {
            $this->em->persist(
                (new AlerteEncaissement())
                    ->setTiers($tiers)
                    ->setMontantCentimes($montant)
                    ->setStatut($statut)
                    ->setEntreprise($entreprise)
            );
        }
    }

    /**
     * Catalogue de professionnels (PARTAGÉ, non cloisonné) + un rendez-vous de l'entreprise.
     *
     * Garde-fou §2.10 / fiche projet : le réseau n'étant pas encore opérationnel (SPE non
     * activée), AUCUN nom de personne ni de cabinet n'est inventé — seuls des PROFILS de
     * compétences mobilisables sont décrits, et l'interface le formule au conditionnel.
     */
    private function semerRendezVous(Entreprise $entreprise): void
    {
        $profils = [
            ['Expert-comptable partenaire', DomaineProfessionnel::COMPTABLE, 'Bilan, liasse fiscale, révision', 'Sous 72 h'],
            ['Avocat en droit des affaires', DomaineProfessionnel::JURIDIQUE, 'Contrats, baux, litiges commerciaux', 'Sous 5 jours'],
            ['Conseil fiscal', DomaineProfessionnel::FISCAL, 'TVA, contrôle fiscal, sécurisation', 'Sous 72 h'],
            ['Gestionnaire de paie', DomaineProfessionnel::SOCIAL, 'DSN, bulletins, URSSAF', 'Sous 48 h'],
            ['Direction financière externalisée', DomaineProfessionnel::DAF, 'Pilotage, prévisionnel, financement', 'Sur demande'],
        ];

        $premier = null;
        foreach ($profils as [$nom, $domaine, $specialite, $delai]) {
            $pro = $this->professionnels->findOneBy(['nom' => $nom]) ?? new Professionnel();
            $pro->setNom($nom)
                ->setCabinet(null)
                ->setDomaine($domaine)
                ->setSpecialite($specialite)
                ->setDelaiIndicatif($delai);
            $this->em->persist($pro);
            $premier ??= $pro;
        }

        $this->em->persist(
            (new RendezVous())
                ->setProfessionnel($premier)
                ->setCreneau(new \DateTimeImmutable('tomorrow 10:00'))
                ->setConfirme(true)
                ->setEntreprise($entreprise)
        );
    }
}
