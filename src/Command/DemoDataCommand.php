<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\UtilisateurRepository;
use App\Service\DemoDataSeeder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Remplit l'entreprise d'un utilisateur de données de démonstration (bac à sable).
 * La logique de peuplement vit dans DemoDataSeeder (§14.1).
 */
#[AsCommand(name: 'app:demo-data', description: 'Remplit l\'entreprise d\'un utilisateur de données de démonstration')]
final class DemoDataCommand extends Command
{
    public function __construct(
        private readonly UtilisateurRepository $utilisateurs,
        private readonly DemoDataSeeder $seeder,
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

        $entreprise = $this->seeder->seed($utilisateur);

        $io->success(\sprintf('Données de démonstration chargées pour « %s » (entreprise : %s).', $email, $entreprise->getRaisonSociale()));

        return Command::SUCCESS;
    }
}
