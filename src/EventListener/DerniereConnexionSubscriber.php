<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

/**
 * Enregistre la date de dernière connexion réussie. C'est la donnée qui permet
 * d'appliquer la durée de conservation des comptes inactifs (§17.1) : sans elle, la
 * purge automatique n'aurait aucun critère.
 */
final readonly class DerniereConnexionSubscriber implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class => 'onLoginSuccess'];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $utilisateur = $event->getUser();
        if (!$utilisateur instanceof Utilisateur) {
            return;
        }

        $utilisateur->setDerniereConnexion(new \DateTimeImmutable());
        $this->em->flush();
    }
}
