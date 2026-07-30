<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Applique TECHNIQUEMENT les durées de conservation (§17.1) : une durée écrite dans la
 * politique de confidentialité mais jamais exécutée n'est qu'une promesse.
 *
 * Choix assumé : les comptes inactifs sont ANONYMISÉS, pas supprimés. L'anonymisation
 * rend la personne non identifiable (finalité RGPD atteinte) sans casser les références
 * de la base. La suppression des données comptables d'une entreprise, elle, dépend de la
 * fin du contrat : elle ne peut pas être déclenchée par un simple délai d'inactivité
 * (décision consignée dans docs/decisions.md).
 */
final readonly class PurgeDonnees
{
    /** Délai d'inactivité au-delà duquel un compte client est anonymisé (recommandation CNIL). */
    public const int MOIS_INACTIVITE = 36;

    /** Domaine réservé (RFC 2606) : une adresse anonymisée ne peut jamais être routée. */
    private const string DOMAINE_NEUTRE = 'supprime.raf360.invalid';

    public function __construct(
        private EntityManagerInterface $em,
        private UtilisateurRepository $utilisateurs,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * Anonymise les comptes clients sans activité depuis MOIS_INACTIVITE.
     * Les comptes d'administration sont exclus : les anonymiser fermerait la porte de
     * la plateforme sans que personne ne l'ait demandé.
     *
     * @return list<int> identifiants des comptes concernés
     */
    public function anonymiserComptesInactifs(bool $simulation = false, ?\DateTimeImmutable $maintenant = null): array
    {
        $limite = ($maintenant ?? new \DateTimeImmutable())
            ->sub(new \DateInterval('P'.self::MOIS_INACTIVITE.'M'));

        return $this->anonymiserLot($this->utilisateurs->findInactifsAvant($limite), $simulation);
    }

    /**
     * Applique la règle à un lot de comptes DÉJÀ sélectionnés. La décision (qui est
     * exclu, ce qui est effacé) vit ici, séparée de la requête : c'est ce qui la rend
     * testable sans base de données.
     *
     * @param Utilisateur[] $utilisateurs
     *
     * @return list<int>
     */
    public function anonymiserLot(array $utilisateurs, bool $simulation = false): array
    {
        $traites = [];
        foreach ($utilisateurs as $utilisateur) {
            if (\in_array('ROLE_ADMIN', $utilisateur->getRoles(), true)) {
                continue;
            }

            $traites[] = (int) $utilisateur->getId();
            if (!$simulation) {
                $this->anonymiser($utilisateur);
            }
        }

        if (!$simulation && [] !== $traites) {
            $this->em->flush();
            $this->logger->info('Purge : comptes inactifs anonymisés', [
                'nombre' => \count($traites),
                'seuil_mois' => self::MOIS_INACTIVITE,
            ]);
        }

        return $traites;
    }

    /**
     * Remplace les données identifiantes par des valeurs neutres et neutralise les
     * moyens d'authentification. L'horodatage rend l'opération idempotente.
     */
    private function anonymiser(Utilisateur $utilisateur): void
    {
        $utilisateur
            ->setEmail(\sprintf('anonyme-%d@%s', $utilisateur->getId(), self::DOMAINE_NEUTRE))
            ->setPrenom('Compte')
            ->setNom('anonymisé')
            ->setRoles([])
            // Empreinte aléatoire : aucun mot de passe ne peut plus correspondre.
            ->setPassword(bin2hex(random_bytes(32)))
            ->setTotpSecret(null)
            ->setAnonymiseLe(new \DateTimeImmutable());
    }
}
