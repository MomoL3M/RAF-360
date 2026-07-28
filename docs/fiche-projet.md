# Fiche projet — RAF360 (phase R1, standard §1)

> **Renseignée par le chef de projet, à partir du Business Plan et des arbitrages validés.**
> Nom canonique du produit : **RAF360** (le BP historique porte encore « DAF 360 » — à harmoniser).
> Logo : disponible dans le dossier **RAF 360 Website**.
> Convention : un champ encore marqué `TODO-PM` = non fourni → la page/décision correspondante n'est pas construite (standard §1, §2.10, §2.12). Rien n'est inventé.

---

## 1.1 Stratégie et objectifs

- **Type de site** : SaaS avec authentification — **site marketing public indexable** (accueil, cas d'usage, tarifs, ressources e-facture, mentions légales) **+ application connectée `/app`** derrière login (tableau de bord, trésorerie, documents, data room), non indexée. Deux zones nettement séparées.

- **Activité en une phrase** (message d'accueil, compréhensible en 5 s) :
  « **Le poste de pilotage financier, comptable, fiscal, social et juridique de votre entreprise, quel que soit votre secteur : tout est réuni, une IA vous alerte et prépare, un réseau de professionnels habilités peut vous accompagner.** »

- **Objectif prioritaire du site (V1)** : attirer les dirigeants de **TPE et PME, tous secteurs d'activité**, avec un diagnostic gratuit « prêt pour l'échéance » à valeur immédiate, les faire **activer** via un onboarding assisté (dont l'IA s'adapte à leur secteur), et n'obtenir l'abonnement qu'une fois l'usage mensuel installé — la vente assistée PME/ETI servant de second moteur.

- **Conversion n°1 (action principale unique)** : **« Démarrer le diagnostic gratuit »** — aperçu de préparation dès la saisie du **SIREN** (avant création de compte), puis enchaînement onboarding : **SIREN → régime de TVA → site internet + type d'activité** (pour l'adaptation sectorielle de l'IA) → premier livrable. CTA unique, dominant et répété ; le paiement n'intervient qu'**après la valeur démontrée**.

- **Micro-conversions** (hiérarchisées par intention — l'ordre sert au scoring des leads) :
  - *Haute intention (proches du diagnostic)* :
    - Aperçu SIREN sans compte (résultat de préparation instantané) ;
    - Inscription à un webinaire **par secteur d'activité**.
  - *Intention moyenne (nurturing)* :
    - Téléchargement d'une checklist (« Prêt pour l'e-facture » / « Trésorerie à 13 semaines ») ;
    - Inscription à la veille e-facturation (newsletter réglementaire ciblée).
  - *Contact direct* :
    - Demande de rappel / contact (à router : TPE → self-serve, PME/ETI → vente assistée).
  - *Règle* : chaque micro-conversion doit avoir un chemin de retour explicite vers la conversion n°1. « Demander une démo » **n'est pas** une micro-conversion : c'est la conversion principale du funnel PME/ETI.

- **Positionnement différenciant (avantages VÉRIFIABLES)** :
  1. **Couche transverse, pas un silo de plus.** Se connecte à vos outils existants (banque, facturation, paie) sans migration, et produit un premier livrable dès la connexion : cockpit cash 13 semaines + calendrier d'échéances. *Preuve : time-to-value mesuré < 1 h après import/connecteur.*
  2. **IA à garde-fous, préparatoire par conception.** Chaque recommandation affiche sa source officielle, sa date et un score de confiance ; au-delà d'un seuil, l'escalade vers un professionnel est obligatoire et journalisée. *Preuve : journal d'audit exportable + sources datées issues du répertoire officiel (Légifrance, DGFiP, BOFiP, URSSAF/BOSS, INSEE…).*
  3. **IA adaptée à votre secteur.** Après le SIREN et le régime de TVA, l'onboarding demande le site internet et le type d'activité, puis ajuste modules, obligations, échéances et recommandations au secteur réel de l'entreprise (commerce, services, industrie, BTP, SaaS…). *Preuve : parcours d'onboarding sectorisé + matrice d'obligations paramétrée par secteur.*
  4. **Architecture SAS / SPE et zéro commission sur honoraires.** Le logiciel (SAS) et les actes réglementés (SPE, à activer) sont strictement séparés ; le professionnel facture directement son client, sans commission de plateforme. *Preuve : CGU + convention de services à prix de marché — argument de conformité déontologique, pas un slogan.*
  5. **Data room maîtrisée.** Partage document par document, à durée limitée, révocable, filigrané et tracé. *Preuve : fonctionnalités d'expiration/révocation + historique des accès.*
  - ⚠ **À ne pas publier sans preuve** : ne pas revendiquer un « réseau de professionnels » opérationnel tant que la SPE n'est pas activée (formuler au conditionnel). **Ne jamais écrire « plateforme agréée / PDP »** → employer la formulation validée : **« Votre outil de gestion compatible facturation électronique »** (Solution Compatible connectée à une plateforme agréée partenaire).

- **Indicateurs de succès + cibles** (hypothèses BP §10.3, à recalibrer après pilote) :
  - **North-star** : usage mensuel actif (WAU/MAU + nombre de tâches/échéances clôturées par compte).
  - *Acquisition / site* : aperçu SIREN → création de compte ; compte → diagnostic réalisé ; puis diagnostic → onboarding assisté → abonnement (funnel en deux marches). Core Web Vitals « Good » ; SEO sur e-facture / trésorerie / obligations TPE-PME. *Cibles chiffrées à fixer après baseline — ne pas inventer un taux.*
  - *Produit / rétention* : activation > 55 % (3 sources connectées en 7 j) ; time-to-value < 60 min ; payback CAC < 9 mois ; LTV/CAC > 4. **⚠ Churn mensuel < 1,8 % = hypothèse fragile, à surveiller en priorité.**
  - *Qualité & risque* : taux de réponses IA rejetées, escalades professionnelles, incidents d'accès, délai de correction.
  - *Disponibilité* : uptime cible 99,9 % (à confirmer selon SLA hébergeur).

---

## 1.2 Publics et personas

- **Cible principale / secondaires** — SaaS **généraliste**, pas de priorité sectorielle ; l'IA s'adapte au secteur de chaque entreprise.
  - **Principale** :
    - **TPE structurées (1-10 salariés)** et **PME (11-49 salariés)**, **tous secteurs**. Dirigeant polyvalent, peu de temps, compta externalisée, paie souvent fragmentée ; fort besoin de coordination cash / échéances / documents. → **Motion : PLG assisté** (aimant self-serve + onboarding assisté pour franchir l'activation) pour les TPE ; **vente assistée** (démo) pour les PME.
  - **Secondaires** :
    - **ETI / groupes** (multi-entités, cycle long) — opportuniste, hors cœur V1 ; → vente projet.
    - **Micro / indépendants** — volume, PLG pur (seul segment où le self-serve intégral fonctionne).
    - **Experts-comptables** — canal de distribution / collaboration, licence cockpit **sans commission** ; → partenariat.
    - **Avocats & DAF externes** — data room, préparation de dossiers, abonnement pro fixe ; → partenariat.

- **Questions et objections par étape** :
  - *Découverte* : « Encore un logiciel de plus ? » → se branche sur l'existant, aucune migration.
  - *Adéquation métier* : « Est-ce que ça comprend mon activité ? » → l'IA s'adapte à votre secteur (site + type d'activité renseignés à l'onboarding).
  - *Maturité numérique* : « Je n'ai pas le temps / pas à l'aise avec l'informatique. » → aperçu sans compte + onboarding assisté (paramétrage fait avec vous).
  - *Prix* : « Rentable face à ce que je paie déjà à mon comptable ? » ; « 39 / 129 / 349 / 899 € pour quoi exactement ? »
  - *Fiabilité* : « Et si l'IA se trompe ? » → sources datées + validation humaine + escalade.
  - *Sécurité* : « Mes données bancaires, de paie, mes contrats sont-ils protégés ? » → hébergement UE, chiffrement, RGPD, droits par rôle.
  - *Périmètre* : « Ça remplace mon expert-comptable / mon avocat ? » → non, ça prépare et oriente.
  - *Délais* : « Combien de temps pour être opérationnel ? » → SIREN + TVA + secteur, valeur en < 1 h.
  - *Conformité* : « Suis-je prêt pour l'e-facture ? » → diagnostic + calendrier (réception 1er sept. 2026 ; émission TPE-PME 1er sept. 2027).

- **Vocabulaire réel de la cible** (socle généraliste ; l'adaptation sectorielle ajoute le lexique métier) :
  trésorerie, découvert, trou de trésorerie, relances, impayés, délai de paiement, échéance URSSAF / TVA, acompte d'IS, bilan, « mon comptable », factures fournisseurs, DSN, bulletins, prélèvement, devis, acompte.
  *Exemples de lexiques sectoriels gérés par l'IA :* BTP (situation de chantier, marge chantier, retenue de garantie, sous-traitance, autoliquidation TVA) ; commerce (stock, marge par canal, marketplace) ; services (TJM, marge mission) ; SaaS (MRR, churn, runway).

- **Coordonnées réelles** (éditeur — jamais inventées) :
  - Raison sociale : **LINDBERGH FORMATION (SAS)**
  - Siège social : **16 rue de Maillé, 91310 Montlhéry, France**
  - Téléphone : **+33 1 87 66 20 97**
  - Email : `TODO-PM` (non fourni)
  - SIRET : **817 946 114 00029**

---

## 1.3 Socle technique — ✅ déjà acté (lot 1)

- Stack : **Symfony 7.4 fullstack** (Twig + Symfony UX Stimulus/Turbo), PHP **8.4** figée (`.php-version` + `composer.json`), Composer + AssetMapper (sans Node).
- BDD : **PostgreSQL 16** via Docker ; runtime **FrankenPHP**. Schéma Doctrine neuf (aucune donnée existante à migrer).

---

## Grille tarifaire — ✅ figée (HT / mois)

| Offre | Prix HT/mois | Cible |
|---|---|---|
| Starter | 39 € | Micro / indépendant |
| Pilot TPE | 129 € | TPE 1-10 salariés |
| RAF PME | 349 € | PME 11-49 salariés |
| RAF ETI | 899 € | ETI / 50+ / groupe |

> Le palier « 49 € » évoqué antérieurement est **abandonné**. ✅ Cohérence de marque : paliers renommés **RAF PME / RAF ETI** (2026-07-28, ex-« DAF PME / DAF ETI »).

---

## Décisions transverses

1. **Contenus réels** (règle §2.10) :
   - Tarifs : ✅ figés (voir grille ci-dessus).
   - Adossement « sources officielles » : ✅ légitime et **documenté** via le répertoire officiel (25 sources : Légifrance, DGFiP, BOFiP, URSSAF/BOSS, INSEE, INPI, CNIL…) — usage documentaire daté, jamais présenté comme un label.
   - Statut « plateforme agréée PDP » : ❌ **retiré**. Remplacé par **« Votre outil de gestion compatible facturation électronique »** (SC connectée à une PA partenaire). Ne jamais revendiquer « agréé ».
   - Témoignages & chiffres clients réels : `TODO-PM` (ne pas inventer).
2. **Domaine** : `raf360.fr` **disponible** (non encore réservé) → **décision : le réserver sans délai** (+ protéger variantes utiles). ⚠ Le chemin projet mentionne encore « DAF 360 ».
3. **Éditeur / mentions légales** : ✅ fournies (Lindbergh Formation SAS, 16 rue de Maillé, 91310 Montlhéry, tél. +33 1 87 66 20 97, SIRET 817 946 114 00029). Manquent : **email de contact** + **hébergeur (UE)** → `TODO-PM`.
4. **Comptes & rôles** : ✅ vrais comptes utilisateurs. Rôles proposés (à valider, d'après BP §5.1) : dirigeant / admin entreprise ; collaborateurs internes à droits granulaires (DAF, compta interne, RH, associé) ; expert-comptable ; avocat ; admin plateforme. Espace `/app` non indexé. → conditionne le lot « auth & sécurité ».
5. **Édition standard du nouveau dépôt** (`raf360-symfony`) : fournir `CLAUDE_SITE_INTERNET_LF_v3_Symfony.md` (édition « de zéro ») **ou** adopter les sections 1-26 de l'édition reprise → `TODO-PM`.

---

## Journal des modifications (traçabilité)

| Date | Modification | Origine |
|---|---|---|
| 2026-07 | Nom canonique fixé : **RAF360** (ex-« DAF 360 ») | Arbitrage PM |
| 2026-07 | **Abandon de la priorité BTP** → positionnement **généraliste** (TPE/PME tous secteurs, ETI opportuniste) ; ajout de l'**adaptation sectorielle par l'IA** (site + type d'activité à l'onboarding) | Arbitrage PM |
| 2026-07 | Grille tarifaire à 4 paliers figée (39 / 129 / 349 / 899 €) ; palier 49 € supprimé | Arbitrage PM |
| 2026-07 | Mentions légales renseignées (Lindbergh Formation SAS) ; email + hébergeur restants en `TODO-PM` | Données PM |
| 2026-07 | Formulation e-facture : « outil de gestion compatible facturation électronique » ; retrait de « plateforme agréée PDP » | Arbitrage PM |
| 2026-07 | Domaine `raf360.fr` : disponible → à réserver | Vérif PM |
| 2026-07-28 | Paliers renommés **RAF PME / RAF ETI** (ex-« DAF ») | Arbitrage PM |
