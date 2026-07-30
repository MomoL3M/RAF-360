<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Action;
use App\Entity\Document;
use App\Entity\Echeance;
use App\Entity\Entreprise;
use App\Entity\Facture;
use App\Enum\DomaineDocument;
use App\Enum\PrioriteAction;
use App\Enum\RegimeTva;
use App\Enum\StatutEcheance;
use App\Enum\StatutFacture;
use App\Enum\TypeMontant;
use App\Repository\EntrepriseRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Alimente l'entreprise d'un utilisateur avec des données de DÉMONSTRATION réalistes
 * (bac à sable — pas du contenu public, cf. §2.10), pour visualiser l'espace /app tant
 * qu'aucune source réelle (connecteur banque/compta ou saisie) n'est branchée.
 * Idempotent : purge puis réinsère les données de l'entreprise.
 */
#[AsCommand(name: 'app:demo-data', description: 'Remplit l\'entreprise d\'un utilisateur de données de démonstration')]
final class DemoDataCommand extends Command
{
    private const string SIREN_DEMO = '784671695';

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UtilisateurRepository $utilisateurs,
        private readonly EntrepriseRepository $entreprises,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'E-mail de l\'utilisateur dont on peuple l\'entreprise');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = mb_strtolower(trim((string) $input->getArgument('email')));

        $utilisateur = $this->utilisateurs->findOneBy(['email' => $email]);
        if (null === $utilisateur) {
            $io->error(\sprintf('Aucun utilisateur avec l\'adresse « %s ».', $email));

            return Command::FAILURE;
        }

        $entreprise = $utilisateur->getEntreprise()
            ?? $this->entreprises->findOneBy(['siren' => self::SIREN_DEMO])
            ?? $this->creerEntreprise();
        $utilisateur->setEntreprise($entreprise);
        $this->em->persist($entreprise);
        $this->em->flush(); // l'entreprise doit avoir un identifiant avant le DELETE par tenant

        $this->purger($entreprise);
        $this->semer($entreprise);
        $this->em->flush();

        $io->success(\sprintf('Données de démonstration chargées pour « %s » (entreprise : %s).', $email, $entreprise->getRaisonSociale()));

        return Command::SUCCESS;
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
        foreach ([Echeance::class, Facture::class, Document::class, Action::class] as $classe) {
            $this->em->createQuery(\sprintf('DELETE FROM %s e WHERE e.entreprise = :ent', $classe))
                ->setParameter('ent', $entreprise)
                ->execute();
        }
    }

    private function semer(Entreprise $entreprise): void
    {
        $this->semerEcheances($entreprise);
        $this->semerFactures($entreprise);
        $this->semerDocuments($entreprise);
        $this->semerActions($entreprise);
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
}
