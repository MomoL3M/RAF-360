# DAF 360 — Contexte projet (à lire avant toute modification)

Ce fichier est le brief de référence. Claude Code le lit automatiquement au démarrage.
Il porte le contexte issu de la conception (business plan + maquette HTML) que ce dépôt met en œuvre.

## Ce qu'est le produit

DAF 360 est un **SaaS français de pilotage financier, comptable, fiscal, social et juridique**
pour TPE / PME (éditeur : Lindbergh Formation / Groupe ARCAN). Ce n'est **pas** un chatbot de conseil :
c'est un « système d'exploitation du DAF » qui collecte, contrôle, pilote, aide à décider et
**oriente les sujets réglementés vers un professionnel habilité**.

Fil conducteur du produit : un **Copilote IA** présent sur chaque écran. Il doit en permanence
**alerter**, **anticiper les problèmes**, faire de la **veille** (juridique, fiscale, sectorielle, comptable)
et **proposer des scénarios** avec avantages / inconvénients et une recommandation.

Périmètre : **France uniquement**. Sources réglementaires : officielles seulement
(Légifrance, impots.gouv.fr, BOFiP, INSEE/SIRENE, URSSAF, net-entreprises, CNB, CROEC).
**Ne jamais inventer** de chiffre ou de référence réglementaire : dans l'UI, tout élément non vérifié
est présenté comme « estimatif » / « à surveiller », jamais comme un fait.

## Stack actuelle

- **Vite + React 18 (JavaScript / JSX)**, CSS simple avec variables (pas de Tailwind).
- App mono-page : l'état `page` (dans `src/App.jsx`) commute les écrans (comme la maquette d'origine).
- Aucune dépendance de graphes : le graphique de trésorerie est du **SVG fait main** (interactif).

### Migration prévue (décision business plan §7.1)
La cible documentée est **React / Next.js** (web SaaS + PWA mobile-first). Quand le routing par URL,
le SSR ou le SEO deviendront utiles, migrer chaque `screen` vers une route Next.js App Router.
Le découpage actuel (un composant par écran + primitives réutilisables) est fait pour rendre
cette migration mécanique.

## Arborescence

```
src/
  main.jsx                 point d'entrée
  App.jsx                  état global (onboarded, page) + routing par écran
  styles/tokens.css        variables de couleur + classes de composants
  lib/tokens.js            couleurs en JS (doit rester synchro avec tokens.css)
  lib/format.js            TODAY (date de réf. démo), frDate(), isOverdue()
  data/demo.js             TOUTES les données de démonstration (fictives)
  components/
    Logo.jsx               nouveau logo 360° (double flèche SVG)
    Icon.jsx               NavIcon (icônes de nav descriptives) + Sparkle
    ui.jsx                 Badge, Chip, SectionTitle, Donut, Spark
    AiInsight.jsx          *** composant signature : le Copilote IA ***
    Chrome.jsx             Sidebar + Topbar
  screens/
    Dashboard, Actions, Calendar, Treasury, Documents,
    Factures, DataRoom, Assistant, Onboarding
```

## Charte graphique (à respecter strictement)

- Couleurs : navy `#1E2761`, navy2 `#2C3A7A`, bleu `#3B6FB6`, bleu glacier `#CADCFC` / `#9CC2F0`,
  ambre `#E4B95B`, vert `#2FA37C`, rouge `#C0503F`, rouge clair `#FCE9E5`, encre `#1A2238`,
  slate `#5B6480`, fond `#F4F7FC`, ligne `#DCE4F2`.
- Typo : **Georgia** (serif) pour les titres/affichage, **Segoe UI** pour le corps.
- Sévérités du Copilote : rouge = alerte, ambre = anticipation, bleu = suggestion, vert = opportunité, navy2 = veille.
- Toute nouvelle donnée dynamique en JS doit utiliser `C` de `lib/tokens.js` ; toute classe passe par `tokens.css`.

## Le composant Copilote (`AiInsight`)

C'est l'élément à réutiliser pour toute nouvelle fonctionnalité IA. Props :
`sev`, `kind`, `title`, `body`, `scenarios` (liste `{h, pro[], con[]}`), `reco`, `cta` (liste `{t, primary, onClick}`), `src`.
Règle produit : dès qu'un écran expose un domaine (trésorerie, factures, paie, juridique…),
il doit porter au moins un `AiInsight` qui alerte / anticipe / propose des scénarios.

## État fonctionnel des écrans (déjà implémenté)

- **Onboarding** : SIREN (simulable) → fiche entreprise « récupérée SIRENE/INSEE » (démo) → régime de TVA.
- **Tableau de bord** : alerte trésorerie + scénarios, KPIs, prochaines échéances (avec montants), veille 4 domaines.
- **Centre d'actions** : liste priorisée + anticipation.
- **Calendrier** : échéances triées (proche → lointain), **montant acompte IS estimatif**, retards surlignés en rouge clair.
- **Trésorerie** : 3 KPIs, courbe SVG à 2 couleurs (fonctionnelle réalisée / prévisionnelle),
  échelles X et Y, **infobulle au survol**, **tableau détaillé au clic**, **zoom ➕/➖**,
  alertes d'encaissement (montant + mode + date).
- **Documents** : arborescence Corporate / Business / RH + simulateur de paie.
- **Factures** : KPIs + anticipation relances + préparation e-facture.
- **Data room** : réseau de professionnels par domaine (arborescence) + prise de rendez-vous.
- **Assistant IA** : chat maquette + résumé proactif du copilote.

## Chantiers naturels pour la suite

1. Brancher des données réelles (agrégateur bancaire, OCR, PDP/e-facture) derrière `data/demo.js`.
2. Externaliser les styles inline restants vers `tokens.css` (classes utilitaires) au fil des écrans.
3. Extraire un vrai moteur de règles/veille (aujourd'hui les alertes sont des données de démo).
4. Accessibilité : focus clavier visible, `aria-*` sur nav et arborescences, `prefers-reduced-motion`.
5. Tests (Vitest + React Testing Library) sur `Treasury` (zoom/sélection) et `Onboarding`.
6. Le cas échéant : migration Next.js (voir §7.1 ci-dessus).

## Rappels de conformité (non négociables — cf. business plan §3)

- Séparation stricte **SAS (SaaS) / SPE (actes réglementés)** ; aucun libellé UI ne doit promettre
  un conseil juridique/fiscal autonome ni une tenue de comptabilité pour autrui.
- **Aucune commission** sur les honoraires d'avocat / EC / CAC dans les parcours.
- L'IA produit du **préparatoire, sourcé, à valider** ; escalade humaine obligatoire au-delà d'un seuil.
