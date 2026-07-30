<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Utilisateur;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Rend la double authentification OBLIGATOIRE pour l'administration (§16.1) : un
 * administrateur pleinement authentifié qui n'a pas encore activé la 2FA est redirigé
 * vers la page d'activation tant qu'il ne l'a pas fait.
 */
final readonly class Enforce2faSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private AuthorizationCheckerInterface $authorizationChecker,
        private UrlGeneratorInterface $urls,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onRequest'];
    }

    public function onRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest() || null === $this->tokenStorage->getToken()) {
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser();
        // isGranted('ROLE_ADMIN') est faux pendant la 2FA en cours → aucune interférence avec le défi.
        if (!$user instanceof Utilisateur
            || !$this->authorizationChecker->isGranted('ROLE_ADMIN')
            || $user->isTotpAuthenticationEnabled()) {
            return;
        }

        $request = $event->getRequest();
        $path = $request->getPathInfo();
        if (\in_array($request->attributes->get('_route'), ['securite_compte', 'deconnexion'], true)
            || str_starts_with($path, '/2fa')
            || str_starts_with($path, '/_')
            || str_starts_with($path, '/assets')) {
            return;
        }

        $event->setResponse(new RedirectResponse($this->urls->generate('securite_compte')));
    }
}
