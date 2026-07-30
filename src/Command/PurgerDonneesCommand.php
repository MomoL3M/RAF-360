<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\PurgeDonnees;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Applique les durées de conservation (§17.1). Destinée à tourner en tâche planifiée
 * quotidienne — la supervision de ce cron fait partie de l'exploitation (§23.3), car une
 * purge qui échoue en silence est une non-conformité invisible.
 *
 * La logique vit dans PurgeDonnees (§14.1) ; cette commande ne fait que la piloter.
 */
#[AsCommand(name: 'app:purger-donnees', description: 'Applique les durées de conservation (anonymisation des comptes inactifs)')]
final class PurgerDonneesCommand extends Command
{
    public function __construct(private readonly PurgeDonnees $purge)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('simulation', null, InputOption::VALUE_NONE, 'Affiche ce qui serait purgé sans rien modifier');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $simulation = true === $input->getOption('simulation');

        $comptes = $this->purge->anonymiserComptesInactifs($simulation);

        $io->definitionList(
            ['Règle' => \sprintf('comptes clients sans activité depuis %d mois', PurgeDonnees::MOIS_INACTIVITE)],
            ['Mode' => $simulation ? 'simulation (aucune écriture)' : 'exécution'],
            ['Comptes concernés' => (string) \count($comptes)],
        );

        if ([] === $comptes) {
            $io->success('Aucun compte à anonymiser.');

            return Command::SUCCESS;
        }

        $io->success($simulation
            ? 'Simulation terminée — relancez sans --simulation pour appliquer.'
            : \sprintf('%d compte(s) anonymisé(s).', \count($comptes)));

        return Command::SUCCESS;
    }
}
