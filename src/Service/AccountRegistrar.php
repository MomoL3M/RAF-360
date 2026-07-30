<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Création de comptes utilisateurs (§16.1). Le mot de passe est haché (Argon2id,
 * cf. security.yaml) — jamais stocké en clair. Un compte issu de l'inscription est
 * un dirigeant (ROLE_DIRIGEANT) ; l'entreprise est rattachée ensuite via l'onboarding.
 */
final readonly class AccountRegistrar
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher,
        private UtilisateurRepository $utilisateurs,
    ) {
    }

    public function emailExists(string $email): bool
    {
        return null !== $this->utilisateurs->findOneBy(['email' => $this->normalize($email)]);
    }

    /**
     * @param list<string> $roles
     */
    public function register(string $email, string $plainPassword, string $prenom, string $nom, ?Entreprise $entreprise = null, array $roles = ['ROLE_DIRIGEANT']): Utilisateur
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setEmail($this->normalize($email))
            ->setPrenom($prenom)
            ->setNom($nom)
            ->setRoles($roles)
            ->setEntreprise($entreprise);
        $utilisateur->setPassword($this->hasher->hashPassword($utilisateur, $plainPassword));

        $this->em->persist($utilisateur);
        $this->em->flush();

        return $utilisateur;
    }

    private function normalize(string $email): string
    {
        return mb_strtolower(trim($email));
    }
}
