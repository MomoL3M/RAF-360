<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Crée (ou met à jour) un utilisateur de démonstration pour visualiser l'espace /app.
 * Usage dev uniquement — ne remplace pas le futur parcours d'inscription/onboarding.
 */
#[AsCommand(name: 'app:demo-user', description: 'Crée/met à jour un utilisateur de démonstration (accès /app).')]
final class DemoUserCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UtilisateurRepository $users,
        private readonly UserPasswordHasherInterface $hasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = 'demo@raf360.fr';
        $plain = 'DemoRAF360!';

        $user = $this->users->findOneBy(['email' => $email]) ?? new Utilisateur();
        $user->setEmail($email);
        $user->setNom('Démo');
        $user->setPrenom('Dirigeant');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, $plain));

        $this->em->persist($user);
        $this->em->flush();

        $io->success(\sprintf('Utilisateur de démonstration prêt — identifiant : %s · mot de passe : %s', $email, $plain));

        return Command::SUCCESS;
    }
}
