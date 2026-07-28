# Inventaire de transfert — RAF 360 (phase R2)

> Extraction fidèle de l'existant Next.js **gelé** (baseline/spécification) en vue de la
> reconstruction Symfony 7.4 (Chemin B, décision R3 du 2026-07-27).
> **Ce document ne décide rien** : il relève ce qui devra être re-exprimé à l'identique.
> Source de vérité du design = `src/app/globals.css` (c'est ce qui rend à l'écran).
> Date : 2026-07-27.

---

## A. Tokens de design (extraits à l'exact de `src/app/globals.css`, bloc `:root`)

### A.1 Couleurs — palette produit (app)

| Token CSS | Valeur | Usage |
|-----------|--------|-------|
| `--navy` | `#14306b` | couleur primaire app, boutons, sidebar active |
| `--navy2` | `#1e2761` | dégradés (hero-band) |
| `--blue` | `#2c6fb0` | accents, KPI, liens |
| `--ice` | `#cadcfc` | fonds doux, surlignage |
| `--iceb` | `#9cc2f0` | numéro du logo |
| `--gold` | `#eda323` | accent ambre (boutons, barres de progression) |
| `--green` | `#2fa37c` | états positifs / validés |
| `--red` | `#c0503f` | états négatifs / retards |
| `--redl` | `#fce9e5` | fond d'alerte doux |
| `--ink` | `#101b3a` | texte principal |
| `--slate` | `#5b6480` | texte secondaire |
| `--bg` | `#f4f7fc` | fond applicatif |
| `--card` | `#ffffff` | fond des cartes |
| `--line` | `#dce4f2` | bordures / séparateurs |

### A.2 Couleurs — échelle marine étendue (marketing)

| Token | Valeur | | Token | Valeur |
|-------|--------|-|-------|--------|
| `--navy-950` | `#071634` | | `--navy-600` | `#1c3f86` |
| `--navy-900` | `#0b1e45` | | `--blue-500` | `#2c6fb0` |
| `--navy-800` | `#0e2a5e` | | `--blue-400` | `#5b94d6` |
| `--navy-700` | `#14306b` | | `--blue-300` | `#8fbbee` |
| | | | `--blue-100` | `#dbe8fb` |

### A.3 Couleurs — ambre (accent) & neutres marketing

| Token | Valeur | | Token | Valeur |
|-------|--------|-|-------|--------|
| `--gold-600` | `#d98a10` | | `--paper` | `#ffffff` |
| `--gold-500` | `#eda323` | | `--paper-2` | `#f7f9fd` |
| `--gold-400` | `#f6b743` | | `--paper-3` | `#eef3fb` |
| | | | `--ink-soft` | `#33406a` |
| | | | `--muted` | `#64708f` |
| | | | `--hairline` | `#e4eaf5` |

> ⚠️ **Divergence à corriger** : `src/lib/tokens.ts` (constante `C`) prétend « rester synchronisé avec globals.css » mais ne l'est **pas** (ex. `navy` `#1E2761` au lieu de `#14306b` ; `gold` `#E4B95B` au lieu de `#eda323` ; `ink` `#1A2238` au lieu de `#101b3a`). **En cible Symfony, une seule source de vérité** : les variables CSS. Ne pas recopier `tokens.ts`.

### A.4 Typographie

- **Serif (titres)** : **Fraunces** — `next/font/google`, variable `--font-serif`, axe optique `opsz`, `display:swap`. Stack : `var(--font-serif), Georgia, "Times New Roman", serif`.
- **Sans (corps)** : **Inter** — `next/font/google`, variable `--font-sans`, `display:swap`. Stack : `var(--font-sans), "Segoe UI", system-ui, -apple-system, sans-serif`.
- ⚠️ **Point de transfert** : la cible AssetMapper n'a **pas de build Node**, donc `next/font` disparaît. À rejouer par **auto-hébergement des `.woff2`** (Fraunces + Inter) via `@font-face`, ou feuille Google Fonts — en conservant les variables `--font-serif` / `--font-sans` pour ne rien changer au CSS.
- Échelle fluide (à l'exact) : `.display` `clamp(2.6rem,6vw,4.6rem)` poids 500 ; `.h2` `clamp(2rem,3.6vw,3rem)` ; `.h3` `clamp(1.3rem,2vw,1.6rem)` ; `.lead` `clamp(1.05rem,1.4vw,1.28rem)` ; `.stat-num` `clamp(2.4rem,4vw,3.4rem)`. Interlignage titres serré (`line-height:1.04–1.2`), `letter-spacing` négatif sur les grands titres.

### A.5 Espacement, formes, ombres, easing

- **Conteneur** : `--container: 1200px` (marketing), variante `.container-narrow` `860px`, padding latéral `24px`.
- **Rythme vertical** : `.section` `96px 0` (→ `64px` sous 760px) ; `.section-sm` `64px 0`.
- **Rayons** (non tokenisés mais cohérents) : boutons app `8px`, cartes app `12px`, `feature-card` `20px`, `price-card`/`product-frame`/`surface` `20–22px`, `m-btn`/pills `999px`.
- **Ombres** : `--shadow-sm` `0 1px 2px rgba(11,30,69,.05)` ; `--shadow-md` `0 12px 30px -12px rgba(11,30,69,.18)` ; `--shadow-lg` `0 40px 80px -30px rgba(11,30,69,.35)`.
- **Easing signature** : `--ease: cubic-bezier(0.22,1,0.36,1)` (utilisé partout pour les transitions).
- **Points de rupture** observés : `940px` (marketing/nav), `760px` (app + footer + sections), `560px`, `520px`.

### A.6 Composants stylés réutilisables (classes à reporter tel quel)

Boutons (`.btn`, `.btn-primary/-accent/-ghost/-sm/-xs`, `.m-btn`, `.m-btn-primary/-gold/-ghost/-sm`), `.badge`, `.chip`, `.pill`, `.card` (+`.clickable`), `.feature-card`, `.price-card` (+`.featured`), `.quote-card`, `.surface`, `.eyebrow`, `.stat-num/.stat-label`, `.hero`/`.page-hero`, bandeaux `.band-dark/-soft/-paper3`, `.product-frame`/`.win-dots`/`.float-card`, `.steps/.step/.step-n`, `.logo-row`, `.check-li`, formulaires (`.form-field`, `.form-label`, `.field`). App : `.shell`, `aside`/`.nav-item`, `header.top`, `.kpi-card` (+`.kpi-navy/-blue/-green/-red/-gold`), `.app-ic`, `.ai`/`.ai-*`/`.scn*` (bloc copilote IA), arbres `.tree-*`/`.pro-row`, trésorerie `.cash-tip`/`.slot`, onboarding `.ob-*`/`.kv`.

---

## B. Motion & accessibilité (à préserver à l'identique)

| Effet | Classe / mécanique | Détail exact |
|-------|--------------------|--------------|
| Révélation au scroll | `.reveal` (+ `[data-anim=left/right/scale]`, `.in`) | `opacity 0→1`, `translateY(26px)→0`, `0.7s var(--ease)` |
| Flottement | `.floaty` / `.floaty-slow` | keyframes `floaty` 6s / 8s |
| Rotation logo | `.spin-slow` | `spin-slow` 44s linéaire |
| Entrée en cascade des écrans app | `.app-main > *` | keyframes `appIn`, délais `.02s`→`.43s` par enfant |
| Carte onboarding | `.ob-card-anim` | `appIn 0.45s` |

> ♿ **Exigence forte** : deux blocs `@media (prefers-reduced-motion: reduce)` désactivent toutes les animations/transitions. **À reconduire obligatoirement** dans la cible (accessibilité — checklist §25).

---

## C. Règles métier repérées (partie « fondations »)

Extraites de `src/lib/format.ts` (les règles portées par les pages/données sont complétées en **§E**) :

- **Date de référence de la démo** : `TODAY = "2026-02-10"` — pivot de tous les calculs de retard. En cible, remplacer par la date réelle du serveur.
- **Format de date FR** : `frDate(iso)` → `« J mois »` avec mois abrégés français (`janv.`, `févr.`, … `déc.`). Cible : `IntlDateFormatter`/Twig `|format_date` locale `fr`.
- **Échéance en retard** : `isOverdue(iso) = iso < TODAY` (comparaison lexicographique de dates ISO). Cible : comparaison de `DateTimeImmutable`.

### C.2 Règles portées par les données / pages (`src/data/demo.ts` + pages app)

- **Échéances** (`ECHEANCES`) : triées par `iso.localeCompare` (plus proche → plus lointaine) ; chaque item a un flag `mt ∈ {réel, estimatif}` → affichage différencié (chip couleur, or si estimatif). Dashboard = `.slice(0,4)`.
- **Statuts** : `statutColor` / `statutLabel` mappent des états (« À faire »→slate, « À valider »→gold, « En retard »→red, `risque`→red « Risque élevé », `confirmer`→gold « À confirmer », `escalade`→navy2 « Escalade avocat », `attente`→slate « En attente »). Dans `calendar`, `isOverdue` **force** l'état « En retard » (rouge, fond `redl`), prioritaire.
- **Trésorerie** (`CASH`, `realIdx:8`) : indices `0..8` = réalisé (trait plein vert), `9..11` = prévisionnel (pointillé or). Zoom : `start = zoom>0 ? min(zoom*3,8) : 0`. Ventilation en dur — encaissements Ventes 78 % / Subventions 9 % / Autres 13 % ; décaissements Salaires 46 % / Fournisseurs 34 % / Fiscalité+prêts 20 %. Seuils : solde `<40 k€` → rouge ; mois prévisionnel `sol<90` → « sous tension ». `CASH_ALERTS` : `en retard` (rouge) vs `attendu` (navy).
- **Documents** (`DOC_TREE`) : 3 domaines (corp/biz/rh) ; score OCR `conf>=85` → vert sinon or ; `corp` ouvert par défaut.
- **Data room** (`PRO_TREE`, `APPT_SLOTS`) : pros groupés par domaine ; confirmation bloquée tant que `slot===null` ; règle affichée « partage 7 j, révocable, journalisé ».
- **Onboarding** : `TOTAL=2` étapes ; `pct = step0 ? (retrieved?30:6) : step1 ? 62 : 100` ; `fetchSiren` force `784 671 695` en démo ; options TVA sans branchement métier ; input SIREN `maxLength=11`.
- **Factures** : KPIs en dur (À traiter 7 / Validées 34 / Doublons 1) ; jauge e-facture **65 %** (constante récurrente : dashboard, factures, veille).
- **Blog** (`data/blog.ts`) : `date` ISO + `dateLabel` FR pré-calculé (redondant) ; `getPost(slug)` = `find` ; 1er post = « à la une », 3 autres = « articles liés ».

> Ces règles décrivent le **comportement de la démo**. Elles guident la conception du schéma Doctrine et de la logique cible, mais **aucune donnée de démo ne part en production** (voir §F).

---

## D. Métadonnées / SEO de référence (`src/app/layout.tsx`)

- `lang="fr"`, OpenGraph `locale: fr_FR`, `type: website`, `siteName: "RAF 360"`.
- `metadataBase: https://raf360.fr` — **domaine à confirmer** (aucun domaine public actif à ce jour).
- Titre par défaut : `« RAF 360 — Le copilote financier des TPE et PME »` ; gabarit `« %s — RAF 360 »`.
- `description` + `keywords` = **copie marketing à valider** (voir §F, contient des promesses commerciales).
- ⚠️ Cible Symfony : reconstruire ces métadonnées (Twig `block` title/meta + OpenGraph) ; prévoir `sitemap.xml`/`robots.txt` (absents ici).

---

## E. Table des URLs / routes

- **Port de dev** : `3002` (`next dev -p 3002`). **`next.config.ts` vide** : aucune règle de redirect/rewrite déclarée.

| URL | Fichier source | Type | Dynamique ? | Objet |
|-----|----------------|------|-------------|-------|
| `/` | `(marketing)/page.tsx` | marketing | statique | Accueil (hero, features, stats, teaser tarifs, témoignages, CTA) |
| `/produit` | `(marketing)/produit/page.tsx` | marketing | statique | 6 modules produit |
| `/solutions` | `(marketing)/solutions/page.tsx` | marketing | statique | 3 profils (TPE, PME, experts-comptables) |
| `/tarifs` | `(marketing)/tarifs/page.tsx` | marketing | statique | 3 offres + comparatif + FAQ |
| `/a-propos` | `(marketing)/a-propos/page.tsx` | marketing | statique | Mission, valeurs, éditeur, stats |
| `/blog` | `(marketing)/blog/page.tsx` | marketing | statique (liste `POSTS`) | À la une + grille |
| `/blog/[slug]` | `(marketing)/blog/[slug]/page.tsx` | marketing | **dynamique `[slug]`** : `generateStaticParams()` (4 slugs), `notFound()` sinon | Article |
| `/contact` | `(marketing)/contact/page.tsx` | marketing | **redirection** `redirect("/app/onboarding")` (code Next.js par défaut, non explicité) | « Contact remplacé par l'onboarding » |
| `/app/onboarding` | `app/onboarding/page.tsx` | app démo | statique (état client) | Onboarding 2 étapes (SIREN → TVA) |
| `/app/dashboard` | `app/dashboard/page.tsx` | app démo | statique | KPIs, échéances, veille |
| `/app/actions` | `app/actions/page.tsx` | app démo | statique | Centre d'actions |
| `/app/calendar` | `app/calendar/page.tsx` | app démo | statique | Calendrier des obligations |
| `/app/treasury` | `app/treasury/page.tsx` | app démo | statique (état client) | Trésorerie réalisé/prévisionnel |
| `/app/documents` | `app/documents/page.tsx` | app démo | statique (état client) | Coffre-fort documentaire |
| `/app/factures` | `app/factures/page.tsx` | app démo | statique | Factures / e-facture |
| `/app/dataroom` | `app/dataroom/page.tsx` | app démo | statique (état client) | Data room & professionnels |
| `/app/assistant` | `app/assistant/page.tsx` | app démo | statique (état client) | Assistant IA (chat maquette) |

- **Layouts** : `app/layout.tsx` (racine : `lang=fr`, polices, SEO) · `(marketing)/layout.tsx` (Header + Footer) · `app/app/layout.tsx` (Sidebar + Topbar).
- **Nav app** (`demo.ts`, `NAV`) : 8 entrées (dashboard, actions, calendar, treasury, documents, factures, dataroom, assistant). `/app/onboarding` **hors** nav.
- Aucun domaine public actif → **aucune redirection 301 à préserver** ; la table de correspondance d'URL est libre en cible.

---

## F. Contenus — tri « validé » vs « à valider » (règle 2.10)

> ⚠️ **Aucun contenu n'est aujourd'hui « validé ».** Tout ce qui suit est **fictif ou à confirmer** et est **BLOQUANT avant mise en ligne**.

### F.1 Manifestement FICTIF / démo — à retirer ou remplacer

- **Témoignages nominatifs** (accueil, tableau `QUOTES`) : **« Camille Roussel »** (dirigeante agence design), **« Thomas Nguyen »** (gérant conseil), **« Sarah Benkacem »** (cofondatrice studio tech), avec citations type « Le copilote m'a fait gagner un découvert. » → personnes non vérifiables présentées comme clients.
- **Auteur du blog** (`data/blog.ts`) : `author: "L'équipe RAF 360"` (générique) sur les 4 articles.
- **Données de démo de l'app** (`data/demo.ts`) — 100 % fictif : clients « ACME / GAMMA / BÉTA » et montants ; réseau d'experts nommés (« Cabinet Durand & Associés », « Cabinet Novéo Audit », « Me Sophie Lambert », « Me Karim Benali », « Cabinet FiscaConseil », « PaieExpert Services », « Hélène Roux ») ; noms de salariés dans les fichiers RH (« Bulletin_paie_Martin.pdf »…) ; scores OCR (99/97/72 %) ; chiffres trésorerie/factures (« 131 200 € », « 76 k€ », « TVA CA3 3 420 € »…) ; SIREN de démo en dur `784 671 695` / « ARCAN Démo SAS ».
- **Statistiques présentées comme des faits** (marketing) : accueil → « 360° », « 6 domaines », « **100 %** sources officielles, jamais inventées », « 10 min pour configurer » ; à-propos → « 5 domaines », « **100 %** sources officielles françaises », « **0** commission ». Mentions répétées « Mise en route en 10 min », « Hébergement en France ».

### F.2 Peut-être RÉEL — à confirmer explicitement (chef de projet)

- **Tarifs** (`tarifs/page.tsx` + accueil `PRICING`) : Essentiel **49 €/mois HT**, Pilotage **129 €/mois HT**, Cabinet « sur mesure ». Mention atténuante déjà présente : « Tarifs indicatifs… prix définitifs confirmés à la souscription. »
- **Éditeur / raison sociale** : « **Lindbergh Formation / Groupe ARCAN** » (à-propos + metadata) — entité juridique à confirmer ; **aucun SIREN, adresse, email ou téléphone réel** dans le code.
- **Marque / domaine** : « RAF 360 » / `https://raf360.fr` — propriété du domaine à confirmer (le chemin projet dit « DAF 360 »).
- **Adossement « sources officielles »** (accueil `logo-row`) : « Légifrance, impots.gouv.fr, INSEE·SIRENE, URSSAF, BOFiP, net-entreprises » sous « Adossé aux sources officielles françaises » ; « plateforme agréée (PDP) » (produit/factures) → **risque d'allégation** de partenariat/agrément : à valider juridiquement.
- **Affirmations réglementaires** (en clair, à vérifier) : « Réception obligatoire au 1er sept. 2026 », « Émission TPE/PME au 1er sept. 2027 » ; « Aucune commission sur les honoraires ».

### F.3 Absents (obligatoires avant mise en ligne)

Mentions légales, politique de confidentialité, CGV/CGU, bandeau cookies/plan de mesure, `sitemap.xml`, `robots.txt`, coordonnées réelles de contact.

---

## G. Comportements dynamiques → cible Stimulus / Turbo

> Principe : l'**animation reste en CSS** (`globals.css`) ; la JS ne fait le plus souvent qu'**ajouter/retirer une classe**. Aucun `setInterval`/`setTimeout`, **aucun fetch réseau réel** aujourd'hui (SIREN + réponses IA simulés en local) — ce sont précisément les points où brancher des endpoints Symfony.

| Composant / Page | Comportement | Mécanique React actuelle | Cible Symfony |
|------------------|--------------|--------------------------|---------------|
| `marketing/Reveal.tsx` | Apparition au scroll (une fois) | `IntersectionObserver` (threshold 0.14, rootMargin -8%) + classe `.reveal.in` | Stimulus `reveal` (IObserver → classe `in`) ; animation CSS conservée |
| `marketing/Header.tsx` | Header `scrolled` (>12px) + burger menu mobile + fermeture au changement de page | `useState` + listener `scroll` + `usePathname()` | Stimulus `header` (scroll → `.scrolled`, toggle `.mobile-menu.open`, fermeture sur `turbo:load`) |
| `Chrome.tsx` — Sidebar | Item de nav actif | `usePathname()` → `.active` | **Aucune JS** : `app.request.attributes.get('_route')` en Twig |
| `Chrome.tsx` — Topbar | Sélecteur d'entité + cloche (non fonctionnels) | — | Twig pur (futur `dropdown` si l'entité devient réelle) |
| `app/treasury` | Zoom ±, tooltip suiveur de curseur, sélection de point → panneau détail | `useState(zoom/sel/tip)`, handlers SVG `mouseenter/move/leave/click` | Stimulus `treasury-chart` (targets point/tooltip/detail) ; zoom + détail via **Turbo Frame** |
| `app/dataroom` | Accordéon multi-ouvert + sélection pro + grille de créneaux + confirmer (`alert()`) | `useState(openDom/pro/slot)`, `disabled={slot===null}` | Stimulus `disclosure` + `appointment` ; panneau RDV en **Turbo Frame**, confirmation en **Turbo Stream** (remplacer `alert()`) |
| `app/documents` | Arbre Corp/Biz/RH dépliable (corp ouvert par défaut) ; upload/boutons sans handler | `useState(open{corp:true})` toggle | Stimulus `disclosure` (mutualisé) ou `<details>` natif ; futur controller d'upload |
| `app/onboarding` | Stepper 2 étapes + barre de progression + rejeu d'animation ; « fetch » SIREN simulé | `useState(step/retrieved/siren)`, remount via `key` pour rejouer `appIn`, `useRouter().push` | Stimulus `wizard` (étapes + largeur barre) ; rejeu = remove/add classe + reflow ; SIREN réel = **Turbo Frame / endpoint SIRENE** ; fin = `Turbo.visit` |
| `app/dashboard` | Cartes KPI + CTA cliquables (navigation) | `useRouter().push` sur `onClick` | **Aucune JS** : `<a href>` + navigation Turbo native |
| `app/assistant` | Chat (envoi, réponse IA fictive, auto-scroll, Entrée) | `useState(chat/input)`, `useRef` + `useEffect` scroll | Stimulus `chat` ; réponses réelles = **Turbo Stream** (append) depuis le backend |
| `AiInsight.tsx` | Encart copilote + CTA (props) | présentation ; `onClick` via props | Partial Twig `_ai_insight.html.twig` ; CTA = liens/boutons Turbo |
| `app/calendar`, `app/actions`, `app/factures` | Listes + badges calculés ; boutons sans handler | **composants serveur** (pas de `use client`) | **Twig pur** |

**100 % présentationnel (aucune JS)** : `ui.tsx` (`Badge`, `Chip`, `SectionTitle`, `Donut`, `Spark`), `marketing/AppPreview.tsx`, `Logo.tsx`, `Icon.tsx`, `marketing/icons.tsx`, `marketing/Footer.tsx`, et **toutes les pages `(marketing)/*`** (leur seule interactivité = `Reveal` + effets CSS `floaty`/`spin-slow`). → macros/partials Twig.

### G.1 Controllers Stimulus à prévoir (7)

`reveal` · `header` · `disclosure` (dataroom + documents, mutualisé) · `treasury-chart` · `appointment` · `wizard` (onboarding) · `chat`. Le reste = liens `<a>` + Turbo, ou Twig pur.

---

## H. Points de transfert structurants (synthèse préliminaire)

1. **Aucune donnée réelle** : tout vient de `src/data/demo.ts` et `src/data/blog.ts` (fictif). Le schéma Doctrine cible devra être conçu à partir de ces structures, mais **aucune donnée de démo ne part en production**.
2. **`next/font` → `@font-face` auto-hébergé** (pas de build Node en cible).
3. **`tokens.ts` obsolète** : ne pas le transférer ; variables CSS = référence unique.
4. **`prefers-reduced-motion`** : à reconduire tel quel.
5. **Contenus fictifs BLOQUANTS** (témoignages, chiffres, tarifs, coordonnées) : voir §F — à remplacer par du réel validé avant toute mise en ligne.
