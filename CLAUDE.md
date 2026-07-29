# CLAUDE.md — Standard LINDBERGH FORMATION pour sites Symfony 7.4 (v3.1-R)
# GABARIT GÉNÉRIQUE : REPRISE D'UN SITE COMMENCÉ AVEC CLAUDE CODE SANS CLAUDE.MD
# _(aucun standard n'était à la racine — stack et structure inconnues au départ ; cible : standard Symfony 7.4 v3.1)_

> **DÉCISION DE TRAJECTOIRE — à compléter en phase R3 :**
> - Projet : **RAF 360** (copilote financier TPE/PME — éditeur Lindbergh Formation / Groupe ARCAN, à confirmer en R1)
> - Trajectoire retenue : **chemin B (reconstruction Symfony)** — **VALIDÉE le 2026-07-23 par le chef de projet** _(nom exact à consigner)_
>
> Claude : TANT QUE ces lignes contiennent des `TODO-PM`, propose la trajectoire recommandée par l'audit en phase R3, obtiens la validation du chef de projet, consigne-la ICI et en ADAPTATIONS, puis ne redemande plus jamais. UNE FOIS complétées, la trajectoire est tranchée : toute question restante porte sur le COMMENT, jamais sur le SI.

> **Contexte de ce gabarit** : le site existant a été développé avec Claude Code SANS AUCUN CLAUDE.md à la racine. Conséquences à anticiper (l'audit R2 les vérifiera) : stack choisie librement par Claude au fil de l'eau (souvent JavaScript/React/Next, parfois autre chose), structure et règles métier dispersées, contenus possiblement INVENTÉS (témoignages, chiffres — aucune règle « contenu réel » ne s'appliquait), secrets possiblement dans le code, base de données de type inconnu, git parfois absent. Ce fichier se place à la racine du dépôt existant et pilote TOUTE la mise en conformité vers le standard Symfony 7.4 — soit sur place (chemin A, si l'existant est déjà en PHP/Symfony), soit par reconstruction (chemin B, le cas le plus fréquent).

> 🎨 **RÈGLE ISO-GRAPHISME & ISO-DYNAMIQUE — NE PAS RÉINTERPRÉTER LE DESIGN** : la mise en conformité (chemin A) comme la reconstruction (chemin B) sont TECHNIQUES, pas visuelles. Le site conforme doit reproduire FIDÈLEMENT le graphisme, la mise en page et les éléments dynamiques du site actuel : mêmes couleurs (valeurs exactes), mêmes polices, mêmes espacements, même structure de pages, mêmes animations et comportements interactifs (menus, carrousels, accordéons, onglets, filtres, modales, transitions). La référence n'est PAS une maquette idéale ni le « goût » de Claude : c'est le site actuel, capturé en phase R0 (baseline visuelle). Toute divergence visuelle ou comportementale non arbitrée est un DÉFAUT à corriger. Si une règle du standard entre en conflit avec cette fidélité, Claude NE tranche PAS seul : il inscrit le point au registre des conflits (section 0-R-V) et le chef de projet choisit. Seule exception : la fidélité visuelle ne protège JAMAIS un contenu invérifiable — un témoignage inventé se retire même s'il « faisait partie du design » (vérifications R2).

> Ce fichier est le contrat que tout contributeur (humain ou IA) DOIT respecter dans ce dépôt. Il définit le standard LINDBERGH FORMATION appliqué à un projet EXISTANT, démarré sans ce fichier (cible : Symfony 7.4). Il garantit un site excellent sur toutes les dimensions de la grille d'audit interne (600 critères, 30 domaines) : point de vue client (UX, design, vitesse, confiance), point de vue business (conversion, contenus, pilotage), point de vue technique (sécurité, SEO, accessibilité, scalabilité, exploitation) et conformité (RGPD, juridique, éco-conception). Lis-le ENTIÈREMENT avant d'écrire la moindre ligne de code.

> **ÉDITION REPRISE — stack inconnue** : les sections 1 à 26 ci-dessous décrivent la CIBLE (le standard Symfony 7.4 v3.1). Le protocole 0-R détermine d'abord ce qui existe (audit + inventaire), puis la trajectoire : chemin A (mise en conformité par lots, sur place) si l'existant est en PHP/Symfony exploitable, chemin B (l'existant est gelé et sert de SOURCE à une reconstruction Symfony dans un nouveau dépôt) sinon. À la fin, le projet vit sous le standard Symfony, et l'éventuel ancien dépôt est archivé.
>
> **Note pour le chef de projet** : tu n'as pas besoin de comprendre les détails techniques de ce fichier. Place-le à la racine de ton projet existant sous le nom exact `CLAUDE.md`, ouvre Claude Code à la racine, et écris simplement : « Lis le CLAUDE.md et démarre le protocole de reprise (0-R) ». Claude te guidera phase par phase. Ton rôle : remplir la section 1 (Fiche projet), fournir les vrais contenus (textes, photos, mentions légales, preuves), tester le site après chaque lot, et prendre les décisions que Claude te soumettra (notamment le choix de trajectoire, phase R3). Règles permanentes : ne supprime JAMAIS ce fichier, ouvre toujours Claude Code à la racine du projet, et surveille le bloc ADAPTATIONS — il doit se vider avec le temps, pas se remplir. La grille d'audit 600 critères reste ton outil de contrôle final.

---

## 0. MODE D'EMPLOI DE CE FICHIER

Ce standard s'applique à un projet EXISTANT démarré sans lui : l'écart entre l'état actuel et les règles ci-dessous est normal au départ — il est mesuré (audit, phase R2), déclaré (bloc ADAPTATIONS) et résorbé par lots (phase R4) selon le protocole de reprise (section 0-R). **Dès maintenant, tout NOUVEAU développement respecte intégralement le standard** ; l'existant, lui, est mis en conformité progressivement. Toute déviation volontaire est déclarée dans le bloc **ADAPTATIONS** avec une justification d'une ligne. Ce bloc doit rester honnête et à jour.

**Priorité des règles en cas de conflit** : 1) Sécurité et RGPD → 2) Accessibilité → 3) UX et performance → 4) SEO et conversion → 5) Tout le reste. On ne sacrifie JAMAIS un niveau supérieur pour un niveau inférieur. La sobriété numérique (section 18.4) s'arbitre avec l'accessibilité et le besoin métier, jamais contre eux.

**Correspondance avec la grille d'audit 600 critères** : les critères P0 de la grille sont traités ici comme BLOQUANTS (checklist section 25), les P1 comme obligatoires sauf dérogation en ADAPTATIONS, les P2 comme recommandés (ils distinguent un site excellent d'un site correct).

**Référentiels de référence** : WCAG 2.2 / RGAA (accessibilité), Core Web Vitals (performance), Google Search Essentials (SEO), OWASP ASVS / Top 10 (sécurité), CNIL / RGPD (données), W3C Web Sustainability Guidelines (éco-conception). En cas de doute sur une exigence, ces référentiels font foi.

## ⚠️ ADAPTATIONS PAR RAPPORT AU STANDARD LINDBERGH FORMATION

> Liste ici chaque écart entre ce projet et le standard. Vide = le projet suit le standard à la lettre. À chaque brique installée, supprime la ligne correspondante.

- **Projet démarré AVANT le standard — mise en conformité selon `docs/audit-conformite.md` (protocole 0-R). Phase en cours : R4-B, lot conformité cible (SEO/GEO + sécurité + RGPD).** Faits : R0/R1/R2/R3 ✅, R4-B socle + schéma + auth (Argon2id) + site public marketing complet + 9 écrans `/app` premium + onboarding 3 étapes ✅. `TODO-PM` bloquants avant mise en ligne : témoignages/chiffres réels, email de contact, hébergeur UE, validation juridique des pages légales. _(mettre à jour au fil de l'avancement)_
- **Existant identifié (R0/R2)** : Next.js 16 / React 19 / TypeScript, données 100 % fictives, aucune auth, aucune BDD connectée → chemin B. Le dépôt Next.js est **GELÉ** (baseline/spécification). Nouveau dépôt Symfony : **`../raf360-symfony`**.
- **ISO-GRAPHISME acté** : le site conforme reproduit fidèlement le graphisme, la mise en page et les éléments dynamiques du site existant (baseline visuelle R0 = référence contractuelle de recette). Les conflits éventuels avec le standard sont arbitrés point par point via le registre 0-R-V — aucun changement visuel silencieux.
- **Base de données : type déterminé en R2, stratégie selon le cas** — PostgreSQL → CONSERVÉE telle quelle (Doctrine mappée sur le schéma existant, zéro migration de données) ; MySQL/SQLite/fichiers/BaaS → reprise des données planifiée : import initial contrôlé + gel court + synchronisation delta à la bascule (JAMAIS une copie unique faite plusieurs jours avant).
- **Exposition actuelle : AUCUNE** (pas de domaine public, pas de trafic, pas de campagne — cf. `docs/etat-des-lieux.md`). → bascule libre, aucune 301 à préserver. _(à re-confirmer par le chef de projet)_
- _(après l'audit R2, lister ici les écarts majeurs restants, puis effacer chaque ligne au fur et à mesure des lots)_
- **Installé** : Twig, Symfony UX (Stimulus/Turbo), AssetMapper, Doctrine ORM, Validator, SecurityBundle (Argon2id), RateLimiter, CI (GitHub Actions), Docker (FrankenPHP + PostgreSQL 16 ; **extension GD WebP/AVIF ajoutée au Dockerfile**), **NelmioSecurityBundle (CSP + clickjacking DENY + nosniff + Referrer-Policy ; HSTS en prod)**, `robots.txt` (+ robots IA autorisés), `llms.txt`, `sitemap.xml`, JSON-LD `Organization`, canonical par page, endpoint `/health`, bandeau de consentement cookies, **LiipImagineBundle (jeux WebP `content_*`)**, **Sentry (sentry/sentry-symfony, PII off, prod via `SENTRY_DSN`)**, **emails transactionnels (service `LeadNotifier` + gabarits + Messenger async ; Mailpit en dev)**, **collecteur d'erreurs front (`/log/client-error` + beacon)**, **accessibilité WCAG 2.2 du site public (lien d'évitement, focus clavier, contraste `--gold-ink`)**. **Reste à installer** : test de charge, PWA (si retenu).
- **`TODO-PM` observabilité/accessibilité** : `SENTRY_DSN` (projet Sentry UE) ; expéditeur/destinataire d'email réels + **SPF/DKIM/DMARC** (DNS, dépend du domaine) ; **recette a11y humaine** (clavier + lecteur d'écran + axe/Lighthouse — bloquée en local par le certificat auto-signé) ; a11y de `/app` + onboarding (templates autonomes, non couverts par la passe site public) ; déclaration d'accessibilité si le statut de l'organisation l'exige. Arbitrage contraste ambre : `docs/conflits-graphisme.md` (CG-01, à confirmer par le PM).
- **Choix §8.2 (assets de marque statiques)** : `og:image` (`raf360-og.jpg`, carte 1200×630, 23 Ko) et logo JSON-LD (`raf360-logo.png`, réduit 850→198 Ko) sont des fichiers statiques optimisés, PAS des sorties LiipImagine — la conversion de format LiipImagine garde l'extension d'URL d'origine (MIME incohérent pour scrapers) et le PNG GD compresse mal. LiipImagine sert les images de CONTENU responsives (WebP).
- **Dérogation §16.2 (CSP `'unsafe-inline'`)** : `script-src`/`style-src` autorisent l'inline — requis par l'importmap AssetMapper, le petit script `.js` de `base.html.twig`, les nombreux styles inline et les écrans `/app` autonomes. XSS déjà couvert par l'auto-échappement Twig + aucun HTML utilisateur. Cible : nonces + externalisation, puis retrait d'`unsafe-inline`.
- **Écart §9.1 (sitemap sans bundle)** : `sitemap.xml` généré par un contrôleur natif depuis le routeur (jeu d'URL restreint et statique) plutôt que `presta/sitemap-bundle` — §2.5 (pas de dépendance sans besoin clair). À rebasculer sur le bundle quand le blog aura de nombreux articles.
- **Dérogation §16.2 (onboarding sans mot de passe — MVP)** : `/onboarding` crée un compte ROLE_USER et ouvre la session sans authentification (accès express démo). Inscription sécurisée (email + mot de passe, MFA admin) à faire avant mise en ligne.

---

## 0-R. PROTOCOLE DE REPRISE D'UN SITE SANS STANDARD (instructions pour Claude Code)

> Ce dépôt contient le site EXISTANT, développé sans standard. Claude, tu exécutes ce protocole dans l'ordre — **si la trajectoire est actée dans l'encadré d'en-tête, exécute sans redemander ; sinon, obtiens-la en phase R3 et consigne-la**. **Au début de CHAQUE session, vérifie où en est la migration** (ligne de phase dans ADAPTATIONS + `docs/audit-conformite.md`) et rappelle au chef de projet la phase en cours et la prochaine étape. Règles permanentes : tout NOUVEAU développement respecte le standard cible ; le site actuel RESTE EN LIGNE et fonctionnel jusqu'à la bascule ou la fin des lots ; en chemin B, l'existant est GELÉ (aucune évolution fonctionnelle — seuls les correctifs de sécurité critiques, notés dans `docs/modifs-pendant-migration.md`).

### Phase R0 — Sécuriser l'existant (OBLIGATOIRE, avant toute action)
1. Git : **initialise le dépôt s'il n'existe pas** (fréquent sur un projet sans standard) ; sinon committe tout l'état courant. Crée le tag `avant-mise-en-conformite` daté.
2. **Sauvegarde HORS de la machine** : pousse le dépôt (avec ses tags) vers un dépôt distant PRIVÉ (compte de l'entreprise — guide le chef de projet pour le créer). Propose en complément une copie du dossier projet vers un autre emplacement. Rappelle que la sécurité vient de git et des sauvegardes — pas des sessions Claude Code.
3. **Base de données : identifie d'abord ce qui existe** (PostgreSQL ? MySQL ? SQLite ? fichiers JSON ? service externe type Supabase/Firebase ?) puis fais un dump/export complet daté, stocké hors de la machine ET hors du serveur d'hébergement.
4. **Baseline visuelle et dynamique (la référence de fidélité)** : capture d'écran PLEINE PAGE de chaque page publique du site actuel aux 4 largeurs (375 / 768 / 1440 / 2560 px), rangée dans `docs/baseline-visuelle/` — depuis l'environnement où le site tourne réellement (en ligne ou local) ; copie des sources du design (fichiers CSS/styles, thème, polices, images clés, quelle que soit la stack) ; **inventaire de CHAQUE élément dynamique** dans `docs/baseline-visuelle/dynamique.md` : où il se trouve, ce qui le déclenche, ce qu'il fait exactement (menu mobile, carrousels, accordéons, onglets, filtres, modales, animations au scroll, compteurs, transitions de page…). Ce dossier est la référence CONTRACTUELLE de la recette visuelle (R4 et R5) : sans lui, la fidélité ne peut pas être prouvée.
5. État des lieux dans `docs/etat-des-lieux.md` : stack et versions RÉELLES (framework, langage), pages et routes publiques, base de données (type, où vivent les données, volumes), authentification éventuelle, mode de déploiement et d'hébergement, existence d'un domaine/trafic/campagnes (`TODO-PM` à confirmer — voir ADAPTATIONS).
6. Ne modifie RIEN d'autre pendant cette phase.

### Phase R1 — Poser les fondations du contrat
1. Remplis la section 1 (Fiche projet) avec le chef de projet — aucun standard n'était en place : ni fiche projet, ni conversion n°1, ni personas n'existent. C'est le moment de les définir, ils piloteront la suite.
2. Mets à jour la ligne de phase dans ADAPTATIONS (R1 → R2).

### Phase R2 — Audit d'écarts ET inventaire de transfert (AUCUNE modification)
> Double objet : mesurer l'écart au standard cible ET recenser ce qui a de la valeur. Produis `docs/audit-conformite.md` :
1. **Audit d'écarts** : compare l'existant à chaque section du standard — règle concernée, ce qui existe (fichier), gravité (**BLOQUANT** : sécurité, RGPD, légal, secrets, données à risque / **MAJEUR** : accessibilité, performance, SEO, structure / **MINEUR** : conventions, finitions), effort.
2. **Vérifications en PRIORITÉ ABSOLUE** (un site sans standard n'avait aucun garde-fou) : secrets ou clés API dans le code ; entrées non validées ; données personnelles exposées ; pages légales absentes si le site est en ligne ; **contenus de réassurance INVÉRIFIABLES** (témoignages, avis, chiffres, partenaires possiblement inventés par Claude en l'absence de règle « contenu réel ») → chaque contenu non prouvé est marqué `TODO-PM : vérifier ou supprimer` — un contenu inventé en ligne est une pratique commerciale trompeuse. **Point critique sur un site EN LIGNE = correction IMMÉDIATE, avant la suite.**
3. **Inventaire de transfert** (ce qui se garde, quel que soit le chemin) : pages & URLs (table de correspondance pour les 301 si domaine public) ; base de données (schéma documenté table par table + stratégie selon le type — voir ADAPTATIONS) ; règles métier (où qu'elles soient dans le code : le code source sert de SPÉCIFICATION à re-exprimer, jamais de copier-coller) ; design **(au service de l'iso-graphisme)** : tokens extraits à l'EXACT depuis le code existant (couleurs hex réelles, familles et tailles de police, espacements, rayons, ombres — pas des approximations) + **table de correspondance des éléments dynamiques** inventoriés en R0 → implémentation cible (contrôleur Stimulus, Turbo Frame/Stream, ou CSS pur) avec le MÊME rendu, le MÊME déclencheur, le MÊME ressenti ; ce qui ne peut pas être reproduit à l'identique est inscrit au registre des conflits (0-R-V) ; contenus VALIDÉS par le chef de projet ; config et comptes (variables SANS leurs valeurs, services tiers).
4. Synthèse : écarts par gravité, les 5 risques les plus urgents, et ta recommandation de trajectoire chiffrée pour R3.

### Phase R3 — Trajectoire (à acter dans l'encadré d'en-tête)
- **Chemin A — Mise en conformité sur place** : UNIQUEMENT si l'existant est déjà en PHP/Symfony avec un code exploitable (rare pour un projet Claude Code sans standard). On garde le dépôt et on traite les lots R4-A.
- **Chemin B — Reconstruction Symfony** (le cas le plus fréquent : existant en JavaScript/React/Next/Nuxt, no-code, ou code inexploitable) : nouveau dépôt Symfony 7.4 avec le standard « de zéro » (`CLAUDE_SITE_INTERNET_LF_v3_Symfony.md` renommé `CLAUDE.md`) à sa racine ; l'existant devient la SOURCE (inventaire R2) ; lots R4-B.
- Critère indicatif : moins de ~30 % de l'existant récupérable EN L'ÉTAT → chemin B. Présente les deux options chiffrées, obtiens la validation écrite du chef de projet, complète l'encadré d'en-tête et ADAPTATIONS — la question ne sera plus jamais reposée. Ne décide JAMAIS seul.

### Phase R4-A — Lots de mise en conformité sur place (si chemin A)
Un lot = une branche + une Merge Request + le site testé par le chef de projet + un tag (`conformite-lot-N`) + mise à jour de l'audit et d'ADAPTATIONS. Ordre NON négociable : 1. **Sécurité & RGPD** (secrets au coffre, Validator partout, HTTPS, NelmioSecurity, cookies, pages légales, logs purgés) → 2. **Fondations** (structure section 3, `strict_types` + PHPStan, couches contrôleurs/services/repositories, Doctrine indexée et migrée, code mort supprimé) → 3. **Accessibilité** (section 10) → 4. **UX/UI/Responsive** (sections 5–7) → 5. **Performance & SEO/GEO** (LiipImagine, cache HTTP, métadonnées par page, sitemap, robots + robots IA, `llms.txt`, redirections 301 — sections 8–9) → 6. **Exploitation** (CI bloquante, endpoint de santé, monitoring, sauvegardes testées, analytics et plan de mesure — sections 20–24). Jamais de changement d'URL publique sans 301. **Iso-graphisme en chemin A** : ces lots corrigent la MÉCANIQUE (sémantique HTML, focus, états, structure du code, performance), pas l'apparence — le graphisme et les comportements dynamiques existants sont conservés à l'identique (comparaison avec la baseline R0 à chaque lot) ; tout point où une règle du standard exigerait un changement VISIBLE passe par le registre 0-R-V, jamais par une correction silencieuse.

### Phase R4-B — Reconstruction Symfony (si chemin B, dans le NOUVEAU dépôt)
Un lot = une branche + une Merge Request + test par le chef de projet + inventaire mis à jour (éléments transférés cochés). Ordre :
1. **Socle** : projet Symfony 7.4 conforme (structure, Docker, CI, qualité) ; **base de données selon la stratégie d'ADAPTATIONS** — PostgreSQL existante : connexion directe + entités Doctrine mappées sur le schéma en place (`doctrine:schema:validate` passe, zéro migration de données) ; autre type : schéma Doctrine propre + script d'import contrôlé (comptages, intégrité), le delta étant rejoué à la bascule (R5).
2. **Règles métier & auth** : re-exprimées depuis l'inventaire R2 (le code source d'origine est la spécification — pas de copier-coller d'un autre langage) ; vérifier la compatibilité des mots de passe hachés existants avec le hasher Symfony configuré, sinon plan de réinitialisation communiqué aux utilisateurs.
3. **Pages & contenus — À L'IDENTIQUE visuellement** : gabarits Twig reproduisant la mise en page EXISTANTE (baseline R0), tokens CSS extraits en R2 (mêmes valeurs), mêmes polices auto-hébergées, métadonnées par page, parité de contenu page par page — UNIQUEMENT les contenus validés en R2 (les `TODO-PM : vérifier ou supprimer` ne passent JAMAIS dans le nouveau site sans validation) ; les éléments dynamiques sont reconstruits selon la table de correspondance R2 (Stimulus/Turbo) avec le MÊME comportement perçu. **Recette visuelle par page AVANT de clore le lot** : comparaison côte à côte avec les captures baseline aux 4 largeurs — l'écart toléré est de l'ordre du rendu navigateur (anti-crénelage, arrondi de sous-pixel), PAS de la nuance de couleur, du changement de police, de l'espacement modifié ou du bloc déplacé. Divergence non arbitrée au registre 0-R-V = défaut à corriger.
4. **Conformité cible** : tout ce que l'absence de standard n'avait jamais couvert — accessibilité, RGPD/consentement, pages légales, GEO (robots IA, `llms.txt`), analytics et plan de mesure, observabilité (sections 9–21).
5. **Parité finale** : chaque URL publique a son équivalent (ou sa 301 si domaine public) ; chaque formulaire testé de bout en bout ; les données reprises s'affichent correctement ; **la parité VISUELLE et DYNAMIQUE est validée page par page par le chef de projet** (côte à côte avec la baseline R0, éléments dynamiques manipulés un par un) — c'est lui qui prononce « conforme ».
Après chaque lot : résumé au chef de projet en langage NON technique.

### Phase R5 — Recette & bascule
1. Recette complète sur préproduction (noindex) : checklist section 25 — y compris le bloc GEO — avec preuve par item. **Plus la recette visuelle finale** : chaque page comparée côte à côte à sa capture baseline R0 (4 largeurs), chaque élément dynamique rejoué selon l'inventaire, et le registre des conflits 0-R-V SOLDÉ (chaque ligne arbitrée option A ou B par le chef de projet — aucune ligne ouverte).
2. Bascule selon le cas confirmé en ADAPTATIONS : **sans domaine public** → on éteint l'ancien service et on expose le nouveau (avec un vrai domaine et HTTPS, exigés par le standard — `TODO-PM`) ; **avec domaine/trafic** → bascule DNS avec 301, baisse de TTL, jour calme, surveillance renforcée — et si des campagnes payantes tournent : URLs d'atterrissage conservées à l'identique, tracking repris sans renommage d'événements, campagnes gelées pendant la semaine de bascule.
3. **Si les données ont été migrées** (base non PostgreSQL) : gel court des écritures, rejeu du delta, vérification des comptages AVANT d'exposer le nouveau site — aucune vente ni donnée créée entre l'import initial et la bascule ne doit être perdue. Si la base a été conservée : les deux sites cohabitent sans risque (même base). Test de fumée + parcours principal réel après bascule.

### Phase R6 — Fermeture
En chemin B : l'ancien dépôt est ARCHIVÉ (jamais supprimé) avec son tag ; l'ancien service est arrêté après 2–4 semaines de recul. Dans tous les cas : accès inventoriés dans `docs/infrastructure.md`, et le projet vit désormais sous son CLAUDE.md Symfony — la CI bloque les fusions non conformes, la checklist s'applique avant chaque mise en production, ADAPTATIONS se vide avec le temps.

### 0-R-V — Registre des conflits graphisme ↔ standard (arbitrage par le chef de projet)

> Conserver le graphisme et le dynamisme du site existant est COMPATIBLE avec le standard dans l'immense majorité des cas : le standard impose des règles de QUALITÉ (contrastes, performance, accessibilité, honnêteté), pas un style graphique. Mais des conflits PONCTUELS peuvent exister — d'autant plus probables ici qu'aucune règle n'encadrait le design d'origine. Voici la règle — elle est stricte parce que le pire des comportements serait de « corriger » le design en silence.

- **Claude ne modifie JAMAIS silencieusement le design pour « se conformer »** : tout point où la fidélité au site existant contredit une règle du standard est inscrit dans `docs/conflits-graphisme.md` (le registre) : élément concerné, page(s), règle du standard en conflit, impact concret, options proposées. Puis Claude le SOUMET au chef de projet — il ne tranche pas seul. Le registre vaut pour les DEUX chemins (lots sur place comme reconstruction).
- **Conflits typiques à vérifier systématiquement** : contraste texte/fond sous le seuil AA (section 10) ; carrousel à défilement automatique sans bouton pause ; animations qui ignorent `prefers-reduced-motion` ; zoom bloqué ou tailles en pixels ; élément dynamique qui injecte du contenu SEO côté client (règle 4.3) ; images du design trop lourdes pour les budgets (section 8) ; libellés de menu « créatifs » (section 5.2) ; couleur d'accent utilisée hors CTA (section 6.1) ; pop-up qui masque le contenu sur mobile (section 5.4).
- **Arbitrage point par point — deux options présentées HONNÊTEMENT** :
  - **Option A — fidélité** : on garde l'existant tel quel ; l'écart est déclaré en ADAPTATIONS avec la mention « dérogation iso-graphisme » et la règle concernée ;
  - **Option B — conformité** : on applique le standard ; le changement visuel est décrit AVANT d'être fait (avec aperçu quand c'est possible) et documenté au registre.
  - Quand elle existe, Claude propose d'abord la **troisième voie : l'ajustement minimal invisible** — assombrir une couleur de quelques pourcents pour atteindre le contraste AA, ajouter un bouton pause discret au carrousel, respecter `prefers-reduced-motion` sans changer l'animation par défaut, compresser l'image sans la remplacer. La fidélité PERÇUE est conservée ET la règle est respectée : c'est presque toujours possible.
- **Trois familles NON négociables (jamais d'option A)** : la sécurité et le RGPD (niveau 1 des priorités — ex. un traceur chargé avant consentement ne survit pas, même s'il pilotait un élément dynamique) ; les mécanismes trompeurs (dark patterns) si le site en contenait ; et les **contenus invérifiables** (témoignages, avis, chiffres possiblement inventés — vérifications R2) : un contenu non prouvé se retire ou se remplace, même si le bloc qui l'affichait était joli. Là, Claude explique pourquoi il n'y a pas le choix, et applique le standard.
- **Compléter n'est pas trahir** : les états d'interface manquants (section 4.4), la 404 soignée, le focus visible, les comportements que le site N'AVAIT PAS s'ajoutent SANS passer par le registre — à condition de reprendre strictement les tokens et le style existants (un skeleton aux couleurs de la charte actuelle, pas un nouveau langage graphique).

**Pièges interdits pendant toute la reprise** : tout réécrire d'un coup sans audit ni inventaire ; faire confiance aux contenus existants sans vérification (ils ont pu être inventés) ; recopier du code d'un autre langage au lieu de re-exprimer les règles depuis l'inventaire ; migrer les données par une copie unique datée (le delta se perd) ; changer une URL publique sans 301 ; toucher un site en ligne sans la phase R0 ; vider ADAPTATIONS pour faire joli ; croire que poser ce fichier corrige l'existant (il pilote la mise en conformité — l'audit et les lots la réalisent) ; **« moderniser », « épurer » ou réinterpréter le graphisme au passage** (la reprise est technique : le design cible est celui de la baseline R0) ; **remplacer un élément dynamique par une version « plus simple »** sans arbitrage au registre 0-R-V ; recréer les couleurs et polices de mémoire au lieu de les extraire du code existant ; « se conformer » au standard en changeant le design sans le dire.

---

## 1. FICHE PROJET (à remplir par le chef de projet)

> Cette section pilote toutes les décisions UX, SEO et conversion. Claude DOIT demander au chef de projet de la compléter avant de construire les pages. Tant qu'elle est incomplète, marquer les valeurs manquantes `TODO-PM` et NE PAS inventer.

### 1.1 Stratégie et objectifs
- **Type de site** : à définir (vitrine / vente de formations / SaaS / catalogue / e-commerce / app avec authentification…).
- **Activité de l'entreprise en une phrase** : à définir. Cette phrase devient la base du message d'accueil : un visiteur doit comprendre en 5 secondes où il est, ce que fait l'entreprise et ce qu'il peut faire.
- **Objectif prioritaire du site** : à définir et formalisé (vendre, convaincre, informer, recruter, générer des leads ou servir). **Chaque page stratégique a ensuite UN objectif principal unique, explicite et mesurable** — une page qui vise deux objectifs n'en atteint aucun.
- **Action principale attendue du visiteur (conversion n°1)** : à définir (acheter, réserver, demander un devis, s'inscrire, appeler…). Une seule action principale par site.
- **Actions secondaires (micro-conversions)** : à définir (newsletter, téléchargement de brochure, création de compte…). Elles seront mesurées séparément (section 20).
- **Positionnement différenciant** : ce qui distingue l'offre des alternatives — visible dès le premier écran, avec des avantages VÉRIFIABLES.
- **Indicateurs de succès définis AVANT le développement** : conversion, rétention, satisfaction, SEO, disponibilité — chacun avec sa cible.

### 1.2 Publics et personas
- **Cible principale et cibles secondaires** : profils, besoins, contraintes, maturité numérique, contexte de visite (mobile en mobilité ? bureau ?). Fondés sur des données ou entretiens réels quand ils existent — pas seulement sur l'intuition.
- **Les questions et objections des visiteurs** à chaque étape (prix, fiabilité, délais, garanties, sécurité) : listées par le chef de projet ; chaque page y répondra (sections 11 et 12).
- **Segments distingués** : nouveau visiteur vs récurrent, visiteur pressé vs comparateur, décideur vs utilisateur final — le site prévoit des raccourcis pour les habitués et un guidage pour les débutants ; les visiteurs pressés trouvent la réponse sans lire toute la page (résumés, sections scannables).
- **Vocabulaire** : les pages critiques utilisent les mots exacts des utilisateurs, pas le jargon interne de l'organisation.
- **Coordonnées réelles** : raison sociale, adresse, téléphone, email, SIRET → fournies par le chef de projet, jamais inventées.

### 1.3 Socle technique
- **Stack** : Symfony 7.4 fullstack — contrôleurs + templates Twig rendus côté serveur (front) + services applicatifs (back) + couche partagée — dépôt unique. Interactivité progressive via Symfony UX (Stimulus + Turbo).
- **PHP** : 8.4 (version exacte ; `composer.json` (`require: php`) et un `.php-version` DOIVENT la figer).
- **Gestionnaire de paquets** : Composer UNIQUEMENT pour PHP. Le lockfile est `composer.lock`. Les assets front (CSS/JS) passent par AssetMapper (natif Symfony, SANS build Node) — aucun npm ni yarn requis.

---

## 2. RÈGLES TRANSVERSES NON NÉGOCIABLES

> Ces règles sont STRICTES et ne se suppriment JAMAIS, quel que soit le projet. Elles sont le plancher, pas le plafond.

### 2.1 Sécurité
- Tu ne DOIS JAMAIS committer de secrets. Tous les secrets vivent dans `.env.local` / le coffre de secrets Symfony (`secrets:set`), jamais dans `.env` versionné. Lecture via l'injection de paramètres/variables d'environnement (`%env(...)%`), JAMAIS `getenv()` éparpillé dans le code.
- Tu ne DOIS JAMAIS exposer un secret serveur au navigateur. Une clé API vit côté serveur (service applicatif), jamais dans un template exposé ni dans le JS client. Seules des valeurs explicitement publiques sont injectées dans les vues.
- Tu DOIS valider TOUTE entrée externe (corps de requête, query, attributs de route, upload) avec le composant Validator (DTO + contraintes, ou Form Types) avant usage. Ne JAMAIS faire confiance aux données client. La validation front ne remplace JAMAIS les contrôles serveur.
- Tu ne DOIS JAMAIS utiliser `eval()`, la création dynamique de code, ni rendre du HTML non assaini. Le filtre Twig `|raw` est INTERDIT sauf contenu assaini (ex. `html-sanitizer` de Symfony) ET commentaire justificatif ; l'auto-échappement Twig ne se désactive jamais globalement.
- Les uploads de fichiers DOIVENT être limités en taille (contrainte `File`/`Image`), vérifiés en type MIME réel, renommés (jamais le nom fourni par l'utilisateur), et stockés hors du dossier `public/`.
- Aucune donnée sensible n'est stockée inutilement dans le navigateur (localStorage/sessionStorage limités et jamais pour des tokens ou données personnelles sensibles).

### 2.2 PHP & typage strict
- Tu DOIS activer `declare(strict_types=1);` en tête de CHAQUE fichier PHP. Le typage explicite (arguments, retours, propriétés) est OBLIGATOIRE ; on exploite les apports de PHP 8.4 (types de propriété, promotion de constructeur, enums, `readonly`).
- Le type `mixed` est INTERDIT comme échappatoire. Utilise un type précis, une union typée ou une interface. Si tu crois avoir besoin de `mixed`, c'est faux — arrête-toi et trouve le vrai type.
- Tu ne DOIS PAS masquer une erreur avec l'opérateur `@` ni un `/** @phpstan-ignore */` non justifié. Corrige le type. Le code DOIT passer l'analyse statique (PHPStan/Psalm au niveau fixé) avant tout push.

### 2.3 Limites de taille (plafonds stricts)
- Fichiers : ≤ 300 lignes. Au-delà, le fichier DOIT être découpé.
- Fonctions : ≤ 30 lignes.
- Templates Twig et classes PHP : ≤ 200 lignes. Au-delà, extraire des composants Twig (Twig Components / includes) ou des services dédiés.
- Si un refactoring pour respecter ces limites est risqué, STOP : signale-le plutôt que de produire un découpage cassé.

### 2.4 Workflow Git
- `main` est PROTÉGÉE. Ne JAMAIS pousser directement sur `main`.
- TOUT le travail se fait sur une branche, fusionnée via Merge Request, relue par un humain.
- Noms de branches : `feat/...`, `fix/...`, `chore/...`, `refactor/...`.
- Conventional Commits, description en français. Exemple : `feat(formations): ajoute le filtre par ville`.
- L'IA gère TOUTES les opérations git. Les contributeurs ne lancent pas git eux-mêmes.

### 2.5 Dépendances
- Tu ne DOIS PAS ajouter une dépendance sans besoin clair. Préfère les composants natifs de Symfony et la stack choisie.
- Chaque script tiers côté client (analytics, widget, police externe…) DOIT être justifié dans ADAPTATIONS et son coût de chargement mesuré : chaque script ralentit le site et alourdit son empreinte (sections 8 et 18.4).
- Quand tu ajoutes une dépendance, justifie-la dans le corps du commit.
- Ne JAMAIS introduire un paquet déprécié ou non maintenu. Les dépendances vulnérables sont détectées et mises à jour selon un processus défini (section 16.2).

### 2.6 Logs
- Tu ne DOIS PAS utiliser `echo`, `var_dump`, `dump()`/`dd()` ni `error_log()` dans du code committé.
- Côté serveur : logger PSR-3 (Monolog, injecté via `LoggerInterface`), JAMAIS de sortie brute.
- Côté client (Stimulus) : `console.error` toléré UNIQUEMENT dans un bloc `catch`. Tout le reste est interdit.
- Les logs ne DOIVENT JAMAIS contenir de données personnelles en clair (email, mot de passe, token, IP non anonymisée) — voir section 17 (RGPD).

### 2.7 Documentation
- Chaque route serveur, service et fonction de repository DOIT avoir un court commentaire de doc : ce qu'elle fait, ses entrées, ce qu'elle retourne/lève.
- La logique métier complexe DOIT être commentée (le « pourquoi », pas le « quoi »).
- Les arbitrages importants (image de marque vs conversion, coût vs résilience, dénormalisation…) sont consignés dans `docs/decisions.md`.

### 2.8 Docker
- Docker est OBLIGATOIRE. Le projet DOIT livrer un `Dockerfile` et un `docker-compose.yml` fonctionnels.
- Rendu serveur : le conteneur exécute PHP 8.4 (PHP-FPM) derrière un serveur web (nginx) — ou FrankenPHP —, PAS un simple hébergement statique. PostgreSQL est fourni comme service dans `docker-compose.yml`.

### 2.9 Tests (conditionnel)
- SI PHPUnit est configuré : les tests sont OBLIGATOIRES pour toute nouvelle logique métier (voir aussi section 24 — recette).
- SI aucun harnais n'existe encore : au minimum l'analyse statique (PHPStan/Psalm) et `composer validate` DOIVENT passer avant chaque push.
- Ne JAMAIS pousser du code qui échoue à l'analyse statique ou au lint.

### 2.10 Contenu réel uniquement — JAMAIS de contenu inventé
- Tu ne DOIS JAMAIS inventer : avis clients, témoignages, chiffres, partenaires, membres d'équipe, adresses, numéros de téléphone, certifications, logos de presse. Inventer ces éléments est mensonger et illégal (pratique commerciale trompeuse).
- Si un contenu de réassurance manque, insère un placeholder visible `TODO-PM: fournir les vrais avis clients` et signale-le au chef de projet.
- Les textes de démonstration (« lorem ipsum ») sont tolérés en développement mais BLOQUANTS pour la mise en ligne (voir checklist section 25).

### 2.11 Propriété intellectuelle et licences
- Chaque image, vidéo, police, icône et texte utilisé a des droits maîtrisés : création originale, licence achetée, ou licence libre COMPATIBLE avec un usage commercial (vérifier avant usage ; pas de « trouvé sur Google Images »).
- Les licences open source des dépendances sont compatibles avec l'usage prévu (attention aux licences copyleft type GPL sur du code propriétaire).
- Les crédits exigés par une licence sont affichés là où la licence l'exige.

### 2.12 En cas de doute — STOP
- Si une exigence est ambiguë, un refactoring risqué, ou une action peut casser la production, STOP et pose la question. Ne JAMAIS deviner sur les données, l'authentification, la sécurité, l'argent ou le juridique.

---

## 3. STRUCTURE DU PROJET (Symfony 7.4)

> Symfony sépare le code applicatif (`src/`), les gabarits (`templates/`) et les assets (`assets/`). Respecte strictement cette séparation en couches.

```
<projet>/
├── src/                      # CODE APPLICATIF (PHP 8.4)
│   ├── Controller/           # Contrôleurs FINS (routing par attributs #[Route])
│   ├── Entity/               # Entités Doctrine (mappées en attributs)
│   ├── Repository/           # ACCÈS DONNÉES — le SEUL endroit où le QueryBuilder/DQL vit
│   ├── Service/              # LOGIQUE MÉTIER (appelée par les contrôleurs)
│   ├── Dto/                  # Objets de transfert + contraintes de validation
│   ├── Form/                 # Form Types (formulaires serveur)
│   ├── Security/             # Voters, authenticators, gestion des accès
│   ├── EventListener/        # Écouteurs/subscribers (exécutés par requête)
│   └── Twig/                 # Extensions & composants Twig
├── templates/                # GABARITS Twig (rendus côté serveur)
│   ├── base.html.twig        # Layout racine (blocs, <head>, SEO)
│   ├── components/           # Twig Components réutilisables (design system)
│   └── bundles/TwigBundle/   # Pages d'erreur (404/500 soignées, voir 5.6)
├── assets/                   # FRONT-END (servi par AssetMapper, sans build Node)
│   ├── controllers/          # Contrôleurs Stimulus (interactivité)
│   ├── styles/               # Tokens de design + styles globaux (CSS)
│   └── app.js                # Point d'entrée (importmap)
├── config/                   # Configuration (services, packages, routes, sécurité)
│   └── packages/             # security.yaml, doctrine.yaml, nelmio_security.yaml…
├── migrations/               # Migrations Doctrine versionnées
├── public/                   # Point d'entrée web (index.php) + assets publics (robots.txt, llms.txt…)
├── translations/             # Fichiers de traduction (messages.fr.yaml…)
├── tests/                    # Tests PHPUnit (unitaires, fonctionnels)
├── docs/                     # decisions.md, plan-mesure.md, gouvernance-contenus.md,
│                             # infrastructure.md, runbook.md (exploitation & incidents)
├── composer.json
├── importmap.php             # Dépendances front (AssetMapper)
├── Dockerfile
├── docker-compose.yml
├── .php-version              # fige PHP 8.4
└── .env                      # Valeurs par défaut (les secrets vont dans .env.local / le coffre)
```

**Règles de structure :**
- Le code `src/` s'exécute côté serveur (PHP-FPM). Le code `assets/` s'exécute dans le navigateur (Stimulus). Les gabarits `templates/` produisent le HTML côté serveur. La logique métier vit dans `src/Service/`, JAMAIS dans un contrôleur ou un template.
- Les DTO et contraintes de validation (`src/Dto/`) sont partagés entre le formulaire et l'API : une seule source de vérité pour les règles de saisie.
- L'architecture doit permettre d'ajouter de nouvelles offres, langues ou zones géographiques SANS refonte majeure (composants Twig et gabarits génériques, contenus séparés de la présentation).

---

## 4. RÈGLES FRONT-END (`app/`)

### 4.1 Composants (Twig Components + Stimulus)
- Les fragments d'interface réutilisables sont des **Twig Components** (`templates/components/`) — pas des copier-coller de balisage.
- Un composant = une responsabilité. Plus de 200 lignes → découper.
- Utilise les composants Symfony UX (Turbo, Live Components, packs UX) comme briques par défaut pour l'interactivité. Ne réécris PAS à la main un comportement (modale, onglets, table triable) qu'un contrôleur Stimulus fournit déjà.
- Les propriétés d'un Twig Component DOIVENT être typées (propriétés PHP typées de la classe du composant).
- Éviter la duplication entre pages : deux pages de même nature partagent le même gabarit et les mêmes composants (structure homogène — un visiteur qui a compris une fiche formation les a toutes comprises).

**MAUVAIS (logique métier et requête dans le template) :**
```twig
{# templates/formation/show.html.twig #}
{% set formations = knp_paginate(query) %}  {# accès données depuis Twig — INTERDIT #}
```
**BON (le contrôleur prépare, Twig affiche) :**
```php
// src/Controller/FormationController.php
#[Route('/formations/{slug}', name: 'formation_show')]
public function show(string $slug, FormationService $service): Response
{
    return $this->render('formation/show.html.twig', [
        'formation' => $service->getBySlug($slug),
    ]);
}
```

### 4.2 État
- L'état applicatif vit CÔTÉ SERVEUR (session Symfony, base de données) ; le HTML rendu porte l'état de la page. L'état d'interface éphémère (onglet ouvert, menu déplié) reste LOCAL au contrôleur Stimulus concerné.
- Ne JAMAIS conserver côté client (valeurs Stimulus, `data-*`) ce qui doit rester une donnée serveur faisant autorité. Pas de duplication de l'état métier dans le navigateur.

### 4.3 Récupération de données (CRITIQUE pour le SEO)
- **Contenu nécessaire au premier rendu / SEO** (contenu de page, liste catalogue, article) → chargé par le contrôleur PHP et passé au template Twig. Le rendu est CÔTÉ SERVEUR par nature : le contenu est dans le HTML que reçoit le robot d'indexation.
- **Actions utilisateur enrichies** (soumettre sans rechargement, ajouter au panier, liker) → Turbo / requête `fetch` depuis un contrôleur Stimulus. Côté client, après interaction, là où le SEO n'a pas d'importance.

**MAUVAIS (tue le SEO — contenu injecté en JS après chargement) :**
```js
// assets/controllers/article_controller.js
async connect() {
  const html = await (await fetch(`/api/articles/${this.idValue}`)).text()
  this.element.innerHTML = html   // contenu principal invisible pour les robots
}
```
**BON (rendu serveur, le robot voit le contenu) :**
```php
// src/Controller/ArticleController.php
#[Route('/articles/{slug}', name: 'article_show')]
public function show(string $slug, ArticleService $service): Response
{
    return $this->render('article/show.html.twig', [
        'article' => $service->getBySlug($slug),
    ]);
}
```

### 4.4 États d'interface obligatoires
Chaque vue qui charge des données DOIT gérer explicitement ses états :
1. **Chargement** : skeleton de préférence (reflétant la structure réelle de la page — pas de saut de mise en page). Si l'action prend du temps, expliquer l'attente.
2. **Erreur** : message clair en français + action de secours (réessayer, retour à l'accueil). Jamais d'écran blanc ni de message technique brut. Le front gère proprement les erreurs réseau et les reprises.
3. **Vide** : message utile (« Aucune formation ne correspond à vos filtres ») + action (réinitialiser les filtres).
4. **Succès** : le contenu.
5. **Désactivé** : un élément désactivé reste lisible et son état est explicable.

### 4.5 Erreurs front capturées
- Les erreurs JavaScript de production (contrôleurs Stimulus) sont capturées et remontées avec contexte exploitable vers l'outil de suivi (section 21) — jamais silencieusement perdues.

---

## 5. UX — EXPÉRIENCE UTILISATEUR & ARCHITECTURE DE L'INFORMATION (critère n°1)

> Objectif : l'utilisateur réfléchit le moins possible. En arrivant sur n'importe quelle page, il comprend immédiatement où il est, ce que fait l'entreprise, ce qu'il peut faire et comment le faire.

### 5.1 Page d'accueil
- Au-dessus de la ligne de flottaison (sans scroller), la page d'accueil DOIT contenir : ce que fait l'entreprise (une phrase claire, pas de slogan vague), pour qui, une preuve (chiffre clé, certification, avis — réels, règle 2.10) et le CTA principal (section 12).
- La page d'accueil est un CARREFOUR : elle oriente vers les parcours prioritaires, elle ne tente pas de tout dire.
- Test de validation : un inconnu doit pouvoir dire en 5 secondes ce que propose le site.

### 5.2 Architecture de l'information
- L'arborescence est simple, stable et compréhensible par un non-spécialiste : les rubriques correspondent aux INTENTIONS des utilisateurs, pas à l'organigramme interne.
- Menu principal : 7 entrées MAXIMUM, libellés simples et concrets (« Formations », « Tarifs », « Contact ») — jamais de libellés créatifs ou ambigus. Un seul niveau de sous-menu maximum (méga-menu = catalogue volumineux uniquement, déclaré en ADAPTATIONS).
- Le menu mobile et le menu desktop suivent la MÊME logique de classement.
- Toute page importante DOIT être atteignable en 3 clics maximum depuis l'accueil.
- Les contenus proches sont regroupés (pas de doublons ni de pages quasi identiques) ; **aucune page orpheline** : toute page publiée est reliée au maillage du site.
- Les pages obsolètes sont redirigées (301) ou retirées selon une règle documentée — jamais laissées à l'abandon.
- **Fil d'Ariane (breadcrumb)** OBLIGATOIRE sur toute page de profondeur ≥ 2, avec balisage Schema.org `BreadcrumbList` (section 9).
- Le logo en haut à gauche renvoie TOUJOURS à l'accueil.
- Le pied de page contient : liens légaux (section 12), plan du site, contenus d'aide et de support faciles à retrouver, coordonnées, liens réseaux sociaux réels.
- Les ressources téléchargeables sont rattachées à des pages explicatives (jamais un PDF orphelin).

### 5.3 Recherche interne
- SI le site a plus de ~20 contenus (formations, articles, produits) : un moteur de recherche interne est OBLIGATOIRE, visible dans l'en-tête.
- La recherche DOIT être tolérante : insensible à la casse et aux accents, tolérante aux fautes courantes (voir section 18.1 pour la recherche avancée).
- La page de résultats gère l'état vide avec des suggestions et une alternative (contact) — jamais une impasse.
- Les recherches internes sont journalisées (anonymement) : les recherches sans résultat alimentent l'amélioration des contenus (section 20).

### 5.4 Parcours sans friction
- **Les parcours les plus fréquents sont les plus courts.** Chaque étape supplémentaire fait perdre des utilisateurs.
- Le retour en arrière ne fait JAMAIS perdre les données saisies.
- Les données déjà connues ne sont JAMAIS redemandées (un client connecté ne retape pas son adresse).
- Les démarches longues peuvent être interrompues et reprises (brouillon, lien de reprise).
- Après connexion ou paiement, l'utilisateur est ramené AU BON CONTEXTE (la page qu'il visait), pas à un accueil générique.
- Les pop-ups et bandeaux ne masquent JAMAIS les actions essentielles ni la lecture ; toute surcouche est refermable facilement, y compris sur mobile.
- Les pages longues ont des ancres, un sommaire ou des sections scannables.
- Les actions irréversibles demandent confirmation ET expliquent les conséquences.
- L'aide est disponible au moment exact du besoin (aide contextuelle près du champ ou de l'étape concernée, pas seulement une FAQ lointaine).
- Les principaux abandons de parcours sont mesurés et analysés (section 20).

### 5.5 Zéro ambiguïté
- Chaque bouton dit ce qu'il fait avec un verbe (« Réserver ma formation », pas « OK » ni « Valider »).
- Jamais deux actions visuellement équivalentes côte à côte : l'action principale est mise en avant, la secondaire est discrète.
- Les éléments cliquables ont des états visibles : survol, focus, sélection, désactivation. Rien de cliquable ne doit sembler inerte, rien d'inerte ne doit sembler cliquable.

### 5.6 Pages d'erreur
- Les gabarits d'erreur (`templates/bundles/TwigBundle/Exception/error404.html.twig`, `error500.html.twig`) DOIVENT être soignés : message en français, sans jargon, orienté solution, avec recherche et/ou liens vers les pages principales. Une 404 est une opportunité de retenir le visiteur, pas une impasse — et ne doit jamais donner une impression d'abandon.
- Les 404 renvoient un vrai statut HTTP 404 (pas un 200 avec un message d'erreur). Les soft 404 sont surveillées et corrigées (section 9).

---

## 6. UI — DESIGN

> Le design doit inspirer confiance : moderne, cohérent, sobre, professionnel. Le design sert le contenu, pas l'inverse. Le niveau de finition doit inspirer confiance dès les premières secondes.

### 6.1 Charte graphique — tokens obligatoires
- AVANT de construire la première page, définir la charte via des variables CSS (custom properties) dans `assets/styles/` (chargées par AssetMapper) :
  - **Palette limitée** : 1 couleur primaire, 1 couleur d'accent (CTA), 2–3 neutres, 1 couleur succès, 1 couleur erreur. C'est TOUT. Chaque couleur a un RÔLE fonctionnel — jamais de couleur purement décorative.
  - **Typographie** : 2 familles maximum (titres + texte). Corps de texte ≥ 16px. Hauteur de ligne 1.5–1.7 pour le texte courant.
  - **Espacements** : échelle unique (4/8/12/16/24/32/48/64px). JAMAIS de valeurs arbitraires (`margin: 13px` est un bug).
  - **Rayons et ombres** : une échelle unique, réutilisée partout.
- Toute couleur, taille ou espacement en dur dans un composant (hors tokens) est un BUG.
- Le design system couvre et normalise : boutons, champs, cartes, listes, tableaux, alertes, modales — avec leurs états (erreur, succès, vide, chargement, désactivé) prévus graphiquement. Variantes LIMITÉES et documentées.

### 6.2 Règles visuelles
- Beaucoup d'espace blanc : en cas de doute, aère. Une page dense fait fuir.
- Cohérence absolue : même style de boutons, de cartes, de titres sur tout le site. Un composant qui existe déjà est réutilisé, jamais recréé en variante.
- Images de qualité, au bon format et compressées (section 8). Les images floues, étirées ou pixellisées sont BLOQUANTES. Les visuels reflètent la RÉALITÉ du service (règle 2.10) ; les pages importantes ne reposent pas uniquement sur de grandes images décoratives.
- Icônes : une seule bibliothèque (via le composant `ux_icon` de Symfony UX Icons / Iconify), un seul style (outline OU solid, pas les deux mélangés), accompagnées d'un libellé quand leur sens n'est pas évident.
- Le contraste texte/fond respecte le minimum WCAG AA (section 10) — c'est aussi une règle de design.
- Les animations restent utiles, sobres et non perturbatrices ; les interfaces critiques évitent les effets de mode qui nuisent à la compréhension.
- **Le design survit aux contenus réels** : titres longs, noms composés, textes traduits plus longs — chaque composant est testé avec des contenus plus longs que la maquette idéale.

### 6.3 Hiérarchie visuelle
- Chaque page a UNE information dominante. L'œil doit savoir où aller en premier.
- Les tailles de titres suivent la hiérarchie sémantique H1 > H2 > H3 (jamais un H3 plus gros qu'un H2).
- Le CTA principal est l'élément le plus visible de la page après le titre.

---

## 7. RESPONSIVE & MULTI-ÉCRANS

> Le site doit être parfait sur smartphone, tablette, ordinateur et écran 4K. La majorité du trafic sera mobile : on conçoit MOBILE-FIRST.

- Développement mobile-first : styles de base pour mobile, media queries pour agrandir. Utiliser une échelle de breakpoints unique et documentée (`sm`, `md`, `lg`, `xl`) définie une fois dans les tokens CSS — pas de breakpoints personnalisés dispersés sans justification.
- **Cibles tactiles ≥ 44×44px** (boutons, liens de menu, icônes cliquables), avec un espacement suffisant pour éviter les faux clics.
- AUCUN zoom ne doit être nécessaire pour lire ou agir. AUCUN défilement horizontal, à aucune largeur d'écran.
- Interdit de bloquer le zoom (`user-scalable=no` est INTERDIT — c'est aussi une règle d'accessibilité).
- **Aucun contenu essentiel n'est masqué sur mobile** : on adapte la présentation, on ne supprime pas l'information.
- Les formulaires restent simples sur mobile : champs pleine largeur, clavier adapté au type de champ (numérique pour téléphone…).
- Les tableaux de données (grilles de prix, comparatifs) DOIVENT avoir une stratégie mobile : cartes empilées, colonnes prioritaires ou défilement contenu au tableau.
- Les éléments sticky (en-tête, bandeau CTA) ne masquent JAMAIS la lecture ni les champs de formulaire.
- Toute interaction au survol (tooltip, menu) a une alternative tactile.
- Les vidéos et cartes interactives s'adaptent aux petits écrans.
- Sur grands écrans : l'espace est utilisé intelligemment SANS étirer les lignes de texte (largeur de lecture max ~70–80 caractères).
- Le rendu est vérifié sur les navigateurs réellement utilisés par la cible (Chrome, Safari — y compris iOS —, Firefox, Edge).
- Test obligatoire avant chaque livraison de page : 375px (mobile), 768px (tablette), 1440px (desktop), 2560px (grand écran) — ET sur au moins un vrai appareil mobile, pas seulement l'émulateur (section 24). Rien de cassé, rien de coupé, rien d'illisible.

---

## 8. PERFORMANCE — VITESSE & CORE WEB VITALS

> Objectif : affichage < 2 secondes, idéalement < 1 seconde. Chaque seconde supplémentaire fait perdre des visiteurs. La performance se construit dès le départ, elle ne se rattrape pas à la fin.

### 8.1 Budgets (plafonds mesurables)
- **LCP** (plus grand élément visible) : < 2,0 s, cible 1,0 s (le seuil Google est 2,5 s au 75e percentile — notre standard est plus strict).
- **INP** (réactivité aux interactions) : < 200 ms.
- **CLS** (stabilité visuelle) : < 0,1 — AUCUN saut de mise en page au chargement.
- **TTFB** (premier octet) : < 800 ms, cible 200 ms — c'est le plancher de tout le reste ; un TTFB lent se corrige côté cache/serveur (8.4, 8.5), pas côté front.
- **Poids d'une page** (transfert initial) : < 1 Mo, cible 500 Ko. JS initial < 200 Ko compressé.
- **Score Lighthouse Performance mobile ≥ 90** sur chaque GABARIT de page (accueil, liste, fiche, article, tunnel) — pas seulement l'accueil.
- Ces budgets valent par gabarit et sont documentés ; **une régression de performance au-delà des seuils BLOQUE le déploiement** (section 24).
- Dès que le site a du trafic : mesurer en DONNÉES TERRAIN (Core Web Vitals réels — CrUX / RUM au 75e percentile), pas seulement en laboratoire. Le terrain fait foi.

### 8.2 Images
- Génération d'images responsives OBLIGATOIRE via LiipImagineBundle (ou un service équivalent) : formats modernes WebP/AVIF, redimensionnement et `srcset` produits côté serveur ; les images sont servies à la taille réellement affichée.
- `loading="lazy"` pour toute image sous la ligne de flottaison ; l'image LCP (héro) en `loading="eager"` + `fetchpriority="high"`. Le chargement différé ne retarde JAMAIS un contenu du premier écran.
- `width` et `height` (ou ratio) TOUJOURS définis pour éviter les sauts de mise en page.
- Une photo ne dépasse pas ~200 Ko après optimisation ; une icône est un SVG.

### 8.3 Polices
- 2 familles max, hébergées localement (pas de requête vers Google Fonts en production — performance ET RGPD).
- Formats WOFF2 uniquement, `font-display: swap`, préchargement de la police du texte principal — aucun blocage de rendu, aucun décalage de mise en page dû aux polices.

### 8.4 Code et rendu
- Rendu serveur par défaut — c'est le fonctionnement natif de Symfony/Twig : ne PAS déporter le contenu principal en JS.
- **Anticiper la navigation** : `preconnect` vers les origines critiques (CDN d'images…), préchargement (`preload`) de l'image LCP et de la police principale sur les pages clés ; le préchargement des liens au survol par Turbo (Turbo Drive, actif par défaut avec Symfony UX) n'est pas désactivé globalement. Métriques de police de secours ajustées pour éviter le saut de texte au chargement.
- Cache HTTP via le composant HttpCache et les en-têtes de réponse (`Cache-Control`, `ETag`, `s-maxage`) : pages stables en cache partagé (reverse proxy / CDN), pages dynamiques avec cache adapté ; ESI pour les fragments à durée de vie différente. Assets versionnés par AssetMapper avec en-têtes de cache longue durée (immutable).
- Chargement différé des scripts non critiques ; AssetMapper + importmap servent des modules ES natifs (pas de bundle monolithique) — ne pas le casser par des imports globaux inutiles.
- Le CSS inutile est éliminé ; le CSS critique arrive en priorité.
- Compression et versionnage des assets gérés par AssetMapper (`asset-map:compile` en production) — ne jamais servir d'assets non compilés en production. Sourcemaps non exposées publiquement en production.
- Pas de bibliothèque lourde pour un besoin simple. Vérifier le poids avant d'ajouter (section 2.5) ; les ressources TIERCES sont auditées pour leur coût réel de chargement.
- Aucune animation coûteuse en continu ; animer uniquement `transform` et `opacity` ; aucun traitement lourd sur le thread principal.
- Polyfills limités aux navigateurs réellement supportés.

### 8.5 Serveur et base de données
- Toute requête SQL générée DOIT s'appuyer sur des index (section 15). Les requêtes N+1 sont un BUG : utiliser les jointures (`JOIN`/`addSelect`) et le `fetch join` de Doctrine correctement, et surveiller le profiler.
- Paginer TOUTE liste potentiellement longue (API et interface). Jamais de `findAll()` sans limite sur une table qui grandit.
- Les temps de réponse des routes critiques respectent des seuils définis (< 300 ms en cible pour les routes de pages) ; les traitements longs deviennent asynchrones (Messenger) plutôt que de faire attendre l'utilisateur.
- Les appels API externes côté serveur sont mis en cache quand la donnée le permet (composant Cache : `cache.app`) et ont TOUJOURS un timeout (HttpClient).
- Compression (gzip/brotli) activée en production ; HTTP/2 ou HTTP/3 via le CDN/reverse proxy.

---

## 9. SEO & GEO — RÉFÉRENCEMENT CLASSIQUE ET GÉNÉRATIF

> Le meilleur site du monde est inutile s'il n'est jamais trouvé. Le SEO est intégré dès la première page, pas ajouté à la fin. Et aujourd'hui, être trouvé signifie DEUX choses : apparaître dans les moteurs de recherche classiques (SEO) ET être cité, recommandé et repris par les moteurs de réponse IA — ChatGPT, Perplexity, Claude, Gemini, aperçus IA de Google (GEO, section 9.4).

### 9.1 SEO technique, indexation et crawl
- **URLs propres**, courtes, en français, avec tirets : `monsite.fr/permis-conduire-accelere` — JAMAIS `monsite.fr?id=54874`. Pas de majuscules, pas d'accents, pas de underscores. Les URLs reflètent la structure du site et restent STABLES.
- **Sitemap XML** : bundle de sitemap (ex. `presta/sitemap-bundle`) OBLIGATOIRE, généré automatiquement, incluant toutes les pages publiques indexables — et soumis dans la Search Console.
- **robots.txt** : servi depuis `public/` (ou une route dédiée), référençant le sitemap, autorisant le crawl des pages utiles et bloquant les zones sans valeur. Les environnements de préproduction DOIVENT être bloqués à l'indexation (noindex) — la production ne l'est JAMAIS par accident (vérification en checklist section 25).
- **Robots des moteurs IA explicitement AUTORISÉS en production** (règle GEO, section 9.4) : `GPTBot`, `OAI-SearchBot`, `ChatGPT-User`, `ClaudeBot`, `PerplexityBot`, `Google-Extended`, `Bingbot` ne sont PAS bloqués — vérifier qu'aucune règle serveur, CDN ou pare-feu ne les bloque par défaut. Un site invisible pour les IA n'est jamais recommandé par elles. (Exception : décision contraire explicite du chef de projet, en ADAPTATIONS.)
- **Canonical** sur chaque page, surtout sur les pages avec filtres/paramètres. Les pages paginées, filtrées ou à facettes sont gérées SANS polluer l'index (canonical vers la page de base, noindex des combinaisons de filtres sans valeur de recherche).
- Les pages sans valeur SEO (résultats de recherche interne, comptes, panier) sont proprement désindexées (`noindex`).
- **Redirections 301** propres pour toute URL modifiée ou supprimée. JAMAIS de chaîne de redirections, jamais de 302 pour du permanent. Toute migration d'URLs a un plan de redirection ET un suivi post-migration.
- Les erreurs 404, 500 et soft 404 sont surveillées (Search Console + logs) et corrigées.
- **Données structurées Schema.org** (JSON-LD généré dans les gabarits Twig, via un service dédié) : `Organization` ou `LocalBusiness`, `BreadcrumbList`, et selon le contenu `Product`, `Course`, `Article`, `FAQPage`. Avec les VRAIES données (règle 2.10) — les données structurées ne promettent JAMAIS une information invisible sur la page.
- Un seul domaine canonique (avec ou sans www, décidé une fois) ; l'autre redirige en 301. HTTPS partout (section 16).
- **La Search Console** (ou équivalent) est configurée dès la mise en ligne et consultée régulièrement (couverture d'index, Core Web Vitals, erreurs).

### 9.2 SEO on-page — chaque page publique
- Métadonnées OBLIGATOIRES via des blocs Twig (`{% block title %}`, balises `<meta>` dans `base.html.twig`), avec des valeurs SPÉCIFIQUES à la page (jamais un titre global partagé) :
  - `title` : unique, ~50–60 caractères, mot-clé principal au début, format « Sujet — Marque ».
  - `description` : unique, ~150–160 caractères, incitative (elle est l'annonce gratuite du site dans Google).
  - `ogTitle`, `ogDescription`, `ogImage` (image 1200×630 dédiée au partage — les extraits partagés sur réseaux sociaux et messageries doivent être impeccables).
- **Un seul H1 par page**, contenant le sujet principal. Puis des H2 pour les sections, H3 pour les sous-sections — hiérarchie stricte, jamais de saut de niveau, jamais de titre choisi pour sa taille visuelle.
- Le contenu SEO est rendu côté serveur (règle 4.3) — un contenu injecté uniquement côté client est invisible pour les robots : c'est un BUG.
- Maillage interne : chaque page importante reçoit des liens depuis d'autres pages, avec des ancres descriptives (« nos formations au permis accéléré », jamais « cliquez ici ») ; le maillage transmet la priorité des pages stratégiques.
- Attribut `alt` descriptif sur toute image porteuse de sens ; noms de fichiers d'images descriptifs (règle partagée avec l'accessibilité, section 10).

```twig
{# templates/formation/show.html.twig #}
{% block title %}{{ formation.titre }} — Lindbergh Formation{% endblock %}

{% block meta %}
  <meta name="description" content="{{ formation.accroche }}">
  <meta property="og:title" content="{{ formation.titre }}">
  <meta property="og:description" content="{{ formation.accroche }}">
  <meta property="og:image" content="{{ formation.imagePartage }}">
{% endblock %}
```

### 9.3 SEO éditorial, sémantique et autorité
- La stratégie SEO part des INTENTIONS DE RECHERCHE (que cherche la cible, avec quels mots), pas d'une liste de mots-clés au volume flatteur. Chaque page cible UNE intention principale identifiable ; deux pages ne se disputent jamais le même mot-clé.
- Les contenus traitent les questions associées naturellement, SANS bourrage de mots-clés : écrits pour les humains d'abord, optimisés ensuite.
- **Organisation en clusters** : une page pilier par grand sujet (ex. « Permis accéléré ») + des contenus satellites qui la soutiennent et pointent vers elle (« prix du permis accéléré », « permis accéléré en 15 jours »…).
- **Crédibilité (E-E-A-T)** : les contenus experts sont signés ou rattachés à une expertise identifiable ; les sources et dates de mise à jour sont affichées sur les sujets évolutifs ; les sujets YMYL (argent, santé, sécurité, juridique — fréquents en formation !) exigent une exactitude renforcée validée par le chef de projet.
- Les contenus locaux contiennent de VRAIES informations locales (pas un copier-coller générique avec le nom de la ville changé).
- Les titres éditoriaux sont attractifs mais EXACTS (jamais de piège à clic démenti par le contenu).
- Les contenus anciens sont révisés, fusionnés ou supprimés selon leur performance — un site qui accumule des pages mortes dilue son autorité (règle partagée avec l'éco-conception, section 18.4).
- Backlinks : privilégier qualité, légitimité et durabilité (contenus liables : guides, FAQ, outils) — jamais d'achat de liens ni de schémas artificiels.
- Les KPI SEO distinguent impressions, clics, positions, conversions et valeur business (section 20).

### 9.4 GEO — être cité par les moteurs de réponse IA
> Une part croissante des visiteurs ne « cherche » plus : elle demande à une IA. Le GEO (Generative Engine Optimization) vise à ce que le site soit LU, COMPRIS, CITÉ et RECOMMANDÉ par ces moteurs. Le socle SEO (9.1–9.3) en est le prérequis ; les règles ci-dessous s'y ajoutent.

**Accès et lisibilité machine**
- Les robots IA sont autorisés (règle 9.1) et TOUT le contenu important est dans le HTML rendu côté serveur — c'est le fonctionnement natif de Twig (règle 8.4) : ne JAMAIS déporter un contenu important dans du JavaScript client (Stimulus), la plupart des moteurs IA ne l'exécutent pas.
- **Fichier `llms.txt`** à la racine (`public/llms.txt`) : présentation courte du site en Markdown + liens vers les pages clés (offres, tarifs, FAQ, contact) — le plan du site pensé pour les modèles de langage. Maintenu à jour comme le sitemap.
- HTML propre et sémantique (section 10) : les extracteurs des IA lisent la structure (titres, listes, tableaux), pas le design.

**Contenu structuré pour la CITATION**
- **Chaque page importante commence par une réponse directe et autoportante** : 2–3 phrases qui répondent à la question de la page en citant le sujet ET le nom de l'entreprise (« Le permis accéléré chez Lindbergh Formation dure 15 jours et coûte X € »). Une IA cite ce qui est extractible tel quel, sans contexte manquant (pas de « nous », « ici », « comme vu plus haut » dans ces passages).
- **Format question → réponse** privilégié : les H2/H3 reprennent les VRAIES questions des utilisateurs (celles qu'ils posent aux IA), suivies d'une réponse concise puis du développement. La FAQ balisée `FAQPage` (9.1) est un actif GEO majeur.
- **Chiffres, dates et faits attribuables** : prix, durées, taux de réussite, conditions — précis, datés, exacts (règle 2.10). Les IA privilégient les sources qui donnent des faits vérifiables plutôt que des slogans.
- Définitions et comparatifs structurés (tableaux, listes nettes) : les formats extractibles sont repris en priorité par les moteurs de réponse.
- Le nom de l'entreprise, son activité et sa zone d'intervention apparaissent en toutes lettres dans les contenus clés (une IA ne recommande que ce qu'elle peut nommer).

**Autorité et cohérence hors site**
- Les données structurées `Organization`/`LocalBusiness` (9.1) sont complètes : nom, adresse, téléphone, zone desservie, offres — les IA s'en servent pour fiabiliser leurs réponses.
- **Cohérence NAP partout** : nom, adresse, téléphone et description de l'entreprise IDENTIQUES sur le site, la fiche Google Business, les annuaires, les réseaux sociaux — les IA recoupent les sources ; une incohérence détruit la confiance. (Présence hors site : actions `TODO-PM`, listées pour le chef de projet.)
- Les contenus signés, datés et sourcés (E-E-A-T, 9.3) comptent DOUBLE en GEO : les moteurs IA privilégient les sources crédibles et à jour.

**Mesure**
- Le trafic issu des moteurs IA (referrers `chatgpt.com`, `perplexity.ai`, etc.) est segmenté dans l'analytics (section 20) et suivi dans le temps.
- Vérification périodique MANUELLE avec le chef de projet : poser aux principaux assistants IA les questions clés de la cible (« meilleure formation permis accéléré à [ville] ? ») et noter si le site est cité, avec quelles informations — les erreurs relevées deviennent des corrections de contenu.

---

## 10. ACCESSIBILITÉ (WCAG 2.2 / RGAA — niveau AA)

> Le site doit être utilisable par tous : malvoyants, daltoniens, handicap moteur, handicap cognitif, usage contraint. L'accessibilité est une obligation légale ET améliore l'UX et le SEO de tout le monde.

- **HTML sémantique d'abord** : `<header>`, `<nav>`, `<main>` (un seul), `<footer>`, `<button>` pour une action, `<a>` pour un lien. JAMAIS un `<div @click>` en guise de bouton. Les attributs ARIA ne servent qu'en dernier recours ; quand un composant personnalisé est inévitable, il respecte rôles, états et propriétés ARIA.
- **Contraste** : ≥ 4,5:1 pour le texte normal, ≥ 3:1 pour le grand texte et les composants d'interface. Vérifié pour CHAQUE combinaison texte/fond de la charte, dès sa définition (section 6.1).
- L'information n'est JAMAIS portée par la couleur seule (une erreur de formulaire = couleur + icône + texte).
- **Navigation clavier complète** : tout ce qui se fait à la souris se fait au clavier (Tab, Entrée, Échap), SANS piège de focus. Ordre de tabulation logique, suivant la logique visuelle. Focus visible et contrasté sur chaque élément interactif — ne JAMAIS supprimer l'outline sans le remplacer par un style de focus équivalent.
- Lien d'évitement (« Aller au contenu ») en premier élément focusable de chaque page.
- **Images** : `alt` descriptif si l'image porte du sens, `alt=""` si elle est décorative (ignorée par les lecteurs d'écran). Jamais d'`alt` manquant.
- **Liens** : libellés explicites HORS contexte (« Voir le programme de la formation permis B », pas « en savoir plus » répété dix fois).
- **Formulaires** : chaque champ a un `<label>` associé (`for`/`id`) ; les erreurs sont annoncées aux lecteurs d'écran (`aria-live` / `aria-describedby`) — voir section 13.
- Les contenus dynamiques (résultats chargés, notifications) sont annoncés aux lecteurs d'écran quand nécessaire (`aria-live`).
- Les modales piègent le focus tant qu'elles sont ouvertes et se ferment à Échap (les contrôleurs Stimulus / composants Symfony UX le gèrent : ne pas les contourner).
- `lang="fr"` sur `<html>` ; les passages dans une autre langue sont marqués (`lang="en"`).
- Tailles de texte en unités relatives (`rem`) — le site reste utilisable avec un zoom navigateur ou texte à 200 %.
- Respecter `prefers-reduced-motion` : les animations non essentielles sont désactivées pour ceux qui le demandent.
- Aucun contenu clignotant, aucun carrousel en défilement automatique sans bouton pause ; les délais automatiques (session, redirection) peuvent être prolongés, désactivés ou sont expliqués.
- Vidéos importantes : sous-titres, transcription ou audiodescription selon le besoin.
- Les PDF proposés au téléchargement sont accessibles OU accompagnés d'une alternative HTML — et un contenu important n'est jamais disponible UNIQUEMENT en PDF (section 11).
- **Nouveautés WCAG 2.2 appliquées** : cibles cliquables ≥ 24×24 px partout (44 px sur mobile, section 7) ; le focus clavier n'est JAMAIS masqué par un en-tête ou bandeau sticky ; toute interaction par glisser-déposer a une alternative simple (boutons) ; jamais de double saisie d'une information déjà fournie dans le même parcours ; le collage est AUTORISÉ dans les champs de code/mot de passe (l'interdire casse les gestionnaires de mots de passe).
- **Vérification** : audit automatique (Lighthouse/axe) À CHAQUE gabarit + tests manuels au clavier et au lecteur d'écran (VoiceOver ou NVDA) sur les parcours clés (section 24). Les corrections d'accessibilité sont intégrées au design system, pas rustinées page par page.
- SI le cadre réglementaire l'exige (organisme public, grande entreprise — à instruire avec le chef de projet) : publier une **déclaration d'accessibilité** ; l'inscrire en ADAPTATIONS.

---

## 11. CONTENU & COPYWRITING

> Le contenu répond aux questions des visiteurs. Il est utile, clair, structuré, précis, à jour et illustré.

- Chaque page importante répond aux questions essentielles : **quoi, pour qui, pourquoi, comment, prix, délais, preuve.**
- Phrases courtes. Paragraphes de 3–4 lignes maximum. Jamais de blocs de texte massifs : découper avec des intertitres (H2/H3), des listes et des visuels. Les comparaisons et procédures complexes utilisent listes et tableaux.
- Les pages longues commencent par un résumé ou une réponse immédiate (le visiteur pressé est servi d'abord).
- INTERDIT : jargon technique non expliqué, anglicismes inutiles, répétitions, formules creuses (« leader de solutions innovantes »), superlatifs non prouvés. Tout acronyme ou terme juridique est défini à sa première apparition. On écrit pour la cible définie en section 1, avec SES mots.
- Chaque affirmation marketing est soutenue par une preuve concrète (chiffre, référence, garantie — réels, règle 2.10). Les erreurs fréquentes et idées reçues de la cible sont traitées explicitement.
- Chaque page répond à une vraie question du visiteur et se termine par la suite logique (CTA ou lien vers l'étape suivante), cohérente avec l'intention de lecture.
- Une FAQ est fortement recommandée sur les pages de vente (avec balisage `FAQPage`, section 9.1) : elle répond aux VRAIES questions (issues du support, des ventes, des recherches internes), pas à des mots-clés artificiels.
- **Les contenus importants vivent en HTML, jamais cachés dans des PDF** (les PDF restent des compléments téléchargeables).
- Les contenus datés ou évolutifs (prix, dates, réglementations) affichent leur date de mise à jour et sont vérifiés par le chef de projet (section 2.10). Un contenu douteux = `TODO-PM`, jamais une invention.
- Le ton éditorial est constant (défini une fois : tutoiement OU vouvoiement, niveau de formalité) sur tout le site, emails inclus.
- Orthographe irréprochable : une faute détruit la confiance. Relire chaque texte avant commit.
- Images, vidéos et infographies AJOUTENT de l'information (elles ne décorent pas seulement).

---

## 12. CONFIANCE & CONVERSION

> Le visiteur doit être rassuré immédiatement, et tout doit conduire naturellement vers l'action principale définie en section 1.

### 12.1 Pages légales OBLIGATOIRES (bloquantes pour la mise en ligne)
- **Mentions légales** (obligation légale française : éditeur, directeur de publication, hébergeur, SIRET).
- **Politique de confidentialité** (RGPD, section 17).
- **CGV** si le site vend / **CGU** si le site a des comptes utilisateurs — accessibles AVANT l'engagement (section 13).
- Gestion des cookies (bandeau + page dédiée, section 17).
- Toutes accessibles depuis le pied de page de CHAQUE page. Le contenu juridique réel est fourni par le chef de projet (`TODO-PM` sinon) — Claude peut préparer la structure, pas inventer le SIRET.

### 12.2 Éléments de réassurance
- Coordonnées réelles visibles et VÉRIFIABLES : adresse, téléphone cliquable (`tel:`), email — dans le pied de page et sur la page contact. L'identité de l'organisation exploitante est claire partout.
- Selon le projet : avis clients, témoignages, logos partenaires, certifications (Qualiopi pour la formation…), photos réelles de l'équipe et des locaux, chiffres clés, références et cas d'usage. TOUS réels et fournis par le chef de projet (règle 2.10). Les avis sont datés et contextualisés ; les logos partenaires/médias uniquement s'ils sont légitimes ; badges et labels reliés à leur source.
- **Transparence AVANT l'engagement** : garanties, délais, conditions, limites et prix expliqués avant que le visiteur s'engage — aucun coût ni condition surprise. La politique de remboursement/réclamation est claire quand elle s'applique.
- L'aide et la FAQ expliquent honnêtement les cas d'échec, retards et erreurs possibles — l'honnêteté rassure plus que la perfection prétendue.
- Les éléments de réassurance sont placés PRÈS des points de décision : avis à côté du bouton d'achat, garanties près du formulaire.
- Sur un tunnel de paiement : logos de paiement, mention de sécurité, récapitulatif clair (section 13).

### 12.3 CTA (Call To Action)
- **Un seul CTA principal par page**, formulé avec un verbe concret qui dit ce qui va se passer : « Réserver ma formation », « Demander un devis gratuit », « Essayer gratuitement ». Les CTA secondaires n'entrent JAMAIS en concurrence visuelle avec lui.
- Le CTA principal apparaît au-dessus de la ligne de flottaison ET est répété en fin de page (pages longues : visible en haut et en bas). Sur mobile, il reste accessible sans effort.
- Couleur d'accent réservée aux CTA (section 6.1) : elle ne sert à rien d'autre.
- Chaque page du site mène quelque part : AUCUNE page cul-de-sac.
- Le parcours de conversion (arrivée → action réussie) DOIT compter le minimum d'étapes possible ; les objections sont traitées AVANT le moment de décision (section 1.2).
- **Pages de remerciement** : après une conversion, proposer une suite utile (confirmation claire, prochaine étape, contenu utile) — jamais une page vide.
- **Cohérence campagne → landing page** : si une campagne (pub, email, réseaux sociaux) promet quelque chose, la page d'atterrissage tient exactement cette promesse (même message, même offre). Les landing pages de campagne masquent les distractions inutiles.
- Les offres limitées dans le temps sont RÉELLES — les faux compteurs et la fausse rareté sont INTERDITS (pratique trompeuse + dark pattern).
- Tests A/B (si mis en place) : une hypothèse précise, une durée et un critère d'arrêt définis à l'avance — jamais des changements aléatoires.
- Mesure : conversions principales ET micro-conversions tracées dans l'outil d'analytics (section 20), sinon on pilote à l'aveugle.

---

## 13. FORMULAIRES, TUNNELS & E-COMMERCE

### 13.1 Formulaires (tous)
- Le moins de champs possible : **chaque champ a une justification métier ou réglementaire** — sinon il saute. Un formulaire de contact = nom, email, message. Point.
- Chaque champ a un `<label>` visible EN PERMANENCE (pas seulement un placeholder qui disparaît), le bon `type` (`email`, `tel`…) et le bon `autocomplete` (`name`, `email`, `tel`…) ; l'autocomplétion est désactivée sur les champs sensibles où elle serait inappropriée.
- Obligatoire vs facultatif clairement indiqué ; les formats attendus sont expliqués AVANT l'erreur (téléphone, date, taille et formats de pièce jointe).
- Validation en ligne, champ par champ, au bon moment ; messages d'erreur précis en français, localisés au champ concerné, qui expliquent COMMENT corriger sans culpabiliser. Jamais une seule erreur générique en haut.
- Le même DTO + contraintes de validation servent le formulaire (Form Type) ET la requête serveur (section 14.3).
- Les champs conditionnels n'apparaissent que lorsqu'ils sont utiles.
- **Les saisies survivent aux accidents** : rafraîchissement, erreur technique ou retour arrière ne font pas perdre les données (état conservé, brouillon).
- Les formulaires longs sont découpés en étapes cohérentes avec progression visible ; les démarches longues sont interruptibles et reprenables (règle 5.4).
- Avant un envoi ENGAGEANT (commande, inscription payante) : un récapitulatif des informations saisies.
- Après envoi : confirmation claire de ce qui va se passer (« Nous vous répondons sous 24 h »), délais et canal de suivi communiqués. L'utilisateur reçoit un email de confirmation quand c'est pertinent (testé — section 24).
- Anti-spam : honeypot + rate limiting serveur d'abord ; JAMAIS de CAPTCHA agressif par défaut (et jamais un CAPTCHA qui bloque l'accessibilité).
- Les doublons de soumission sont détectés (double-clic, renvoi) — côté client (bouton désactivé pendant l'envoi) ET côté serveur (idempotence).
- Le back-office/destinataire reçoit les données dans un format exploitable, et les leads transmis au commercial contiennent le contexte utile (page d'origine, campagne).
- Utilisables au clavier et au lecteur d'écran (section 10) ; testés avec données valides, invalides, extrêmes et incomplètes (section 24).

### 13.2 E-commerce, paiement & réservation (si le site vend)
- **Le prix total est visible AVANT validation** — frais, taxes et options inclus. Aucun frais révélé tardivement. Les promotions s'appliquent de façon transparente.
- Les CGV sont accessibles avant l'engagement ; les obligations consommateur sont couvertes (prix, délais, droit de rétractation, garanties légales, médiation de la consommation) — contenus fournis par le chef de projet (section 17.2).
- Le panier/devis reste modifiable jusqu'à la validation finale ; le tunnel indique clairement les étapes restantes ; un panier interrompu peut être repris.
- Adresses et informations de livraison/lieu validées AVANT le paiement.
- **Paiement** : prestataire certifié uniquement (Stripe…) — le site ne voit JAMAIS un numéro de carte. Les moyens de paiement correspondent aux habitudes de la cible.
- Les erreurs de paiement expliquent la situation SANS exposer de données sensibles, avec une issue (réessayer, autre moyen, contact).
- **Les cas d'échec après paiement sont gérés** : confirmation manquante, timeout, double clic → idempotence des opérations (une commande ne se crée pas deux fois), réconciliation via webhooks du prestataire (signés — section 14), et un chemin de rattrapage pour l'utilisateur.
- Confirmation, reçu ou facture envoyés selon le cas ; commandes/réservations consultables depuis le compte ou un lien sécurisé ; le support peut retrouver rapidement une transaction (identifiants de commande).
- Stocks, disponibilités ou créneaux synchronisés en temps réel — ou avec un délai INDIQUÉ.
- Les relances de panier abandonné respectent le consentement (section 17) et une pression commerciale acceptable.
- Le tunnel complet est testé en préproduction : scénarios nominaux ET incidents (paiement refusé, timeout, retour arrière) — section 24.

### 13.3 Emails transactionnels (confirmations, reçus, réinitialisations)
- Envoyés via un service d'email transactionnel dédié (jamais depuis une boîte perso ni un SMTP bricolé) ; **SPF, DKIM et DMARC configurés dès la mise en place** — un email de confirmation en spam est une conversion perdue.
- Gabarits cohérents avec la charte (section 6.1), version texte incluse, liens ABSOLUS en HTTPS, objet clair qui dit le contenu (« Votre réservation du 12 mars est confirmée »).
- Chaque email transactionnel contient : le fait confirmé, la prochaine étape, et le moyen de contact. AUCUNE donnée sensible dans l'objet ni au-delà du nécessaire dans le corps.
- Les emails transactionnels sont SÉPARÉS des emails marketing (le désabonnement marketing ne coupe jamais les confirmations) ; tout email non transactionnel a un lien de désinscription fonctionnel (section 17.1).
- Les envois passent par Symfony Messenger avec transport asynchrone et retry (section 22) : un pic ou une panne du fournisseur ne perd aucun email ; les échecs d'envoi sont journalisés et alertés (section 21).
- Rendu et délivrabilité TESTÉS avant mise en ligne (section 24).

---

## 14. RÈGLES BACK-END (`src/`)

> Le back-end utilise un LAYERING LÉGER : contrôleurs fins, logique métier dans les services, accès données isolé dans les repositories Doctrine.

### 14.1 Les trois couches
1. **Contrôleurs** (`src/Controller/`) — FINS. Un contrôleur : valide l'entrée (DTO + Validator / Form Type), appelle un service, formate la réponse (rendu Twig ou JSON). AUCUNE logique métier, AUCUN accès Doctrine direct ici.
2. **Services** (`src/Service/`) — logique métier. Orchestrent les repositories, appliquent les règles. AUCUNE préoccupation HTTP, AUCUNE requête Doctrine directe (passer par les repositories).
3. **Repositories** (`src/Repository/`) — le SEUL endroit qui construit du DQL / QueryBuilder. Un repository par entité/domaine (ex. `FormationRepository`).

**Autrement dit : si tu vois du QueryBuilder ou du DQL hors de `src/Repository/`, c'est un BUG.**

### 14.2 Conventions de routes
- Routing par attributs `#[Route]` sur les méthodes de contrôleur, avec un `name` explicite et le(s) verbe(s) HTTP (`methods`).
- Un contrôleur par domaine ; une action = une méthode publique retournant une `Response`.
- Les paramètres de route typés sont résolus automatiquement (value resolvers) ; les entités via l'attribut `#[MapEntity]` quand pertinent.
- Valider le corps/la query avec un DTO mappé (`#[MapRequestPayload]` / `#[MapQueryString]`) + contraintes, ou un Form Type.

**BON (contrôleur fin → service → repository, validé) :**
```php
// src/Controller/FormationController.php
#[Route('/api/formations', name: 'api_formation_create', methods: ['POST'])]
public function create(
    #[MapRequestPayload] CreateFormationDto $dto,
    FormationService $service,
): JsonResponse {
    return $this->json($service->create($dto), Response::HTTP_CREATED);
}
```
```php
// src/Repository/FormationRepository.php — le SEUL endroit où l'on requête Doctrine
final class FormationRepository extends ServiceEntityRepository
{
    /** Insère une formation en base. */
    public function save(Formation $formation): void
    {
        $this->getEntityManager()->persist($formation);
        $this->getEntityManager()->flush();
    }
}
```

### 14.3 Validation (DTO + contraintes, partagée)
- Les DTO et leurs contraintes vivent dans `src/Dto/` : le MÊME DTO valide le formulaire (Form Type) ET la requête (payload mappé).
- S'appuyer sur les contraintes du composant Validator (attributs `#[Assert\...]`) — ne PAS dupliquer les règles de validation à la main dans les contrôleurs.
- Les données reçues de systèmes EXTERNES (API tierces, webhooks, imports) sont validées avec la même rigueur que les données utilisateur.

### 14.4 Erreurs & logs
- Lever les erreurs HTTP avec les exceptions dédiées (`throw $this->createNotFoundException()`, `HttpException`). JAMAIS un 500 brut. Format d'erreur uniforme sur toute l'API (un `EventListener` d'exception le normalise).
- Les messages d'erreur renvoyés au client ne DOIVENT JAMAIS révéler d'informations internes (stack trace, requête SQL, chemin de fichier, version). Le détail va dans les logs serveur, pas dans la réponse.
- Logger les erreurs serveur via `LoggerInterface` (Monolog). JAMAIS de sortie brute côté serveur.
- Les opérations critiques (commande, paiement, inscription) sont traçables de bout en bout (identifiant de corrélation dans les logs).

### 14.5 API & intégrations SI
- REST cohérent : bons verbes, bons codes HTTP, pluriels (`/api/formations/{id}`), format d'erreur uniforme.
- Versionner dès qu'un tiers consomme l'API (`/api/v1/...`) ; contrats documentés (OpenAPI / NelmioApiDocBundle, ou à minima un `docs/api.md`) et stables.
- Authentification obligatoire sur tout endpoint non public, quotas/rate limiting (section 16), traitements longs asynchrones (file de tâches) quand l'expérience l'exige.
- **Intégrations tierces (CRM, ERP, email, paiement, signature, calendrier…)** : toute intégration passe par le serveur (`src/Service/` + un service client par intégration, sur HttpClient) — JAMAIS de clé d'un service tiers côté client. Chacune a : timeout, retry raisonnable, circuit breaker si critique, journalisation des échecs, et un **mode dégradé documenté** (le site ne casse pas si le CRM est en panne ; l'erreur d'intégration ne bloque pas le parcours utilisateur quand une file d'attente Messenger peut absorber).
- Les webhooks entrants (contrôleurs dédiés) vérifient TOUJOURS la signature de l'émetteur ET sont idempotents (rejouables sans doublon).
- Les flux vers les systèmes tiers sont supervisés (section 21) ; pour chaque donnée partagée avec le SI, la « source de vérité » est définie par écrit.
- Les exports/imports de données ont des contrôles de format, doublons et complétude.
- Documenter chaque intégration : service, données échangées, sens des flux, secret utilisé (nom de la variable `.env`, pas sa valeur).

---

## 15. BASE DE DONNÉES (Doctrine ORM + PostgreSQL)

- Schéma défini par les entités Doctrine (`src/Entity/`, mapping en attributs).
- Migrations VERSIONNÉES via DoctrineMigrationsBundle : `make:migration` puis `doctrine:migrations:migrate` (automatisé dans le déploiement, section 23) et réversibles (`down()`) quand c'est possible. Ne JAMAIS utiliser `doctrine:schema:update --force` sur un environnement partagé ou en production.
- Tout accès à la base isolé dans `src/Repository/` (voir 14.1).
- L'`EntityManager` et les repositories sont injectés par le conteneur de services (autowiring) — jamais instanciés à la main, jamais un manager par requête créé manuellement.
- **Index** : chaque colonne utilisée dans un `WHERE`, un `ORDER BY` fréquent ou une jointure DOIT être indexée (attribut `#[ORM\Index]`) — les index correspondent aux requêtes RÉELLES. Les colonnes à unicité métier (email…) portent une contrainte `#[ORM\UniqueConstraint]` / `#[UniqueEntity]`.
- Normaliser quand c'est pertinent ; dénormaliser uniquement pour une raison de performance mesurée, documentée dans `docs/decisions.md`.
- Utiliser des transactions (`$em->wrapInTransaction(...)`) pour toute opération multi-écritures qui doit rester cohérente (commande + paiement…).
- **Pooling de connexions** dès que l'app tourne derrière de nombreux workers PHP-FPM ou plusieurs instances (PgBouncer ou pooler de l'hébergeur) : des workers qui ouvrent chacun leurs connexions saturent PostgreSQL bien avant la charge réelle.
- **Plan de croissance des données** : les tables qui grossissent sans fin (logs applicatifs, événements, historiques) ont une règle d'archivage ou de purge définie À LA CRÉATION de l'entité, pas quand la base rame.
- **Sauvegardes automatiques quotidiennes** de la base en production, CHIFFRÉES, avec test de restauration documenté et objectifs RPO/RTO définis (section 22).
- Chaque environnement fournit `DATABASE_URL` (valeur non sensible dans `.env`, secret réel dans `.env.local` / le coffre). Le dev local exécute PostgreSQL via Docker. Les accès base suivent le moindre privilège (l'app n'est pas superutilisateur).

```php
// src/Repository/FormationRepository.php — repository injecté, jamais instancié à la main
final class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }
}
```

---

## 16. AUTHENTIFICATION, COMPTES & SÉCURITÉ RENFORCÉE (référence : OWASP ASVS / Top 10)

### 16.1 Auth & comptes (SecurityBundle)
- Authentification via le composant Security de Symfony (firewall, authenticator ; session gérée par le framework — cookie de session `Secure`, `HttpOnly`, `SameSite`, expiration adaptée au risque).
- La création de compte demande le STRICT nécessaire au service (minimisation, section 17).
- Les comptes utilisateurs sont des entités Doctrine (implémentant `UserInterface`) stockées dans PostgreSQL ; l'authenticator vérifie les identifiants via le user provider puis établit le jeton de sécurité.
- **Mots de passe hachés via le `PasswordHasher` (Argon2id ou bcrypt)** (jamais en clair, jamais en MD5/SHA1), paramètres de coût actuels. Longueur minimale 12 caractères ; JAMAIS de règle absurde de complexité qui pousse aux mots de passe faibles.
- Protéger les pages via `access_control` (security.yaml) et l'attribut `#[IsGranted]` sur les contrôleurs. Les contrôleurs sensibles revérifient TOUJOURS les droits côté serveur — masquer un lien en Twig n'est qu'un confort, jamais une sécurité.
- **Rôles et permissions** : documentés et TESTÉS via des Voters. Chaque contrôleur sensible vérifie le droit ; un utilisateur ne peut accéder qu'à SES données (contrôle d'appartenance sur chaque ressource, dans un Voter — jamais de simple `id` pris tel quel dans l'URL). La vérification se fait côté serveur, jamais uniquement en cachant un bouton.
- **Limitation des tentatives de connexion** : rate limiting sur login/inscription/réinitialisation (par IP et par compte) contre le credential stuffing. Message d'erreur identique que l'email existe ou non (« Identifiants incorrects »).
- Réinitialisation de mot de passe par token à usage unique et à expiration courte, jamais par envoi du mot de passe.
- **Changements sensibles confirmés** : modification d'email, de téléphone ou de mot de passe → confirmation par l'ancien canal + notification à l'utilisateur.
- L'utilisateur peut consulter et révoquer ses sessions actives (si le site a des comptes durables) ; la suppression/désactivation de compte est claire et conforme (section 17).
- **MFA disponible ou obligatoire pour les comptes sensibles** — OBLIGATOIRE pour les comptes d'administration (back-office, section 19).
- Comptes administrateurs NOMINATIFS (jamais partagés), aux droits minimisés et séparés par fonction (lecture, édition, validation, export) ; les exports de données sensibles exigent un droit spécifique ET sont journalisés. Les accès prestataires sont limités dans le temps et au périmètre nécessaire.
- SI login social (Google/Facebook) ou SSO requis : utiliser `knpuniversity/oauth2-client-bundle` (ou un authenticator OIDC dédié), configuration testée — déclarer en ADAPTATIONS.

### 16.2 Durcissement applicatif (NelmioSecurityBundle + composants natifs)
- En-têtes de sécurité OBLIGATOIRES via NelmioSecurityBundle (CSP, HSTS, X-Frame-Options, X-Content-Type-Options, Referrer-Policy…), rate limiting via le composant RateLimiter, limite de taille des requêtes, protection XSS (auto-échappement Twig). Ne désactiver une protection qu'avec justification dans ADAPTATIONS.
- HTTPS OBLIGATOIRE partout (production ET préproduction), redirection automatique HTTP → HTTPS, AUCUN contenu mixte. Certificats TLS renouvelés automatiquement ou surveillés (section 23).
- Protection CSRF : jetons CSRF (composant CSRF / `csrf_token()` dans les formulaires Twig) sur les mutations sensibles, en plus des cookies `SameSite` et de la vérification d'origine ; jamais de mutation d'état via GET.
- Injections SQL : couvertes par Doctrine (requêtes paramétrées, QueryBuilder) tant qu'on n'assemble pas de SQL par concaténation — la concaténation de valeurs utilisateur dans du DQL/SQL brut est INTERDITE (le SQL natif paramétré reste exceptionnel et justifié).
- **SSRF & redirections** : toute route qui télécharge une URL fournie par l'utilisateur valide le domaine (liste blanche) ; les redirections ouvertes (`?redirect=https://...`) sont interdites — uniquement des chemins internes validés.
- **Fichiers téléversés servis à d'autres utilisateurs** : analysés (antivirus type ClamAV ou service équivalent) avant mise à disposition, servis avec `Content-Disposition` et type MIME forcés — jamais exécutables, jamais interprétés par le serveur.
- `composer audit` à chaque ajout de dépendance et en CI (section 23) ; une vulnérabilité critique bloque le déploiement ; un processus de correctifs est défini (qui, sous quel délai selon la gravité).
- **Journaux de sécurité** : connexions, échecs, changements critiques (rôle, email, export) sont journalisés — sans données sensibles en clair.
- Paiements : ne JAMAIS stocker de numéro de carte. Passer par un prestataire certifié (section 13.2).
- Pour un site critique (paiement, données sensibles à grande échelle) : test d'intrusion avant mise en production — à décider avec le chef de projet, en ADAPTATIONS.

---

## 17. RGPD, DONNÉES PERSONNELLES & CONFORMITÉ JURIDIQUE

> Le RGPD n'est pas optionnel. Il se construit dans le code (« privacy by design »), pas dans une page légale ajoutée à la fin.

### 17.1 Données personnelles (RGPD / CNIL)
- **Minimisation** : ne collecter QUE les données nécessaires à chaque finalité annoncée. Chaque champ qui collecte une donnée personnelle se justifie ; chaque traitement a sa finalité et sa base légale documentées (tableau dans `docs/` : donnée → finalité → base légale → durée de conservation → sous-traitants). Ce tableau alimente la politique de confidentialité et le registre des traitements tenu par le chef de projet.
- **Durées de conservation définies ET appliquées techniquement** (purge/anonymisation automatique des données expirées — pas seulement une promesse dans la politique).
- **Bandeau cookies** (si cookies non essentiels : analytics, marketing…) : AUCUN traceur non essentiel ne se charge avant le consentement explicite. « Refuser » est aussi simple et visible qu'« Accepter ». Le choix est modifiable et RETIRABLE à tout moment via un lien permanent en pied de page ; la preuve du choix est conservée quand nécessaire.
- Préférer un analytics respectueux de la vie privée et sans cookies (mesure d'audience exemptée de consentement) quand le besoin est une simple mesure d'audience — moins de friction, moins de risque juridique (section 20).
- Les formulaires distinguent les bases : consentement (case NON pré-cochée — newsletter…), contrat, obligation légale. Finalité indiquée + lien vers la politique de confidentialité.
- **Droits des personnes** : accès, rectification, export et suppression des données facilement exerçables ; la suppression de compte est claire et effective.
- Pas de données personnelles dans les logs (règle 2.6) ni dans les outils d'analytics (section 20) ; pas de données réelles en environnement de dev/test sans anonymisation.
- Hébergement des données personnelles dans l'UE de préférence ; transferts hors UE encadrés ; tout sous-traitant (email, analytics, hébergeur, paiement) listé dans la politique de confidentialité et contractualisé (DPA).
- Données sensibles ou de mineurs : protections renforcées (fréquent en formation ! — consentement parental si requis, pas de profilage) — à instruire avec le chef de projet AVANT développement ; analyse d'impact (AIPD) si le risque le justifie.
- Procédure de détection et notification des incidents de données (qui prévenir, sous 72 h à la CNIL si requis) — préparée AVANT l'incident.
- La conformité privacy est re-vérifiée à CHAQUE nouvelle fonctionnalité, tag ou intégration tierce.

### 17.2 Conformité juridique et sectorielle
- **Droit de la consommation** (si le site vend) : prix, délais, droit de rétractation, garanties légales, médiateur de la consommation — traités dans les CGV et le tunnel (section 13.2).
- **Avis clients** : règles de collecte, modération et transparence respectées (afficher si les avis sont vérifiés et comment).
- Les contenus publicitaires ou sponsorisés sont identifiables comme tels ; les comparatifs restent vérifiables et non dénigrants ; toute allégation (environnementale, santé, financière, éducative) est justifiée.
- Les concours, promotions et parrainages ont des règles claires et accessibles.
- Les documents contractuels téléchargeables sont datés et versionnés.
- Obligations sectorielles : selon le projet (formation → Qualiopi/CPF, conduite → agréments, immobilier → loi Hoguet…), le chef de projet fournit les exigences et agréments à afficher ; les inscrire en ADAPTATIONS. La conformité est revue à chaque changement de parcours, d'outil tiers ou de pays cible.

---

## 18. FONCTIONNALITÉS AVANCÉES (conditionnelles)

> Ces briques ne s'installent QUE si le projet en a besoin (à décider avec le chef de projet, à déclarer en ADAPTATIONS). Mais quand elles existent, elles suivent ces règles. Elles restent DÉSACTIVABLES sans casser l'expérience de base.

### 18.1 Recherche intelligente
- Au-delà de ~200 contenus ou si la recherche est au cœur du service : moteur dédié (Meilisearch ou Typesense, auto-hébergeables) plutôt que du SQL `LIKE`.
- Exigences : tolérance aux fautes, accents, synonymes et formulations proches ; suggestions pendant la saisie ; résultats en < 100 ms, triés par pertinence UTILISATEUR ; filtres à facettes compréhensibles et combinables si le catalogue le justifie.
- Les recherches sans résultat proposent alternatives, corrections ou contact ; les requêtes sont analysées pour améliorer contenus et offres (section 20).
- La page de résultats de recherche est `noindex` (SEO) mais les pages de contenu qu'elle liste sont indexables.

### 18.2 Personnalisation
- Toute personnalisation (contenu selon profil, localisation, historique) respecte le RGPD : consentement ou nécessité stricte au service. Elle apporte une valeur CLAIRE sans donner une impression intrusive.
- La personnalisation ne casse JAMAIS le SEO : le contenu principal d'une page publique reste identique pour tous et rendu côté serveur ; seuls des blocs secondaires se personnalisent côté client.
- Toujours une version par défaut de qualité pour le visiteur inconnu ; les recommandations qui influencent une décision importante sont explicables et contrôlables.

### 18.3 IA (assistant conversationnel, contenus, recommandations)
- Uniquement si elle apporte une vraie valeur (guider vers la bonne formation, répondre aux questions fréquentes) — jamais un gadget qui bloque l'écran mobile.
- L'appel au modèle d'IA se fait CÔTÉ SERVEUR (service PHP sur HttpClient) : la clé API n'atteint jamais le navigateur (règle 2.1). Rate limiting, plafond de coût et suivi de la latence OBLIGATOIRES.
- L'assistant est cadré sur les contenus du site (réponses ancrées sur une base documentaire validée), affiche qu'il est une IA, reconnaît ses limites et propose TOUJOURS un chemin humain (contact). Les risques d'hallucination, de biais et d'injection de prompt sont traités (garde-fous, filtrage des demandes hors périmètre).
- Prompts, règles et bases de connaissance sont VERSIONNÉS dans le dépôt et testés ; les contenus générés par IA destinés à être publiés sont RELUS avant publication (pages sensibles : validation humaine obligatoire) ; les logs IA sont anonymisés ou protégés.
- Aucune décision juridiquement ou financièrement engageante ne repose uniquement sur une IA non contrôlée ; pas de données personnelles envoyées à un service IA sans encadrement juridique (contrat, pas d'entraînement sur nos données sans accord).
- Le site reste 100 % fonctionnel sans l'assistant (progressive enhancement) ; la fonctionnalité est évaluée (taux de résolution, satisfaction, erreurs graves — section 20).

### 18.4 Éco-conception & sobriété numérique (référence : W3C WSG)
- Chaque fonctionnalité importante JUSTIFIE sa valeur utilisateur ou business — la fonctionnalité la plus sobre est celle qu'on ne construit pas.
- Les budgets de la section 8 sont AUSSI des budgets écologiques : pages légères, JS minimal, images/vidéos optimisées ; le poids des pages et le nombre de requêtes sont suivis dans le temps.
- Aucun script tiers non indispensable (règle 2.5). Auditer régulièrement : chaque script, tracker et widget doit prouver sa valeur.
- Pas de vidéo en lecture automatique ; toute vidéo est chargée au clic (façade), hébergée en résolution adaptée et de durée utile.
- Pas d'animation ni de requête en boucle quand l'onglet est inactif ; les choix graphiques évitent les effets coûteux sans valeur.
- Cache agressif (section 8.4) : la page la plus verte est celle qu'on ne recalcule pas. Les requêtes serveur sont réduites par regroupement et cache.
- Les contenus obsolètes ou peu consultés sont archivés, fusionnés ou supprimés (règle partagée avec le SEO, section 9.3) ; les documents lourds sont remplacés par du HTML quand possible ; les emails automatiques sont utiles, ciblés, non redondants.
- Les environnements et ressources inutilisés sont arrêtés ou supprimés (section 23) ; l'hébergement tient compte de la localisation et d'engagements environnementaux vérifiables.
- Testé sur appareils modestes et réseaux contraints (section 24). La sobriété s'arbitre AVEC l'accessibilité et le besoin métier, jamais contre eux.

### 18.5 Internationalisation & multilingue
- Défaut : français uniquement. Activer le composant Translation multi-locale (routes localisées) UNIQUEMENT si le projet vise plusieurs langues (déclarer en ADAPTATIONS).
- SI le multilingue est activé :
  - TOUTE chaîne visible passe par le système de traduction, avec les clés présentes dans TOUTES les langues configurées — jamais de mélange de langues sur une page (une traduction incomplète ne part pas en production).
  - Les traductions des pages à enjeu (vente, juridique) sont humaines ou validées par un humain ; les contenus juridiques et commerciaux sont LOCALISÉS (adaptés au pays), pas seulement traduits.
  - Formats de date, heure, adresse, téléphone et devise adaptés au pays ; fuseaux horaires explicites pour rendez-vous et délais ; les formulaires acceptent les formats internationaux.
  - **hreflang** correct entre les versions linguistiques (généré depuis les routes localisées) ; le SEO n'est pas fragmenté inutilement entre variantes (section 9.1).
  - Le changement de langue CONSERVE le contexte (on reste sur la page équivalente, pas retour à l'accueil).
  - Le système de traduction gère pluriels, genre et variables ; les interfaces sont testées avec les textes les plus longs (règle 6.2).
  - Si une langue à écriture droite-gauche est ciblée : propriétés CSS logiques (`margin-inline`, `padding-inline`, `text-align: start/end`) — JAMAIS de `left`/`right` codés en dur.
  - Les performances sont vérifiées depuis les zones géographiques ciblées (CDN) et les analytics segmentent par pays/langue (section 20).

### 18.6 PWA — application web installable (si le besoin le justifie)
- À activer quand la cible revient souvent sur le site depuis mobile SANS qu'une vraie app mobile se justifie (espace élève, suivi de dossier) — décision en ADAPTATIONS. Pour un vrai besoin d'app, utiliser le standard mobile LINDBERGH FORMATION.
- SI activée : manifest complet (nom, icônes, couleurs), installabilité testée sur iOS et Android, page hors ligne de repli élégante (pas d'écran d'erreur navigateur), stratégie de cache PRUDENTE (jamais de contenu périmé sur les prix ou les données de compte), mise à jour du service worker sans casser la session en cours.
- La PWA ne remplace JAMAIS le rendu serveur ni ne dégrade le SEO : c'est une couche d'amélioration progressive.

---

## 19. GESTION DE CONTENU & BACK-OFFICE (si des non-développeurs publient)

> Dès que le chef de projet ou son équipe doit publier/modifier des contenus régulièrement (formations, articles, tarifs), le site DOIT offrir un moyen de le faire SANS développeur — sinon le site meurt de contenus périmés. Solution selon le besoin (à déclarer en ADAPTATIONS) : un back-office d'administration (EasyAdmin ou pages `/admin` protégées), ou un CMS headless.

- Les contributeurs publient les contenus courants sans intervention développeur ; les modèles de pages et champs STRUCTURÉS (titre, accroche, prix, image…) empêchent les erreurs de mise en page et les copier-coller incohérents — jamais de champ « HTML libre » comme outil principal.
- Les images téléversées sont AUTOMATIQUEMENT redimensionnées et optimisées (le contributeur n'a pas à connaître la règle des 200 Ko).
- Le back-office vérifie les champs SEO essentiels avant publication (title, description, alt) et alerte s'ils manquent.
- Rôles séparés si plusieurs contributeurs (rédaction, validation, publication, administration) ; les contenus critiques (tarifs, pages légales, pages à forte audience) ont un workflow de validation ou au minimum une relecture obligatoire.
- Prévisualisation fidèle au rendu final avant publication ; historique des versions permettant de restaurer une page.
- Contenus programmables et expirables proprement (une promo terminée disparaît seule).
- Le back-office est protégé : MFA pour les administrateurs (section 16.1), accès journalisés.
- Les liens internes cassés sont détectés (vérification automatique périodique — section 24).
- La gouvernance est écrite : qui crée, valide, publie et révise chaque famille de contenus, à quelle fréquence (le chef de projet la définit ; `docs/gouvernance-contenus.md`).

---

## 20. DONNÉES, ANALYTICS & PILOTAGE

> On ne pilote pas à l'aveugle : chaque objectif de la section 1 a sa mesure. Mais mesurer n'autorise pas à violer la vie privée (section 17).

- **Plan de mesure OBLIGATOIRE avant la mise en ligne** (`docs/plan-mesure.md`) : il traduit les objectifs business en événements mesurables — conversions principales, micro-conversions, étapes des tunnels. Chaque événement a un nom cohérent et documenté (convention unique, ex. `formation_reservation_envoyee`).
- Outil d'analytics conforme RGPD (section 17.1) ; **AUCUNE donnée personnelle envoyée dans l'analytics** (pas d'email dans une URL trackée, pas de nom dans un événement).
- Les événements analytics sont centralisés dans un service/contrôleur Stimulus unique (`tracking`) — jamais dispersés en appels bruts dans les gabarits — et TESTÉS avant mise en production (section 24).
- Les conversions macro et micro sont toutes mesurées et distinguées ; les campagnes sont identifiées par des paramètres UTM cohérents ; la qualité des conversions par source compte plus que le volume.
- Les abandons sont analysés PAR PARCOURS et par étape (où perd-on les gens ?) ; les données de recherche interne (section 5.3) alimentent contenus, UX et offres.
- **Des alertes préviennent les chutes anormales** de conversion, de trafic ou de disponibilité — une chute de conversion mérite autant d'attention qu'une erreur serveur (section 21).
- Heatmaps et enregistrements de session : uniquement avec consentement adapté et masquage des champs sensibles.
- Les tableaux de bord répondent à des questions de DÉCISION (le chef de projet doit pouvoir dire : « cette page convertit mal, on la corrige »), distinguent trafic/engagement/conversion/valeur et séparent acquisition payante, organique, directe et referral.
- Revue périodique : tags inutiles supprimés, mesures obsolètes corrigées, écarts entre outils compris et documentés.

---

## 21. OBSERVABILITÉ, EXPLOITATION & SUPPORT

> On ne peut pas garantir un site rapide et disponible si on ne le mesure pas. Détecter, comprendre, corriger — vite.

- **Endpoint de santé** OBLIGATOIRE : une route `/health` (contrôleur dédié) renvoie `{ status: 'ok' }` + vérification de l'accès base. C'est lui que la surveillance externe interroge.
- **Surveillance de disponibilité** (uptime) : un service externe interroge `/api/health` en continu et alerte en cas de panne (24h/24). Pour un site critique : test synthétique régulier du PARCOURS clé (pas seulement la page d'accueil — le tunnel de conversion).
- **Suivi des erreurs** : outil de suivi (ex. Sentry ou équivalent) pour chaque erreur SERVEUR ET FRONT de production, avec fréquence, priorisation et données personnelles purgées. Chaque erreur récurrente a un ticket.
- **Logs structurés** (JSON) via Monolog : niveau, horodatage, identifiant de corrélation — corrélables entre services, exploitables, minimisés en données personnelles.
- **Métriques techniques** suivies : latence, taux d'erreur, saturation (CPU, mémoire, disque, base), trafic — selon la plateforme d'hébergement.
- **Alertes UTILES et actionnables** : chaque alerte a un destinataire et une action attendue ; pas de bruit (une alerte ignorée est une alerte à supprimer ou corriger). Niveaux de gravité et procédure d'escalade définis avec le chef de projet ; objectif de disponibilité (SLA interne) écrit.
- Les anomalies BUSINESS (chute de conversion, chute de trafic — section 20) déclenchent autant d'attention que les erreurs techniques.
- **Performance en continu** : Lighthouse à chaque évolution majeure + Core Web Vitals terrain (section 8.1).
- Support : les erreurs affichées aux utilisateurs portent un identifiant de trace communicable au support ; le support peut diagnostiquer sans accès excessif aux données ; les bugs signalés publiquement sont reliés à des tickets et suivis jusqu'à résolution ; les erreurs utilisateur fréquentes alimentent la roadmap UX.
- Incidents : post-mortem sans recherche de coupable pour tout incident sérieux (cause, correction, prévention — dans `docs/decisions.md`) ; message de statut/incident prévu pour les services critiques ; surveillance renforcée pendant les périodes sensibles (campagnes, rentrée, lancement).

---

## 22. SCALABILITÉ, RÉSILIENCE & CONTINUITÉ

- Le site doit encaisser 100 → 100 000 visiteurs sans refonte : c'est la conséquence des règles déjà posées (rendu serveur + cache HTTP, CDN, requêtes indexées et paginées, pas d'état en mémoire serveur).
- **Aucun état de session en mémoire du processus PHP** : les sessions sont stockées dans un backend partagé (base de données ou Redis via le composant Cache/Session), pas dans la mémoire d'un worker ; l'app tourne en plusieurs instances derrière un load balancer sans rien changer ; les sessions survivent aux redémarrages et déploiements.
- **CDN** devant le site en production (assets statiques au minimum, pages cachées si possible) ; pare-feu applicatif (WAF) selon la criticité.
- **Points uniques de défaillance identifiés** : lister ce qui, en tombant, fait tomber le site (base, hébergeur, DNS, paiement, CDN, API tierce) et documenter le comportement attendu pour chacun. **Modes dégradés définis** pour les dépendances critiques : si la recherche tombe, le catalogue reste navigable ; si l'emailing tombe, les envois sont mis en file et rejoués — jamais d'opération perdue en silence.
- Les traitements non urgents (emails, exports, synchronisations) sont ISOLÉS des parcours temps réel (Messenger + transport asynchrone) ; files, caches et workers supervisés (section 21).
- Les limites techniques sont CONNUES et écrites : trafic maximal encaissable, quotas d'emails, limites API tierces, stockage.
- Les pics prévisibles (rentrée, lancement de campagne, soldes) sont anticipés : test de charge sur les parcours critiques AVANT le pic (section 24), capacité planifiée.
- **Sauvegardes automatiques** : base quotidienne chiffrée (section 15) + fichiers uploadés. Objectifs écrits : RPO (perte de données max acceptable) et RTO (durée de reprise max). **Une sauvegarde non testée n'existe pas** : restauration testée et procédure documentée (PRA : quoi restaurer, comment, en combien de temps, par qui).
- Déploiement reproductible : l'image Docker se construit et démarre sur une machine vierge avec le seul `.env` comme configuration. `docker compose up` DOIT suffire en local. Zéro secret dans l'image (variables d'environnement au runtime).
- Les arbitrages coût/résilience sont documentés et assumés (`docs/decisions.md`) — un petit site vitrine n'a pas besoin d'une infra multi-région, et c'est un choix écrit.

---

## 23. DEVOPS, CI/CD, OUTILLAGE & ENVIRONNEMENTS

### 23.1 Outillage
- **Composer** — seul gestionnaire de dépendances PHP. Le serveur de dev est lancé via `symfony serve` ; les assets sont compilés via `asset-map:compile`.
- **PHP-CS-Fixer + PHPStan/Psalm** — le code DOIT passer le formatage et l'analyse statique avant tout push ; conventions automatisées, pas de débat de style.
- **PHPUnit** — tests (voir 2.9 et section 24).
- **Typage strict PHP 8.4** — pas de `mixed` échappatoire, pas d'erreurs masquées.
- **Lighthouse** — l'outil de vérification des budgets de la section 8 et d'une partie du SEO/accessibilité.

### 23.2 CI/CD
- **Une CI est OBLIGATOIRE dès que le dépôt est partagé** (GitLab CI / GitHub Actions) : à chaque Merge Request, elle exécute AU MINIMUM l'analyse statique (PHPStan/Psalm), PHP-CS-Fixer (contrôle), `composer validate`, les tests s'ils existent, et `composer audit`. Une CI rouge BLOQUE la fusion.
- Les déploiements sont reproductibles et traçables (image Docker taguée, numéro de version, changelog) ; les migrations de base sont automatisées dans le déploiement (section 15).
- **Un déploiement peut être annulé rapidement** : stratégie de rollback documentée AVANT la première mise en production (redéployer l'image précédente + compatibilité des migrations).
- **Test de fumée post-déploiement AUTOMATIQUE** : après chaque mise en production, un script vérifie les pages clés (accueil, page de conversion, endpoint de santé, statut 200 + contenu attendu) ; échec = alerte immédiate et rollback.
- **Mises à jour de dépendances automatisées et surveillées** (Renovate ou Dependabot, sur Composer ET importmap) : les propositions sont revues régulièrement, les correctifs de sécurité passent en priorité ; la montée de version majeure de Symfony (et de PHP) est PLANIFIÉE (au moins une fois par an), jamais subie.
- Les secrets de CI/CD vivent dans le coffre de la plateforme (variables protégées), JAMAIS dans le dépôt.

### 23.3 Environnements & hébergement
- Environnements SÉPARÉS : dev (local), préproduction (bloquée à l'indexation, section 9.1, sans données personnelles réelles — section 17.1), production. Jamais de test en production. Les configurations ne divergent pas sans justification documentée.
- Le rendu serveur exige un runtime PHP 8.4 (PHP-FPM derrière nginx, ou FrankenPHP), PAS un hébergement statique seul. Le projet se déploie comme conteneur Docker.
- Accès production restreints, nominatifs et journalisés.
- Certificats TLS renouvelés automatiquement (Let's Encrypt ou équivalent) ou surveillés avec alerte avant expiration.
- Domaines, DNS et certificats INVENTORIÉS (`docs/infrastructure.md` : registrar, DNS, expiration, responsable).
- Tâches planifiées (cron) et workers supervisés : une tâche qui échoue en silence est un incident invisible.
- Coûts d'hébergement suivis ; environnements temporaires supprimés (coût, sécurité, sobriété — section 18.4).
- _(Détails spécifiques au projet à compléter : hébergeur, domaine, DNS, certificats.)_

---

## 24. TESTS & RECETTE

> On ne livre pas ce qu'on n'a pas testé. La recette couvre les cas nominaux ET les cas d'erreur.

- **Tests automatisés (PHPUnit)** : toute logique métier critique (calculs de prix, règles d'inscription, validation) a des tests unitaires. Les parcours complexes sont couverts par des tests fonctionnels (WebTestCase). Les contrats d'API critiques sont testés.
- **Plan de recette avant CHAQUE mise en production** — c'est la checklist de la section 25, complétée par les parcours spécifiques du projet. Les tests couvrent :
  - cas nominaux ET cas d'erreur (paiement refusé, champ invalide, session expirée) ;
  - données EXTRÊMES : textes très longs, caractères spéciaux, accents, emojis, valeurs limites ;
  - navigateurs réellement utilisés par la cible (Chrome, Safari iOS, Firefox, Edge) ;
  - les 4 largeurs d'écran (section 7) + au moins un VRAI appareil mobile ;
  - accessibilité : outils automatiques + navigation clavier + lecteur d'écran sur les parcours clés (section 10) ;
  - performance : Lighthouse sur chaque gabarit (section 8.1) — une régression au-delà des seuils bloque ;
  - **emails transactionnels** : rendu (clients mail principaux), contenu, délivrabilité (SPF/DKIM/DMARC configurés) ;
  - liens internes, redirections et fichiers téléchargeables vérifiés automatiquement (pas de 404, pas d'image manquante — un composant cassé bloque la recette) ;
  - rôles et permissions : un utilisateur ne voit QUE ce qu'il doit voir (tests d'accès interdits) ;
  - tracking : les événements analytics critiques déclenchent correctement (section 20) ;
  - SEO : les changements de balises/URLs font partie de la recette, pas d'une vérification après coup.
- **Tests de charge** avant tout pic prévisible ou lancement à fort trafic (section 22), mesurant aussi l'expérience utilisateur (temps de réponse sous charge), pas seulement la survie du serveur.
- Les anomalies trouvées sont priorisées par impact utilisateur et risque business ; les contributeurs métier participent à la recette des contenus critiques.
- Un bilan qualité est conservé pour chaque version majeure (`docs/`).

---

## 25. DEFINITION OF DONE — CHECKLIST AVANT MISE EN LIGNE

> Claude DOIT dérouler cette checklist et donner le résultat point par point avant toute mise en production. Un point non conforme = mise en ligne BLOQUÉE (ou dérogation explicite du chef de projet, notée dans ADAPTATIONS). Elle reprend les critères P0 de la grille d'audit 600.

**Qualité technique**
- [ ] Analyse statique (PHPStan/Psalm), PHP-CS-Fixer et `composer validate` passent sans erreur ; CI verte.
- [ ] Aucune sortie de debug (`dump`/`dd`/`var_dump`), aucun `mixed` échappatoire, aucun accès Doctrine hors repositories, aucun secret dans le code (grep de vérification).
- [ ] `docker compose up` fonctionne sur environnement vierge ; rollback documenté.

**UX / UI / Responsive**
- [ ] Test des 4 largeurs (375 / 768 / 1440 / 2560 px) sur toutes les pages + un vrai mobile : rien de cassé, pas de défilement horizontal.
- [ ] Les états (chargement / erreur / vide / succès) existent sur chaque vue de données.
- [ ] Page 404 soignée ; aucune page cul-de-sac ; aucune page orpheline ; breadcrumb sur les pages profondes.
- [ ] Un inconnu comprend l'activité et l'action attendue en 5 secondes sur l'accueil.
- [ ] Composants testés avec contenus réels longs ; pop-ups refermables partout.

**Performance**
- [ ] Lighthouse mobile ≥ 90 en Performance sur CHAQUE gabarit ; LCP < 2 s ; CLS < 0,1 ; TTFB < 800 ms.
- [ ] Image LCP préchargée ; `preconnect` vers les origines critiques ; préchargement Turbo actif.
- [ ] Toutes les images en formats modernes (WebP/AVIF), dimensionnées, lazy-loadées ; polices locales en WOFF2.
- [ ] Cache HTTP configuré (`Cache-Control`/ESI) ; listes paginées ; scripts tiers tous justifiés.

**SEO**
- [ ] Métadonnées Twig (title/description/OG) complètes et UNIQUES sur chaque page publique ; un seul H1 par page.
- [ ] Sitemap accessible et soumis ; robots.txt correct ; **la production n'est PAS en noindex** ; Search Console configurée.
- [ ] URLs propres, canonicals posées, redirections 301 sans chaîne, données structurées validées (test Google Rich Results).
- [ ] Tout le contenu SEO est rendu côté serveur (vérifier le HTML source, pas le DOM).

**GEO (moteurs de réponse IA)**
- [ ] Robots IA (GPTBot, ClaudeBot, PerplexityBot, Google-Extended…) NON bloqués en production (robots.txt, serveur, CDN, pare-feu vérifiés).
- [ ] `llms.txt` présent, à jour et cohérent avec le sitemap.
- [ ] Chaque page clé commence par une réponse directe autoportante nommant l'entreprise ; FAQ en format question→réponse balisée.
- [ ] Faits, prix et dates exacts et datés ; données structurées Organization/LocalBusiness complètes ; NAP cohérent site/fiche Google/annuaires.
- [ ] Trafic des moteurs IA segmenté dans l'analytics ; test manuel de citation effectué sur les questions clés de la cible.

**Accessibilité**
- [ ] Parcours complet au clavier possible, sans piège de focus ; focus visible partout.
- [ ] Contrastes AA vérifiés ; `alt` sur toutes les images ; labels sur tous les champs ; libellés de liens explicites.
- [ ] `lang="fr"`, zoom 200 % utilisable, `user-scalable=no` absent ; test lecteur d'écran effectué sur les parcours clés.
- [ ] WCAG 2.2 : cibles ≥ 24 px, focus jamais masqué par un élément sticky, alternative au glisser-déposer, collage autorisé dans les champs de code.
- [ ] Déclaration d'accessibilité publiée si le statut de l'organisation l'exige.

**Contenus / Confiance / Légal**
- [ ] Mentions légales, politique de confidentialité, CGV/CGU en ligne avec les VRAIES informations (aucun `TODO-PM`, aucun lorem ipsum nulle part).
- [ ] Aucun avis/témoignage/chiffre inventé (revue de la règle 2.10) ; avis datés ; prix et conditions exacts et transparents AVANT engagement.
- [ ] Droits maîtrisés sur toutes les images, polices et contenus (règle 2.11).
- [ ] Contenus importants en HTML (pas seulement en PDF) ; date de mise à jour sur les contenus évolutifs.

**RGPD**
- [ ] Aucun traceur non essentiel avant consentement ; « Refuser » aussi accessible qu'« Accepter » ; choix modifiable en pied de page.
- [ ] Tableau données → finalité → base légale → durée de conservation complété ; durées appliquées techniquement.
- [ ] Export et suppression des données utilisateur possibles ; sous-traitants listés ; aucune donnée personnelle dans logs et analytics.

**Sécurité**
- [ ] HTTPS actif avec redirection et HSTS ; en-têtes de sécurité en place (NelmioSecurityBundle) ; aucun contenu mixte.
- [ ] Rate limiting actif sur login et formulaires ; mots de passe hachés (Argon2id/bcrypt) ; MFA sur les comptes admin.
- [ ] Routes sensibles : session, rôle ET appartenance des données vérifiés côté serveur (tests d'accès interdits passés).
- [ ] `composer audit` sans vulnérabilité critique ; journaux de sécurité actifs.

**E-commerce (si le site vend)**
- [ ] Prix total visible avant validation ; CGV accessibles avant engagement ; rétractation et garanties couvertes.
- [ ] Tunnel testé : nominal + paiement refusé + timeout + double clic (idempotence) ; webhooks signés ; factures/confirmations envoyées.

**Exploitation & pilotage**
- [ ] `/health` répond ; surveillance uptime configurée ; suivi des erreurs serveur ET front actif ; alertes définies avec destinataires.
- [ ] Sauvegarde base configurée, chiffrée ET restauration testée ; RPO/RTO écrits ; PRA documenté.
- [ ] Plan de mesure appliqué : événements de conversion testés ; alertes de chute de conversion/trafic en place.
- [ ] Emails transactionnels testés (rendu + SPF/DKIM/DMARC), envoyés via Messenger avec retry, séparés du marketing.
- [ ] Test de fumée post-déploiement automatique en place ; mises à jour de dépendances automatisées (Renovate/Dependabot) actives.
- [ ] Pooling de connexions BDD si multi-instances/nombreux workers ; règles d'archivage définies sur les tables qui grossissent.
- [ ] Tâches cron supervisées ; certificats TLS auto-renouvelés.
- [ ] Gouvernance des contenus définie (qui publie, qui valide) si des non-développeurs contribuent.

---

## 26. VÉRIFICATION ANTI-CONTRADICTION

Avant de finaliser tout travail, re-scanne : est-ce qu'un exemple, un commentaire ou du code écrit contredit une règle ci-dessus (une sortie de debug `dump`/`dd`, un `mixed` échappatoire, un accès Doctrine hors repository, du contenu SEO injecté côté client, un push direct sur main, un secret exposé au client, une couleur en dur hors tokens, une image sans `alt`, une page sans métadonnées Twig, un robot IA bloqué par accident, un `llms.txt` obsolète, un traceur chargé avant consentement, une donnée personnelle dans un log ou un événement analytics, une mutation d'état via GET, une redirection ouverte, un contenu inventé, une image sans droits, un prix affiché sans ses frais, un champ de code qui interdit le coller, un email transactionnel sans version texte, une table de logs sans règle de purge) ? Si oui, corrige AVANT de committer.




