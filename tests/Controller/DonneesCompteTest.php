<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Droits d'accès et d'effacement (§17.1). Ce sont des chemins à conséquence : l'export
 * ne doit pas fuiter de secret, et la suppression ne doit pas partir sur une simple
 * requête. On les exerce donc pour de vrai, contre la base de test.
 */
final class DonneesCompteTest extends WebTestCase
{
    private const string MOT_DE_PASSE = 'motdepassefort2026';

    public function testLExportContientLeCompteMaisAucunSecret(): void
    {
        $client = static::createClient();
        $utilisateur = $this->creerCompte($client, 'export@example.test', '111111111');
        $client->loginUser($utilisateur);

        $client->request('GET', '/mon-compte/donnees/export');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('Content-Type', 'application/json');
        $contenu = (string) $client->getResponse()->getContent();

        self::assertStringContainsString('export@example.test', $contenu);
        self::assertStringContainsString('Boulangerie Test', $contenu, 'les données de l\'entreprise sont incluses');
        self::assertStringNotContainsString($utilisateur->getPassword(), $contenu, 'le mot de passe haché ne sort jamais');
        self::assertStringNotContainsString('totp', $contenu, 'le secret de double authentification ne sort jamais');
        self::assertStringContainsString('no-store', (string) $client->getResponse()->headers->get('Cache-Control'));
    }

    public function testLaSuppressionExigeLeBonMotDePasse(): void
    {
        $client = static::createClient();
        $utilisateur = $this->creerCompte($client, 'refus@example.test', '222222222');
        $client->loginUser($utilisateur);

        $this->soumettreSuppression($client, 'mauvais-mot-de-passe', 'SUPPRIMER');

        self::assertNotNull($this->retrouver($client, 'refus@example.test'), 'le compte est toujours là');
    }

    public function testLaSuppressionExigeLaConfirmationExplicite(): void
    {
        $client = static::createClient();
        $utilisateur = $this->creerCompte($client, 'confirmation@example.test', '333333333');
        $client->loginUser($utilisateur);

        $this->soumettreSuppression($client, self::MOT_DE_PASSE, 'oui');

        self::assertNotNull($this->retrouver($client, 'confirmation@example.test'));
    }

    public function testLaSuppressionEffaceReellementLeCompteEtSonEntreprise(): void
    {
        $client = static::createClient();
        $utilisateur = $this->creerCompte($client, 'adieu@example.test', '444444444');
        $client->loginUser($utilisateur);

        $this->soumettreSuppression($client, self::MOT_DE_PASSE, 'SUPPRIMER');

        self::assertResponseRedirects('/compte-supprime');
        self::assertNull($this->retrouver($client, 'adieu@example.test'), 'la suppression est effective, pas une désactivation');

        $em = $this->em($client);
        self::assertNull(
            $em->getRepository(Entreprise::class)->findOneBy(['siren' => '444444444']),
            'dernier compte de l\'entreprise : ses données partent aussi',
        );
    }

    /** Remplit et envoie le formulaire réel (le jeton CSRF vient donc de la page). */
    private function soumettreSuppression(KernelBrowser $client, string $motDePasse, string $confirmation): void
    {
        $crawler = $client->request('GET', '/mon-compte/donnees');
        self::assertResponseIsSuccessful();

        $formulaire = $crawler->filter('form[action="/mon-compte/donnees/supprimer"]')->form([
            'mot_de_passe' => $motDePasse,
            'confirmation' => $confirmation,
        ]);
        $client->submit($formulaire);
    }

    private function creerCompte(KernelBrowser $client, string $email, string $siren): Utilisateur
    {
        $em = $this->em($client);
        $this->nettoyer($em, $email, $siren);

        $entreprise = (new Entreprise())
            ->setRaisonSociale('Boulangerie Test')
            ->setSiren($siren);

        $utilisateur = (new Utilisateur())
            ->setEmail($email)
            ->setPrenom('Awa')
            ->setNom('Ndiaye')
            ->setRoles(['ROLE_DIRIGEANT'])
            ->setEntreprise($entreprise);
        $utilisateur->setPassword(
            $client->getContainer()->get(UserPasswordHasherInterface::class)->hashPassword($utilisateur, self::MOT_DE_PASSE),
        );

        $em->persist($entreprise);
        $em->persist($utilisateur);
        $em->flush();

        return $utilisateur;
    }

    /**
     * Les tests partagent une base : on repart d'un état connu à chaque scénario.
     * Les comptes rattachés partent AVANT leur entreprise, sinon la clé étrangère refuse.
     */
    private function nettoyer(EntityManagerInterface $em, string $email, string $siren): void
    {
        foreach ($em->getRepository(Utilisateur::class)->findBy(['email' => $email]) as $ancien) {
            $em->remove($ancien);
        }
        $em->flush();

        $entreprise = $em->getRepository(Entreprise::class)->findOneBy(['siren' => $siren]);
        if (null !== $entreprise) {
            foreach ($em->getRepository(Utilisateur::class)->findBy(['entreprise' => $entreprise]) as $rattache) {
                $em->remove($rattache);
            }
            $em->flush();
            $em->remove($entreprise);
            $em->flush();
        }
    }

    private function retrouver(KernelBrowser $client, string $email): ?Utilisateur
    {
        $em = $this->em($client);
        $em->clear();

        return $em->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);
    }

    private function em(KernelBrowser $client): EntityManagerInterface
    {
        return $client->getContainer()->get(EntityManagerInterface::class);
    }
}
