<?php

declare(strict_types=1);

namespace App\Tests\Twig;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

/**
 * Une page d'erreur qui plante elle-même est pire que pas de page d'erreur :
 * on vérifie donc que les gabarits 404 et 500 se rendent réellement (§5.6).
 */
final class PagesErreurTest extends KernelTestCase
{
    public function testLaPage404SeRendAvecDesCheminsUtiles(): void
    {
        $html = $this->rendre('bundles/TwigBundle/Exception/error404.html.twig');

        self::assertStringContainsString('Cette page n\'existe pas', $html);
        self::assertStringContainsString('Retour à l\'accueil', $html);
        self::assertStringContainsString('/tarifs', $html, 'la 404 propose des chemins de reprise');
        self::assertStringNotContainsString('Exception', $html, 'aucun détail technique exposé');
    }

    public function testLaPage500NeDependDeRienEtNexposeAucunDetail(): void
    {
        $html = $this->rendre('bundles/TwigBundle/Exception/error500.html.twig');

        self::assertStringContainsString('momentanément indisponible', $html);
        self::assertStringNotContainsString('Exception', $html);
        self::assertStringNotContainsString('Stack', $html);
    }

    private function rendre(string $gabarit): string
    {
        self::bootKernel();
        $conteneur = static::getContainer();

        // Les gabarits utilisent le contexte de requête (canonical, URLs absolues) :
        // en situation réelle une requête existe toujours, on la simule ici.
        $requete = Request::create('https://raf360.fr/page-inexistante');
        $conteneur->get(RequestStack::class)->push($requete);

        /** @var Environment $twig */
        $twig = $conteneur->get(Environment::class);

        return $twig->render($gabarit);
    }
}
