<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Suppression effective d'un compte à la demande de la personne (droit à l'effacement,
 * §17.1). « Effective » veut dire : les lignes disparaissent réellement de la base, il
 * n'y a pas de simple désactivation cachée.
 *
 * Règle de périmètre : les données métier appartiennent à l'ENTREPRISE, pas à la
 * personne. Elles ne sont donc effacées que si le compte supprimé était le dernier de
 * son entreprise — sinon les collègues perdraient leur comptabilité.
 */
final readonly class SuppressionCompte
{
    public function __construct(
        private EntityManagerInterface $em,
        private UtilisateurRepository $utilisateurs,
        private EffacementDonneesEntreprise $effacement,
        private LoggerInterface $logger,
    ) {
    }

    /** Indique si la suppression emportera aussi les données de l'entreprise. */
    public function emporteraLesDonneesEntreprise(Utilisateur $utilisateur): bool
    {
        $entreprise = $utilisateur->getEntreprise();

        return null !== $entreprise && 1 === $this->utilisateurs->compterPourEntreprise($entreprise);
    }

    /**
     * Supprime le compte, et l'entreprise avec ses données si c'était le dernier compte.
     * Le tout dans une transaction : on ne veut pas d'entreprise orpheline à moitié vidée.
     */
    public function supprimer(Utilisateur $utilisateur): void
    {
        $identifiant = $utilisateur->getId();
        $entreprise = $utilisateur->getEntreprise();
        $dernier = $this->emporteraLesDonneesEntreprise($utilisateur);

        $this->em->wrapInTransaction(function () use ($utilisateur, $entreprise, $dernier): void {
            $this->em->remove($utilisateur);
            $this->em->flush();

            if ($dernier && null !== $entreprise) {
                $this->effacement->effacer($entreprise);
                $this->em->remove($entreprise);
                $this->em->flush();
            }
        });

        // Traçabilité de l'opération sans réintroduire la donnée qu'on vient d'effacer (§2.6).
        $this->logger->info('Compte supprimé à la demande de la personne', [
            'compte_id' => $identifiant,
            'donnees_entreprise_effacees' => $dernier,
        ]);
    }
}
