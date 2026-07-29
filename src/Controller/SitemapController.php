<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Sitemap XML des pages publiques indexables (§9.1).
 *
 * Généré depuis le routeur (pas de dépendance sitemap : jeu d'URL restreint et
 * statique — cf. ADAPTATIONS §2.5). Les URL sont absolues (contexte de requête,
 * donc le domaine canonique en production). L'espace /app, la connexion et
 * l'onboarding en sont exclus (non indexables, cf. robots.txt).
 */
final class SitemapController extends AbstractController
{
    /**
     * Pages publiques indexables : route => [changefreq, priority].
     * Quand le blog aura de vrais articles (entité ArticleBlog), les ajouter ici.
     */
    private const PAGES = [
        'accueil' => ['weekly', '1.0'],
        'produit' => ['monthly', '0.9'],
        'solutions' => ['monthly', '0.9'],
        'tarifs' => ['monthly', '0.9'],
        'a_propos' => ['yearly', '0.6'],
        'blog' => ['weekly', '0.6'],
        'contact' => ['yearly', '0.7'],
        'diagnostic' => ['monthly', '0.8'],
        'mentions_legales' => ['yearly', '0.3'],
        'confidentialite' => ['yearly', '0.3'],
        'rgpd' => ['yearly', '0.3'],
    ];

    #[Route('/sitemap.xml', name: 'sitemap', methods: ['GET'])]
    public function index(): Response
    {
        $urls = [];
        foreach (self::PAGES as $route => [$changefreq, $priority]) {
            $urls[] = [
                'loc' => $this->generateUrl($route, [], UrlGeneratorInterface::ABSOLUTE_URL),
                'changefreq' => $changefreq,
                'priority' => $priority,
            ];
        }

        $response = $this->render('sitemap.xml.twig', ['urls' => $urls]);
        $response->headers->set('Content-Type', 'application/xml; charset=UTF-8');

        return $response;
    }
}
