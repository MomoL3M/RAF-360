<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\AccountRegistrar;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Crée un compte administrateur nominatif (§16.1). Le mot de passe est haché (Argon2id).
 * La double authentification est OBLIGATOIRE : elle sera exigée dès la première connexion
 * (Enforce2faSubscriber redirige vers l'activation).
 */
#[AsCommand(name: 'app:create-admin', description: 'Crée un compte administrateur (2FA obligatoire à la 1re connexion)')]
final class CreateAdminCommand extends Command
{
    public function __construct(private readonly AccountRegistrar $registrar)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'Adresse e-mail de l\'administrateur')
            ->addArgument('password', InputArgument::OPTIONAL, 'Mot de passe (≥ 12 car.) ; généré si omis');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = (string) $input->getArgument('email');
        $password = (string) ($input->getArgument('password') ?? '');

        if ($this->registrar->emailExists($email)) {
            $io->error(\sprintf('Un compte existe déjà avec l\'adresse « %s ».', $email));

            return Command::FAILURE;
        }

        $generated = false;
        if (mb_strlen($password) < 12) {
            $password = bin2hex(random_bytes(9)); // 18 caractères
            $generated = true;
        }

        $this->registrar->register($email, $password, 'Admin', 'RAF360', null, ['ROLE_ADMIN']);

        $io->success(\sprintf('Administrateur « %s » créé.', $email));
        if ($generated) {
            $io->writeln(\sprintf('Mot de passe généré : <info>%s</info> (à transmettre de façon sécurisée puis à changer).', $password));
        }
        $io->note('La double authentification (TOTP) sera exigée dès la première connexion.');

        return Command::SUCCESS;
    }
}
