# Journal des lots livrés — RAF360 (reconstruction Symfony, chemin B)

> Ce fichier existe pour une raison précise : le bloc **ADAPTATIONS** du `CLAUDE.md` doit
> lister les **écarts** au standard et **se vider** avec le temps (phase R6). Il s'y était
> accumulé des lignes « ✅ Fait » qui sont de l'historique, pas des écarts. Elles sont
> déplacées ici, sans rien perdre.

Ordre chronologique. Chaque entrée renvoie au commit sur `main`.

---

## R0 → R3 — sécurisation, contrat, audit, trajectoire

| Date | Livré |
|---|---|
| 2026-07-23 | Standard LINDBERGH FORMATION v3.1-R installé comme `CLAUDE.md` ; protocole 0-R engagé |
| 2026-07-27 | **R0** : git + tag `avant-mise-en-conformite`, état des lieux (`docs/etat-des-lieux.md`) |
| 2026-07-27 | **R2** : inventaire de transfert (`docs/inventaire-transfert.md`) — tokens extraits à l'exact, 17 routes, règles métier. Constat majeur : **aucun contenu validé** (témoignages, chiffres, adossements fictifs) |
| 2026-07-23 | **R3** : trajectoire **chemin B** (reconstruction Symfony) validée par le chef de projet |
| 2026-07-28 | **R1** : fiche projet remplie (`docs/fiche-projet.md`) — positionnement généraliste TPE/PME, conversion n°1 « Démarrer le diagnostic gratuit », tarifs 39/129/349/899 |

**Existant audité** : Next.js 16 / React 19 / TypeScript, données 100 % fictives, aucune
authentification, aucune base connectée → moins de 30 % récupérable en l'état, d'où le chemin B.

---

## R4-B — reconstruction par lots

| Lot | Livré |
|---|---|
| Socle | Symfony 7.4 + PHP 8.4, Docker (FrankenPHP `1-php8.4` + PostgreSQL 16 + Mailpit), AssetMapper (sans Node), CI GitHub Actions, PHP-CS-Fixer + PHPStan niveau 6 |
| Schéma | 11 entités du domaine financier + enums + repositories (§14.1), montants en centimes, migrations versionnées |
| Site public | Design system porté à l'exact de la baseline, polices auto-hébergées WOFF2, 11 pages (accueil, produit, solutions, tarifs, à-propos, blog, contact, diagnostic, 3 pages légales), formulaires réels (CSRF + honeypot + rate limit) |
| Conformité | SEO/GEO (`robots.txt` + robots IA, `llms.txt`, `sitemap.xml`, JSON-LD, canonical), NelmioSecurityBundle (CSP, clickjacking, nosniff, Referrer-Policy, HSTS en prod), `/health`, bandeau de consentement |
| Images | LiipImagineBundle (jeux WebP `content_*`), extension GD ajoutée au Dockerfile, `og:image` ramené à 23 Ko et logo 850 → 198 Ko |
| Observabilité | Sentry (PII désactivée), emails transactionnels (`LeadNotifier` + gabarits + Messenger async), collecteur d'erreurs front (`/log/client-error`) |
| Accessibilité | WCAG 2.2 sur le site public : lien d'évitement, focus clavier visible, token `--gold-ink` pour le contraste de l'ambre en texte |
| Authentification | `/inscription` (Argon2id, ≥ 12 caractères, CSRF, rate limit) → `/onboarding` protégé. **Dérogation « onboarding sans mot de passe » levée** |
| MFA | Double authentification TOTP (`scheb/2fa-*`), **obligatoire pour les administrateurs** (`Enforce2faSubscriber`), commande `app:create-admin` |
| `/app` | Les **8 écrans** sur vraies données, cloisonnés **par entreprise** (§16.1) ; présentation extraite en 3 services de vue ; plus aucun tableau de démonstration dans les gabarits |

**Contenus inventés retirés au fil des lots** (§2.10) : clients nommés, professionnels
nommés, ventilations de charges par catégorie inexistantes, fausse réponse d'assistant IA,
veille réglementaire fabriquée. Remplacés par des profils de compétences, des totaux réels
et des formulations au conditionnel.

---

## R5 — recette et levée des bloquants techniques

| Date | Livré |
|---|---|
| 2026-07-30 | **Recette §25 avec preuve par item** → `docs/recette-r5.md`. Verdict initial : 9 bloquants. Corrections trouvées par la recette : pages 404/500 inexistantes, `mixed` échappatoire, DQL hors repository |
| 2026-07-30 | **Gate R0 fermé** : dépôt distant privé `github.com/MomoL3M/RAF-360` (`main` + branche `baseline-nextjs-gelee` + tag). La CI a pu tourner pour la première fois |
| 2026-07-30 | **RGPD exerçable** : page « Mes données » (export JSON + suppression définitive), `EffacementDonneesEntreprise` partagé, `app:purger-donnees` (anonymisation à 36 mois), registre `docs/traitements-donnees.md`. Correction : l'e-mail des prospects était **journalisé en clair** |
| 2026-07-30 | **Exploitation** : sauvegardes chiffrées + **restauration réellement testée**, RPO 24 h / RTO 4 h, PRA, rollback, règles d'archivage (`docs/exploitation.md`) |
| 2026-07-30 | **Test de fumée** (12 vérifications contenu + code HTTP) branché en CI, Dependabot, `importmap:audit` |
| 2026-07-30 | **Plan de mesure** (`docs/plan-mesure.md`) + contrôleur `tracking` unique, conversions annoncées par le serveur, segmentation des moteurs IA |
| 2026-07-30 | **CI avec PostgreSQL** : sans base, aucun test fonctionnel n'était possible. Couverture 20 → 27 tests |

Verdict R5 après ces lots : **5 bloquants restants, tous côté chef de projet**.
