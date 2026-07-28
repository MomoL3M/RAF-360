# Audit de conformité & suivi de reprise — RAF 360

> Piloté par le standard LINDBERGH FORMATION (CLAUDE.md, protocole 0-R).

## Suivi de phase

| Phase | Objet | État |
|-------|-------|------|
| R0 | Sécuriser l'existant (git, sauvegarde, baseline, état des lieux) | **En cours** — git ✅, tag ✅, [etat-des-lieux.md](etat-des-lieux.md) ✅ ; sauvegarde distante ⬜, captures baseline 4 largeurs ⬜ |
| R1 | Fondations du contrat (fiche projet §1, CLAUDE.md propre à la racine) | ✅ fiche remplie ([fiche-projet.md](fiche-projet.md), 2026-07-28) + CLAUDE.md à la racine. Reste : email / hébergeur / témoignages (`TODO-PM`) |
| R2 | Audit d'écarts + inventaire de transfert (aucune modification) | 🔄 inventaire de transfert ✅ ([inventaire-transfert.md](inventaire-transfert.md), 2026-07-27) ; audit d'écarts par section ⬜ |
| R3 | Trajectoire | ✅ **Chemin B — reconstruction Symfony 7.4** (validé 2026-07-23) |
| R4-B | Reconstruction Symfony par lots | 🔄 **en cours** — lot 1 (socle + environnement Docker) ✅ terminé le 2026-07-27 |
| R5 | Recette & bascule | — |
| R6 | Fermeture (archivage de l'existant) | — |

## Outillage (mise à jour 2026-07-23)

- ✅ **PHP 8.4.23** (NTS x64) dans `C:\php`, sur le PATH, extensions activées (intl, pdo_pgsql, pgsql, mbstring, openssl, curl, zip, sodium, gd, opcache), `php.ini` réglé (`realpath_cache_size=5M`). `symfony check:requirements` : aucune erreur bloquante.
- ✅ **Composer 2.10.2** (`C:\php\composer.phar` + `composer.bat`).
- ✅ **Symfony CLI 5.17.1** (`C:\php\symfony.exe`).
- ✅ **Docker Desktop** installé (Docker 29.6.2, Compose v5.3.1) — daemon opérationnel le 2026-07-27. Débloque PostgreSQL et l'exécution conteneurisée (§2.8).

## R4-B — Suivi par lots

- **Lot 1 — Socle + environnement (✅ 2026-07-27)** : nouveau dépôt `../raf360-symfony` (git initialisé, commit de socle). Symfony **7.4.14** webapp (Twig, AssetMapper sans build Node, Doctrine ORM). Environnement Docker **FrankenPHP (PHP 8.4.23)** + **PostgreSQL 16.14** + Mailpit, tous conteneurs *healthy*. Vérifié : page d'accueil Symfony servie en HTTPS, connexion Doctrine → Postgres OK. Runtime PHP **épinglé 8.4** (le template amont livrait 8.5) pour respecter la cible du standard.
- **Standard reçu et installé (2026-07-27)** : édition reprise v3.1-R propre (accents OK) → `CLAUDE.md` à la racine du dépôt gelé ET de `raf360-symfony`. Socle mis en conformité §1.3 (`.php-version`=8.4, `composer.json` php `8.4.*`).
- **Lot 2 — Modèle de domaine (🔄 en cours)** : **9 entités** Doctrine couvrant tous les modules, re-exprimées de l'inventaire R2 (**sans donnée de démo**), + 8 enums, repositories dédiés (§14.1), index (§15), 2 migrations appliquées, `doctrine:schema:validate` OK, **PHPStan niveau 6 : 0 erreur**, 5 tests unitaires.
  - branche `feat/schema-domaine-financier` : Échéance, Facture, Document.
  - branche `feat/entites-complementaires` : FluxTresorerie, AlerteEncaissement, Professionnel, RendezVous (ManyToOne), Action, ArticleBlog.
  - Auth **différée** (§2.12, attend la fiche R1).
- **Outillage qualité (✅ 2026-07-27, branche `chore/outillage-qualite`)** : PHP-CS-Fixer (@Symfony + risky + `declare_strict_types`), **PHPStan niveau 6** (0 erreur, extensions Symfony & Doctrine), **CI GitHub Actions** (validate / cs-fixer / phpstan / phpunit / audit), 1er test unitaire (règles métier). Vérification locale : tout vert (3 tests OK, 0 vulnérabilité).
- **Lot auth & sécurité (✅ terminé, branche `feat/auth-securite`)** : entités **Utilisateur** / **Entreprise** (relation, validation email + SIREN), enum `RoleUtilisateur`, `security.yaml` (**Argon2id** explicite via sodium, provider entité, `form_login` + CSRF, `login_throttling` 5 essais, `role_hierarchy`, `/app` réservé `ROLE_USER`), `SecurityController` (/connexion, /deconnexion) + gabarit, `AppController` /app (placeholder protégé), `AppNoindexSubscriber` (X-Robots-Tag noindex sur /app), **EntrepriseVoter** (contrôle d'appartenance §16.1), + `symfony/rate-limiter`. Migration `Version20260728111750` appliquée (tables `utilisateur`/`entreprise`). Vérifié : CS-Fixer 0, PHPStan niveau 6 0 erreur, PHPUnit 8 tests ; **recette runtime OK** — `/connexion` 200 (form + CSRF), `/app` anonyme → **302 vers /connexion + `X-Robots-Tag: noindex`**, hachage **Argon2id** confirmé, conteneurs *healthy*.
- **Consolidation (✅ 2026-07-28)** : les 5 lots fusionnés (fast-forward) sur une branche unique **`main`** (ex-`master`, renommée pour aligner §2.4) ; branches de lot supprimées. `main` = 7 commits, working tree propre, smoke OK (`/connexion` 200, `/app` 302). ⚠ Fait en **local sans Merge Request** (aucun dépôt distant à ce jour) ; le flux branche → MR → revue humaine s'appliquera dès qu'un remote existera.
- **Lot pages marketing (🔄 en cours, branche `feat/pages-marketing`)** : design system CSS porté **à l'exact** (`tokens.css` + `marketing.css`, sans Tailwind) + polices **Fraunces/Inter auto-hébergées** (WOFF2, §8.3) ; `base.html.twig` (SEO, `lang=fr`, préchargement) ; Header/Footer/Logo/icônes en Twig + contrôleurs Stimulus `reveal` & `header` ; **page d'accueil complète iso-graphisme (HTTP 200)** + pages stub « en construction » (aucun lien mort). **Contenu corrigé (garde-fous R1)** : « outil de gestion compatible facturation électronique » (jamais « PDP/agréé »), réseau de pros au conditionnel, témoignages retirés (placeholder), tarifs R1 (39/129/349), éditeur « Lindbergh Formation SAS ». `/app` en `noindex`. Vérifié CS-Fixer/PHPStan 0.
- **Reste** : pages marketing internes (produit, solutions, tarifs, à-propos, blog) → app `/app` (interface réelle) → pages légales/RGPD → **recette visuelle 4 largeurs vs baseline** → conformité §25 → recette R5. Outillage encore à poser : LiipImagine, sitemap, `llms.txt`/`robots.txt`, NelmioSecurityBundle.

### Garde-fous de contenu actés en R1 (règle 2.10 / §17.2) — à respecter dans TOUTE page

- **JAMAIS** « plateforme agréée » / « PDP » / « agréé » → formulation validée : « **outil de gestion compatible facturation électronique** » (solution compatible connectée à une plateforme agréée partenaire).
- **Réseau de professionnels** au **conditionnel** tant que la SPE n'est pas activée.
- **« Sources officielles »** = usage documentaire daté, **jamais** un label ni un partenariat.
- **Témoignages & chiffres clients** = `TODO-PM`, **ne pas inventer**.

Note : ouvrir un **nouveau** terminal pour que le PATH (`C:\php`) soit pris en compte.

## R2 — Audit d'écarts (à compléter)

_À renseigner lors de la phase R2 : pour chaque section du standard, l'écart entre l'existant et la cible, la gravité (BLOQUANT / MAJEUR / MINEUR) et l'effort. Vérifications prioritaires : secrets dans le code, entrées non validées, données personnelles exposées, pages légales absentes, contenus de réassurance invérifiables._

### Points déjà repérés (pré-audit, cf. etat-des-lieux.md)

- **Contenus fictifs (règle 2.10)** : témoignages nominatifs, statistiques, tarifs, coordonnées → à valider ou retirer (`TODO-PM`). BLOQUANT pour toute mise en ligne.
- **Pages légales absentes** (mentions légales, confidentialité, CGV/CGU).
- **Aucune mesure d'audience / dispositif RGPD** (à intégrer à la reconstruction).

## R2 — Inventaire de transfert (✅ 2026-07-27)

Consigné dans **[inventaire-transfert.md](inventaire-transfert.md)** :
- **§A** Tokens de design extraits à l'exact de `globals.css` (couleurs, typo Fraunces/Inter, espacements, ombres, easing, breakpoints) — signale que `lib/tokens.ts` est **désynchronisé** (à ne pas transférer).
- **§B** Motion + exigence `prefers-reduced-motion` à reconduire.
- **§C** Règles métier (format de date, retard, tri d'échéances, ventilation trésorerie, seuils, stepper onboarding, seuil OCR 85 %, jauge 65 %…).
- **§D** Métadonnées/SEO de référence (domaine `raf360.fr` à confirmer ; sitemap/robots absents).
- **§E** Table des URLs (aucune 301 à préserver — pas de domaine public).
- **§F** Tri des contenus : **aucun contenu validé à ce jour** — témoignages, chiffres, tarifs, adossement « sources officielles » = **BLOQUANT** avant mise en ligne (`TODO-PM`).
- **§G** Comportements dynamiques → cible Stimulus/Turbo (7 controllers identifiés) + inventaire du présentationnel (Twig pur).
