# État d'exécution du standard LINDBERGH FORMATION — RAF360

> Où en est le projet, section par section du `CLAUDE.md`, avec la preuve ou la limite.
> Trois états seulement, et ils ont un sens strict :
> **✅ fait et vérifié** · **⚠️ partiel ou non vérifié** (ni réussi, ni échoué) · **⛔ bloqué ou non engagé**.
>
> Un point marqué ⚠️ « non vérifié » n'est **pas** un point réussi. Cette distinction est
> volontaire : elle empêche de croire le site prêt sur des contrôles jamais passés.
>
> Arrêté au **2026-07-30**, commit `main` = `0e7a9bd` + lot R6.

---

## 1. Protocole de reprise 0-R

| Phase | État | Preuve / limite |
|---|---|---|
| **R0** — sécuriser l'existant | ✅ | Git + tag `avant-mise-en-conformite`, `docs/etat-des-lieux.md`, **dépôt distant privé** (branche `baseline-nextjs-gelee`). Baseline visuelle : captures partielles, l'ancien site n'ayant jamais été déployé |
| **R1** — fondations du contrat | ✅ | `docs/fiche-projet.md` rempli par le chef de projet (2026-07-28) : conversion n°1, cibles, tarifs, garde-fous |
| **R2** — audit + inventaire | ✅ | `docs/audit-conformite.md`, `docs/inventaire-transfert.md` (tokens à l'exact, 17 routes, règles métier). Constat majeur : **aucun contenu validé** |
| **R3** — trajectoire | ✅ | **Chemin B** validé par le chef de projet le 2026-07-23 |
| **R4-B** — reconstruction | ✅ | Socle, schéma, auth, site public 11 pages, 8 écrans `/app` sur vraies données, conformité. Détail : `docs/journal-lots.md` |
| **R5** — recette & bascule | ⚠️ **étape 1 faite, étape 2 bloquée** | Recette §25 avec preuve par item (`docs/recette-r5.md`) ; 4 bloquants techniques levés. **Bascule impossible** : ni domaine, ni hébergeur, ni certificat |
| **R6** — fermeture | ⚠️ **partielle** | Baseline archivée ✅, `docs/infrastructure.md` ✅, gabarit de fusion ✅, ADAPTATIONS purgé ✅. **Ne peut pas être close** : rien à éteindre puisque rien n'a été mis en ligne, et le recul de 2 à 4 semaines suppose une bascule |

---

## 2. Sections 1 à 26 du standard

### Fondations

| § | Sujet | État | Preuve / limite |
|---|---|---|---|
| 1 | Fiche projet | ✅ | Objectif, conversion n°1, personas, socle technique renseignés |
| 2.1 | Sécurité transverse | ✅ | Aucun secret au dépôt (vérifié avant publication), validation serveur systématique, `\|raw` limité au JSON-LD maîtrisé. Aucun téléversement de fichier à ce jour |
| 2.2 | PHP typage strict | ✅ | `strict_types` partout, **PHPStan niveau 6 : 0 erreur**. Les 2 `mixed` restants sont imposés par la signature du Voter Symfony |
| 2.3 | Plafonds de taille | ⚠️ | 3 gabarits autonomes dépassent 300 lignes — **dérogation déclarée** |
| 2.4 | Workflow Git | ⚠️ | Branche + fusion respectés, Conventional Commits. **Protection de `main` non activée côté GitHub** |
| 2.5 | Dépendances | ✅ | Chaque ajout justifié ; sitemap fait sans bundle plutôt qu'avec une dépendance de plus |
| 2.6 | Logs | ✅ | Monolog partout, aucune sortie de debug. **Défaut corrigé le 2026-07-30** : l'e-mail des prospects était journalisé en clair |
| 2.7 | Documentation | ✅ | `docs/decisions.md`, en-têtes de classes, commentaires sur le « pourquoi » |
| 2.8 | Docker | ✅ | `Dockerfile` + `compose.yaml` fonctionnels (FrankenPHP + PostgreSQL 16). ⚠️ **jamais testé sur machine vierge** |
| 2.9 | Tests | ✅ | **27 tests, 79 assertions**, dont 4 fonctionnels sur les droits RGPD |
| 2.10 | Contenu réel uniquement | ⛔ | **Aucune invention** (contrôlé à chaque lot, plusieurs contenus fictifs retirés), mais les **vrais témoignages et chiffres manquent** → bloquant côté chef de projet |
| 2.11 | Propriété intellectuelle | ✅ | Polices auto-hébergées sous licence libre, icônes maison, logo de l'éditeur. Aucune image « trouvée sur le web » |
| 2.12 | STOP en cas de doute | ✅ | Deux arbitrages remontés au lieu d'être devinés : source réelle des données `/app`, contraste CG-01 |
| 3 | Structure du projet | ✅ | Arborescence conforme, séparation `src/` · `templates/` · `assets/` |

### Front, UX, design

| § | Sujet | État | Preuve / limite |
|---|---|---|---|
| 4 | Règles front-end | ✅ | Contenu SEO **rendu serveur** (jamais injecté en JS), états d'interface traités, erreurs front remontées (`/log/client-error`) |
| 5 | UX et architecture de l'information | ✅ | Menu ≤ 7 entrées, 3 clics max, pied de page complet, **pages 404 et 500 soignées** (créées en R5 — elles n'existaient pas). Recherche interne non requise (< 20 contenus) |
| 6 | UI et design | ✅ | Tokens CSS uniques (couleurs, échelle d'espacement, typographie), design system homogène. **CG-01 ouvert** (contraste ambre) |
| 7 | Responsive | ⚠️ | Développé mobile-first, cibles ≥ 44 px, zoom non bloqué. **La recette 4 largeurs n'a pas été passée** : navigateur inaccessible en local |
| 8 | Performance | ⛔ **non prouvé** | Rendu serveur, images WebP, polices locales, cache d'assets immuable, préchargement LCP. **Aucun budget §8.1 mesuré** : ni Lighthouse, ni LCP, ni CLS, ni Core Web Vitals terrain |
| 9 | SEO et GEO | ✅ | Métadonnées uniques par page, 1 seul H1, canonical, sitemap, `robots.txt` **avec robots IA autorisés**, `llms.txt`, JSON-LD `Organization` + `FAQPage`, réponses autoportantes. Mineur : **7 méta-descriptions hors plage 150-160**. Search Console : dépend du domaine |
| 10 | Accessibilité WCAG 2.2 | ⚠️ | Site public traité (lien d'évitement, focus visible, contrastes, labels, `prefers-reduced-motion`, collage autorisé). **Non fait** : `/app` et onboarding non audités, **aucune recette clavier ni lecteur d'écran** |
| 11 | Contenu et copywriting | ⚠️ | Structure, ton et lisibilité en place ; dépend des contenus réels manquants (§2.10) |
| 12 | Confiance et conversion | ⛔ | CTA unique, parcours court, pages de remerciement. **Mentions légales incomplètes et CGV/CGU absentes alors que le site affiche des prix** |
| 13 | Formulaires et tunnels | ✅ | Contact et diagnostic : CSRF, honeypot, limitation de débit, validation serveur, POST-redirect-GET, e-mails de confirmation via Messenger. E-commerce : **hors périmètre** (aucun paiement). **SPF/DKIM/DMARC** dépendent du DNS |

### Back-end, données, sécurité

| § | Sujet | État | Preuve / limite |
|---|---|---|---|
| 14 | Règles back-end | ✅ | Contrôleurs fins → services → repositories ; **DQL uniquement dans `src/Repository/`** (écart corrigé en R5) ; erreurs normalisées sans détail interne |
| 15 | Base de données | ✅ | Doctrine + PostgreSQL 16, migrations versionnées et réversibles, `schema:validate` vert. Index vérifiés en base : **9 index métier explicites** (date d'échéance, statut de facture, priorité d'action, domaine, créneau…) **+ index sur chaque `entreprise_id`** (le filtre de cloisonnement), plus les contraintes d'unicité (e-mail, SIREN, numéro de facture, slug). **Règles d'archivage écrites table par table**. Pooling : non requis en mono-instance |
| 16 | Authentification et sécurité | ✅ | Argon2id, limitation des tentatives, CSRF, Voters d'appartenance, en-têtes de sécurité, **MFA TOTP obligatoire pour les administrateurs**, `composer audit` propre. Manque : vérification d'e-mail (double opt-in), non bloquante |
| 17 | RGPD et juridique | ⚠️ | **Droits exerçables** (export + suppression), registre des traitements, durées **appliquées** par purge automatique, consentement avant tout traceur, aucune donnée personnelle dans les journaux. Manquent : DPO, procédure de violation, contrats de sous-traitance, CGV — tous côté chef de projet |
| 18 | Fonctionnalités avancées | ✅ *(périmètre restreint assumé)* | Recherche dédiée, personnalisation, PWA : **non nécessaires** à ce stade. IA conversationnelle **volontairement non activée** et annoncée comme telle. Éco-conception : budgets de poids tenus par construction, mais non mesurés |
| 19 | Gestion de contenu et back-office | ⛔ **non engagé** | Aucun contributeur non-développeur à ce jour. À rouvrir dès qu'une personne devra publier sans développeur — sinon le site mourra de contenus périmés |

### Pilotage et exploitation

| § | Sujet | État | Preuve / limite |
|---|---|---|---|
| 20 | Données et analytics | ✅ *(mécanisme)* | Plan de mesure écrit, contrôleur `tracking` unique, conversions annoncées par le serveur, moteurs IA segmentés, aucune donnée personnelle. **Aucun outil branché → le site ne mesure rien aujourd'hui** |
| 21 | Observabilité et support | ⚠️ | `/health` opérationnel, Sentry prêt (PII désactivée), journaux JSON structurés. **Surveillance uptime et alertes dépendent de l'hébergement** |
| 22 | Scalabilité et continuité | ✅ | **Sauvegardes chiffrées + restauration réellement testée**, RPO 24 h / RTO 4 h, PRA, modes dégradés, points de défaillance listés. Limites assumées : sessions en fichiers (mono-instance), test de charge non fait |
| 23 | DevOps et CI/CD | ⚠️ | CI complète (9 étapes, PostgreSQL inclus), rollback documenté, **test de fumée automatique**, Dependabot. Manquent : **préproduction** et protection de branche |
| 24 | Tests et recette | ⚠️ | 27 tests automatisés, recette §25 avec preuve par item. **Impossible en local** : navigateurs réels, lecteur d'écran, appareil mobile, charge |
| 25 | Definition of Done | ⛔ | Déroulée intégralement → `docs/recette-r5.md`. **5 bloquants côté chef de projet** + les contrôles non vérifiables sans préproduction |
| 26 | Vérification anti-contradiction | ✅ | Passée à chaque lot ; a produit de vraies corrections (e-mail journalisé, DQL hors repository, `mixed`, pages d'erreur absentes) |

---

## 3. Ce qui décide de la suite

**Cinq décisions côté chef de projet** — aucune ne peut être levée par du code :

1. Témoignages et chiffres clients réels
2. Email de contact, directeur de la publication, hébergeur (UE)
3. CGV/CGU
4. Domaine + hébergement + certificat
5. Arbitrage CG-01

Le point 4 est le **verrou principal** : il débloque à lui seul la préproduction, donc la
recette performance, l'accessibilité réelle, la parité visuelle, la Search Console, la
surveillance uptime, les e-mails authentifiés, la mesure d'audience, et les tâches
planifiées de sauvegarde et de purge.
