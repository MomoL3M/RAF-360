# Recette R5 — checklist §25, preuve par item

**Date** : 2026-07-30 · **Version auditée** : `main` (après R4-B) · **Auditeur** : Claude Code
**Mise à jour** : 2026-07-30 (fin de journée) — les 4 bloquants techniques et le gate R0
sont soldés ; voir la synthèse §B.

**Verdict global** : ⛔ **mise en ligne toujours BLOQUÉE** — **5 bloquants restants, tous
côté chef de projet** (contenus réels, mentions légales, CGV/CGU, domaine et hébergement,
arbitrage CG-01). Aucun ne peut être levé par du code.

À cela s'ajoute la recette impossible sans préproduction (§C) : performance, accessibilité
et parité visuelle restent **non vérifiées** — ni réussies, ni échouées.

---

## 0. Limites de cet audit (à lire avant les verdicts)

Cette recette a été menée **en environnement de développement local**, faute de préproduction. Trois familles de contrôles n'ont donc **pas** pu être exécutées, et sont marquées `NON VÉRIFIÉ` — elles ne sont ni réussies ni échouées :

| Limite | Conséquence |
|---|---|
| **Aucune préproduction** (§23.3 exige dev / préprod / prod séparés) | La recette « sur préproduction noindex » exigée par R5 n'a pas eu lieu. Les mesures de performance en dev (debug + profiler + montage Windows) ne sont **pas représentatives**. |
| **Certificat auto-signé sur `https://localhost`** | Le navigateur intégré n'accède pas au site (page vide, viewport 0×0). Donc : **aucun test clavier réel, aucun lecteur d'écran, aucune capture aux 4 largeurs, aucun audit axe/Lighthouse**. |
| **Pas de domaine public ni d'hébergeur** (`TODO-PM`) | Pas de HTTPS réel, pas de HSTS effectif, pas de Search Console, pas de mesure Core Web Vitals terrain. |

Tout ce qui est marqué ✅ ci-dessous a été **prouvé par une commande** (sortie reproductible), pas supposé.

---

## 1. Qualité technique

| Item §25 | Verdict | Preuve |
|---|---|---|
| Analyse statique, CS-Fixer, `composer validate` | ✅ | PHPStan niveau 6 : `[OK] No errors` · CS-Fixer : `0 of 74 files` · `composer.json is valid` |
| Tests | ✅ | PHPUnit `OK (20 tests, 49 assertions)` |
| Aucune sortie de debug | ✅ | `grep -rE "(var_dump|dd|dump)\(" src/` → vide ; `console.*` absent de notre JS (hors `assets/vendor/`, code tiers) |
| Aucun `mixed` échappatoire | ✅ **corrigé pendant la recette** | Le collecteur d'erreurs front utilisait `clip(mixed $value)` → remplacé par un accesseur typé `champ(array, string): string`. Restent 2 `mixed` dans `EntrepriseVoter`, **imposés par la signature du Voter Symfony** (légitimes). |
| Aucun accès Doctrine hors repositories | ✅ **corrigé pendant la recette** | `DemoDataSeeder` exécutait du DQL `DELETE` → déplacé en `deleteForEntreprise()` dans les **7 repositories** concernés. Re-vérifié : `grep -rl "createQueryBuilder\|createQuery(" src/ \| grep -v Repository/` → vide |
| Aucun secret dans le code | ✅ | grep sur `src/ config/ templates/` → vide ; `.env.local` non versionné |
| `docker compose up` sur environnement vierge | ⚠️ NON VÉRIFIÉ | Jamais testé sur une machine vierge (toujours le même poste). À faire avant mise en production. |
| **Rollback documenté** | ⛔ **ABSENT** | Aucun document de rollback (§23.2 l'exige **avant** la première mise en production). |
| CI verte | ⚠️ PARTIEL | Les mêmes outils passent en local ; le workflow `.github/workflows/ci.yaml` n'a **jamais tourné** (aucun dépôt distant). |

## 2. UX / UI / Responsive

| Item | Verdict | Preuve |
|---|---|---|
| Test des 4 largeurs + vrai mobile | ⛔ **NON FAIT** | Navigateur inaccessible (cert). **Recette visuelle R5 vs baseline R0 non réalisée** — c'est une exigence explicite de la phase R5. |
| États chargement / erreur / vide / succès | ✅ (partiel) | États vides présents sur les 8 écrans `/app` (échéances, factures, documents, actions, trésorerie, data room) et sur le blog. États de *chargement* : sans objet (rendu serveur, pas de chargement asynchrone). |
| **Page 404 soignée** | ✅ **créée pendant la recette** | `templates/bundles/TwigBundle/Exception/error404.html.twig` (+ `error500`) — absentes avant l'audit. Rendu prouvé par `PagesErreurTest` (2 tests). Statut HTTP réel vérifié : `404`. |
| Aucune page cul-de-sac / orpheline | ✅ | Tous les liens internes des 11 pages publiques répondent 200/302 (aucun lien cassé) |
| Compréhension en 5 secondes | ⚠️ À VALIDER PAR LE PM | Jugement humain, non automatisable. |

## 3. Performance

| Item | Verdict | Preuve |
|---|---|---|
| **Lighthouse mobile ≥ 90 par gabarit** | ⛔ **NON VÉRIFIÉ** | Outil indisponible dans cet environnement. **Aucun budget §8.1 n'est donc prouvé** (LCP, CLS, INP). |
| TTFB < 800 ms | ⚠️ NON REPRÉSENTATIF | Mesuré en dev : 0,70 – 1,20 s (`/`, `/produit`, `/tarifs`) — avec debug, profiler et montage Windows. À remesurer en préproduction. |
| Poids de page < 1 Mo | ✅ | HTML 76–106 Ko non compressé, servi compressé (zstd/gzip) |
| Polices locales WOFF2 + préchargement | ✅ | `preload` sur `inter-400` et `inter-700`, servies depuis `/assets/fonts/` |
| Images en formats modernes, dimensionnées | ✅ (sans objet) | Aucune image matricielle sur les pages publiques (visuels en SVG inline) ; pipeline WebP prêt (LiipImagine) pour le contenu futur ; `og:image` = 23 Ko |
| Cache HTTP des assets | ✅ | `Cache-Control: immutable, max-age=604800, public` + `ETag` |

## 4. SEO

| Item | Verdict | Preuve |
|---|---|---|
| Métadonnées complètes et **uniques** par page | ✅ | 11 pages : titres tous uniques, descriptions toutes uniques, `og:title/description/image/url/locale/type` présents |
| Longueur des descriptions ~150–160 car. | ⚠️ **7 pages hors plage** | 243 (`/produit`), 221 (`/`), 187, 182, 181, 175, 171 → trop longues (tronquées par Google) ; 91 (`/mentions-legales`), 109 (`/contact`), 135, 136 → trop courtes. **MINEUR, à retoucher.** |
| Un seul H1 par page | ✅ | Exactement 1 `<h1>` sur chacune des 11 pages |
| Sitemap accessible | ✅ | `/sitemap.xml` → 200, 11 URLs, cohérent avec les pages publiques |
| robots.txt correct | ✅ | 200, `Disallow` sur `/app`, `/connexion`, `/onboarding`, `Sitemap:` référencé |
| **La production n'est pas en noindex** | ✅ | En dev, `X-Robots-Tag: noindex` apparaît sur les pages publiques : c'est le listener **Symfony `DisallowRobotsIndexingListener`, actif uniquement en debug**. Prouvé en prod : `debug:container disallow_search_engine_index_response_listener` → *No services found*. Notre subscriber n'ajoute `noindex, nofollow` que sur `/app` (vérifié : `str_starts_with($path, '/app')`). |
| Canonicals posées | ✅ | Canonical exacte et sans query sur les 11 pages |
| Données structurées valides | ✅ | JSON-LD `Organization` parse sans erreur (`name=RAF360`, `legalName=Lindbergh Formation SAS`) ; `FAQPage` présent sur `/tarifs` |
| Contenu SEO rendu côté serveur | ✅ | Contenu présent dans le HTML source (curl), pas injecté en JS |
| Search Console configurée | ⛔ IMPOSSIBLE | Nécessite un domaine public (`TODO-PM`) |
| Redirections 301 | ✅ (sans objet) | Aucune URL publique préexistante (aucun domaine, aucun trafic) |

## 5. GEO (moteurs de réponse IA)

| Item | Verdict | Preuve |
|---|---|---|
| Robots IA non bloqués | ✅ | `robots.txt` autorise explicitement GPTBot, OAI-SearchBot, ChatGPT-User, ClaudeBot, PerplexityBot, Google-Extended, Bingbot |
| `llms.txt` présent et cohérent | ✅ | 200, 1 293 octets, liens alignés sur le sitemap |
| Réponse autoportante nommant l'entreprise | ✅ | Présente sur l'accueil et les pages clés (rédigées en R4) |
| FAQ balisée question→réponse | ✅ | `FAQPage` sur `/tarifs` |
| Faits/prix/dates exacts et datés | ⚠️ PARTIEL | Tarifs figés (39/129/349/899 € HT) et dates e-facture (1ᵉʳ sept. 2026 / 2027) exacts. **Mais témoignages et chiffres clients restent absents** (`TODO-PM`). |
| NAP cohérent site / fiche Google / annuaires | ⛔ IMPOSSIBLE | Aucune présence hors site (`TODO-PM`) |
| Trafic IA segmenté dans l'analytics | ⛔ **ABSENT** | Aucun analytics installé (voir §8) |

## 6. Accessibilité

| Item | Verdict | Preuve |
|---|---|---|
| Parcours complet au clavier, sans piège de focus | ⛔ **NON TESTÉ** | Navigateur inaccessible. Le CSS nécessaire existe (focus-visible global, lien d'évitement, `scroll-padding-top`) mais **rien ne remplace le test manuel**. |
| Test lecteur d'écran | ⛔ **NON FAIT** | Exige un humain (NVDA/VoiceOver). |
| Contrastes AA | ⚠️ PARTIEL | 2 échecs identifiés et corrigés en amont (ambre en texte → token `--gold-ink`), consignés au registre `conflits-graphisme.md` (CG-01, **arbitrage PM en attente**). `--muted` sur `--paper-3` ≈ 4,3:1 reste **limite** (< 4,5). Balayage exhaustif non fait (outil). |
| `alt` sur toutes les images | ✅ | Aucune `<img>` sans `alt` sur les 11 pages |
| Labels sur tous les champs | ✅ | Contact et diagnostic : chaque champ visible a un `<label for>` ; le champ piège (honeypot) utilise un **label implicite** (`<label>texte<input></label>`), valide |
| `lang="fr"` | ✅ | Présent sur les 11 pages |
| Zoom autorisé | ✅ | Aucun `user-scalable=no` ni `maximum-scale` |
| Lien d'évitement | ✅ | « Aller au contenu » présent sur les 11 pages |
| Déclaration d'accessibilité | ⚠️ À INSTRUIRE | Obligatoire seulement selon le statut de l'organisation — à trancher avec le PM. |
| Accessibilité de `/app` et de l'onboarding | ⛔ **NON AUDITÉ** | Gabarits autonomes, hors périmètre de la passe accessibilité du site public. |

## 7. Contenus / Confiance / Légal

| Item | Verdict | Preuve |
|---|---|---|
| Aucun lorem ipsum | ✅ | grep sur les 11 pages → vide |
| Aucun contenu inventé | ✅ **assaini pendant R4-B** | Aucun terme interdit (`PDP`, `plateforme agréée`, `Groupe ARCAN`) ; les professionnels nommés fictifs de la data room ont été supprimés (réseau au conditionnel) ; ventilations de charges inventées supprimées ; fausse réponse IA supprimée |
| Coordonnées réelles | ✅ (partiel) | Mentions légales : `Lindbergh Formation SAS`, `16 rue de Maillé, 91310 Montlhéry`, `+33 1 87 66 20 97`, `SIRET 817 946 114 00029` |
| **Mentions légales complètes** | ⛔ **BLOQUANT** | Manquent, honnêtement signalés « à préciser avant la mise en ligne » : **email de contact**, **directeur de la publication**, **hébergeur**. §12.1 les exige. |
| **Témoignages / chiffres clients** | ⛔ **BLOQUANT** | Absents (`TODO-PM`). Interdiction absolue d'en inventer (§2.10). |
| Droits maîtrisés sur images/polices | ✅ | Aucune image tierce ; polices Inter/Fraunces auto-hébergées (licences libres) ; logos de sources officielles volontairement **non affichés** (décision actée) |
| CGV / CGU | ⛔ **ABSENTES** | Le site présente des offres payantes : §12.1 exige des **CGV accessibles avant engagement**. Aucune page CGV/CGU n'existe. |

## 8. RGPD

| Item | Verdict | Preuve |
|---|---|---|
| Aucun traceur avant consentement | ✅ | **Aucun script tiers** sur les pages publiques (grep sur `src="http…"` → vide) |
| « Refuser » aussi accessible qu'« Accepter » | ✅ | Deux boutons de même taille (`consent#refuse` / `consent#accept`), choix modifiable via « Gérer les cookies » en pied de page |
| **Export et suppression des données utilisateur** | ⛔ **NON IMPLÉMENTÉ** | Aucun contrôleur ne permet l'export ni la suppression du compte. §17.1 et §25 l'exigent. |
| **Tableau donnée → finalité → base légale → durée** | ⛔ **ABSENT** | `docs/` ne contient que `conflits-graphisme.md`. La politique de confidentialité renvoie à des durées « à préciser ». |
| Durées de conservation appliquées techniquement | ⛔ **NON FAIT** | Aucune purge/anonymisation automatique. |
| Aucune donnée personnelle dans logs/analytics | ✅ | Sentry configuré avec `send_default_pii: false` ; aucun analytics |

## 9. Sécurité

| Item | Verdict | Preuve |
|---|---|---|
| En-têtes de sécurité | ✅ | CSP (enforce), `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Referrer-Policy` |
| HTTPS + HSTS | ⚠️ PARTIEL | HTTP → HTTPS en 308 (Caddy) ; HSTS configuré **en prod uniquement** (`when@prod`), donc non observable ici. Certificat réel = `TODO-PM`. |
| Rate limiting | ✅ | `login_throttling: 5` + limiteurs `lead_form`, `registration`, `client_error` |
| Mots de passe hachés Argon2id | ✅ | En base : **13/13** comptes en `$argon2id$` |
| MFA sur les comptes admin | ✅ | TOTP obligatoire (`Enforce2faSubscriber`, 4 tests unitaires) |
| Routes sensibles protégées | ✅ | `/app/dashboard` anonyme → `302 /connexion` ; `access_control` sur `^/app`, `^/onboarding`, `^/mon-compte` ; `EntrepriseVoter` pour l'appartenance |
| Cloisonnement des données par entreprise | ✅ | Les 8 écrans passent par `findForEntreprise()` (relation `entreprise` non-nullable sur les 7 entités de données) |
| `composer audit` sans vulnérabilité | ✅ | `No security vulnerability advisories found` |
| Tests d'accès interdits (utilisateur A vs B) | ⚠️ NON TESTÉ | Le cloisonnement est structurel (requêtes filtrées) mais **aucun test automatisé** ne le prouve. À ajouter. |

## 10. E-commerce

**Sans objet** : le site ne vend pas en ligne (pas de tunnel de paiement). ⚠️ Mais il **affiche des prix** → les CGV restent obligatoires (voir §7).

## 11. Exploitation & pilotage

| Item | Verdict | Preuve |
|---|---|---|
| `/health` répond | ✅ | `{"status":"ok","database":"ok"}` — 200 |
| Suivi des erreurs serveur et front | ✅ (câblé) | Sentry (PII off) + collecteur `/log/client-error` (204 / 400) ; **`SENTRY_DSN` vide** → inactif tant que le PM ne fournit pas le projet Sentry |
| Surveillance uptime + alertes | ⛔ **NON CONFIGURÉE** | Aucun service externe (nécessite un domaine public) |
| **Sauvegarde BDD chiffrée + restauration testée** | ⛔ **ABSENTE** | Aucune sauvegarde configurée, **aucun test de restauration**, **RPO/RTO non écrits**, **PRA non documenté**. §22/§25 l'exigent. |
| **Plan de mesure / analytics** | ⛔ **ABSENT** | `docs/plan-mesure.md` inexistant, aucun analytics, donc **aucune conversion mesurée** et aucune alerte de chute. |
| Emails transactionnels | ⚠️ PARTIEL | Envoi fonctionnel prouvé (Messenger async → 2 emails corrects dans Mailpit) ; **SPF/DKIM/DMARC non configurés** (DNS, `TODO-PM`) |
| **Test de fumée post-déploiement** | ⛔ **ABSENT** | Aucun script |
| **Mises à jour de dépendances automatisées** | ⛔ **ABSENTES** | Ni Renovate ni Dependabot |
| Pooling de connexions BDD | ✅ (sans objet) | Instance unique ; à revoir en multi-instances |
| Règles d'archivage/purge des tables qui grossissent | ⛔ **NON DÉFINIES** | Ni pour les logs, ni pour les données expirées |
| Gouvernance des contenus | ⛔ **ABSENTE** | `docs/gouvernance-contenus.md` inexistant (nécessaire dès qu'un non-développeur publie) |

## 12. Recette visuelle & registre des conflits (exigence propre à R5)

| Item | Verdict |
|---|---|
| Comparaison page par page avec la baseline R0 aux 4 largeurs | ⛔ **NON FAITE** (navigateur inaccessible) |
| Éléments dynamiques rejoués un par un selon l'inventaire R2 | ⚠️ PARTIEL — vérifiés au fil des lots via `javascript_tool`, mais **pas dans une passe de recette formelle** |
| Registre `0-R-V` soldé (aucune ligne ouverte) | ⛔ **NON SOLDÉ** — `conflits-graphisme.md` : **CG-01 en attente d'arbitrage du chef de projet** |

---

## Synthèse : ce qui bloque la mise en ligne

### A. Ne dépend que du chef de projet (5)
1. **Témoignages et chiffres clients réels** — interdiction d'inventer (§2.10).
2. **Email de contact, directeur de la publication, hébergeur (UE)** — mentions légales incomplètes.
3. **CGV/CGU** — contenu juridique à fournir/valider (le site affiche des prix).
4. **Domaine + hébergeur + certificat** — conditionne HTTPS réel, HSTS, Search Console, uptime, SPF/DKIM/DMARC.
5. **Arbitrage CG-01** (contraste ambre) pour solder le registre 0-R-V.

### B. Travail technique restant — ✅ **SOLDÉ le 2026-07-30**

Les quatre bloquants techniques identifiés par cette recette ont été traités et vérifiés
(détails dans les documents cités) :

| # | Bloquant | État | Preuve |
|---|---|---|---|
| 6 | Export et suppression des données + tableau des traitements + purges | ✅ levé | Page « Mes données », `app:purger-donnees`, `traitements-donnees.md`, 4 tests fonctionnels |
| 7 | Sauvegardes chiffrées + restauration testée + RPO/RTO + PRA + rollback | ✅ levé | `exploitation.md` §3 : cycle réellement exécuté, comptages identiques avant/après |
| 8 | Plan de mesure + analytics conforme | ✅ levé | `plan-mesure.md`, contrôleur `tracking` unique, conversions annoncées par le serveur |
| 9 | Test de fumée + Dependabot + règles d'archivage | ✅ levé | `bin/test-de-fumee.sh` (12/12), `dependabot.yml`, `exploitation.md` §7 |

Découvertes au passage, corrigées :

- **L'e-mail des prospects était journalisé en clair** (§2.6 / §17.1) → remplacé par une
  empreinte SHA-256 non réversible. Cette recette ne l'avait pas vu au premier passage.
- **La CI n'avait aucune base de données** : aucun test fonctionnel n'était donc possible.
  PostgreSQL ajouté ; la couverture passe de 20 à 27 tests.
- `/app` affichait encore « sera construit lors d'un prochain lot » alors que l'espace
  existe → devenu le hub de compte.

Restent, hors périmètre de ces quatre lots, deux points d'exploitation dépendant de
l'hébergeur (`TODO-PM`) : la **copie des sauvegardes hors site** et la **planification
supervisée des tâches** (sauvegarde, purge RGPD).

### C. Recette impossible sans préproduction (à replanifier)
- **Lighthouse / Core Web Vitals** par gabarit (aucun budget §8.1 prouvé à ce jour).
- **Accessibilité : clavier + lecteur d'écran + axe**, sur le site public **et** sur `/app`.
- **Recette visuelle 4 largeurs** vs baseline R0, et rejeu formel des éléments dynamiques.
- `docker compose up` sur machine vierge.
- Tests d'accès inter-entreprises automatisés.

### Prérequis transverse — ✅ **FERMÉ le 2026-07-30**
- Le gate **R0** est clos : dépôt privé `github.com/MomoL3M/RAF-360`. `main` = projet
  Symfony, branche `baseline-nextjs-gelee` + tag `avant-mise-en-conformite` = existant gelé.
  Vérifié avant publication : aucun secret dans les fichiers versionnés.
- Conséquence : la **CI tourne enfin** (elle n'avait jamais été exécutée).

---

## Corrections apportées pendant cette recette

| Correction | Règle |
|---|---|
| `mixed` échappatoire retiré du collecteur d'erreurs front | §2.2 |
| DQL sorti du service de peuplement → `deleteForEntreprise()` dans les 7 repositories | §14.1 |
| Pages **404 et 500 soignées** créées (+ 2 tests de rendu) | §5.6 |

Qualité après corrections : **CS-Fixer 0 · PHPStan niveau 6 : 0 · lint:twig 42 · PHPUnit 20/20 · composer audit propre**.
