<?php

declare(strict_types=1);

namespace App\Tests\EventListener;

use App\Entity\Utilisateur;
use App\EventListener\Enforce2faSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Vérifie que la 2FA est imposée aux administrateurs sans double authentification (§16.1),
 * sans boucle de redirection, et sans gêner les autres cas.
 */
final class Enforce2faSubscriberTest extends TestCase
{
    public function testAdminSans2faEstRedirigeVersLActivation(): void
    {
        $event = $this->handle($this->admin(), true, '/app/dashboard', 'app_dashboard');

        $response = $event->getResponse();
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame('/mon-compte/securite', $response->getTargetUrl());
    }

    public function testUtilisateurNonAdminNestPasRedirige(): void
    {
        $event = $this->handle($this->admin(), false, '/app/dashboard', 'app_dashboard');

        self::assertNull($event->getResponse());
    }

    public function testAdminAvecuneuf2faActiveeNestPasRedirige(): void
    {
        $admin = $this->admin()->setTotpSecret('JBSWY3DPEHPK3PXP');

        $event = $this->handle($admin, true, '/app/dashboard', 'app_dashboard');

        self::assertNull($event->getResponse());
    }

    public function testPasDeBoucleSurLaPageDActivation(): void
    {
        $event = $this->handle($this->admin(), true, '/mon-compte/securite', 'securite_compte');

        self::assertNull($event->getResponse());
    }

    private function admin(): Utilisateur
    {
        return (new Utilisateur())->setEmail('admin@example.fr')->setRoles(['ROLE_ADMIN']);
    }

    private function handle(UserInterface $user, bool $isAdmin, string $path, string $route): RequestEvent
    {
        $token = $this->createStub(TokenInterface::class);
        $token->method('getUser')->willReturn($user);
        $tokenStorage = $this->createStub(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($token);

        $auth = $this->createStub(AuthorizationCheckerInterface::class);
        $auth->method('isGranted')->willReturn($isAdmin);

        $urls = $this->createStub(UrlGeneratorInterface::class);
        $urls->method('generate')->willReturn('/mon-compte/securite');

        $request = Request::create($path);
        $request->attributes->set('_route', $route);
        $event = new RequestEvent($this->createStub(HttpKernelInterface::class), $request, HttpKernelInterface::MAIN_REQUEST);

        (new Enforce2faSubscriber($tokenStorage, $auth, $urls))->onRequest($event);

        return $event;
    }
}
