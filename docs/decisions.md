# Journal des décisions — RAF 360

## 2026-07-28 — Fiche projet R1 appliquée (arbitrages & garde-fous)

Fiche R1 fournie par le chef de projet ([fiche-projet.md](fiche-projet.md)) et intégrée.

- **Produit** : nom canonique **RAF360** (ex-« DAF 360 »). Éditeur **Lindbergh Formation SAS**, 16 rue de Maillé, 91310 Montlhéry, +33 1 87 66 20 97, SIRET 817 946 114 00029.
- **Positionnement** : SaaS **généraliste TPE/PME tous secteurs** (priorité BTP **abandonnée**) ; IA adaptée au secteur (site + type d'activité à l'onboarding). Site marketing public indexable + app `/app` non indexée.
- **Conversion n°1** : « Démarrer le diagnostic gratuit » (aperçu SIREN sans compte → onboarding SIREN → TVA → site+activité → 1er livrable ; paiement après valeur démontrée).
- **Tarifs figés (HT/mois)** : Starter 39 / Pilot TPE 129 / **RAF PME** 349 / **RAF ETI** 899 (palier 49 € abandonné ; paliers renommés « RAF » le 2026-07-28, ex-« DAF »).
- **Comptes & rôles** (vrais comptes) : dirigeant/admin entreprise ; collaborateurs à droits granulaires (DAF, compta, RH, associé) ; expert-comptable ; avocat ; admin plateforme → débloque le lot auth.
- **Domaine** : `raf360.fr` disponible → à réserver sans délai.

**Garde-fous de contenu NON négociables (règle 2.10 / §17.2), valables sur TOUTE page :** jamais « plateforme agréée / PDP / agréé » (dire « outil de gestion compatible facturation électronique ») ; « réseau de professionnels » au conditionnel tant que la SPE n'est pas activée ; « sources officielles » = usage documentaire daté, pas un label ; témoignages/chiffres clients = `TODO-PM`, jamais inventés.

**En attente** : paliers renommés **RAF PME / RAF ETI** ✅ (2026-07-28) ; reste le dossier projet nommé « DAF 360 » ; `TODO-PM` email de contact + hébergeur UE. **Édition standard du nouveau dépôt** : résolu — édition reprise adoptée comme `CLAUDE.md` de `raf360-symfony`.

## 2026-07-27 — Démarrage R4-B, lot 1 (socle + Docker)

- Docker installé → dernier blocage d'outillage levé. Lot 1 exécuté : nouveau dépôt **`../raf360-symfony`** (frère du Next.js gelé), Symfony 7.4 webapp + environnement Docker **FrankenPHP (PHP 8.4)** + PostgreSQL 16 + Mailpit. Stack vérifiée (conteneurs *healthy*, accueil Symfony en HTTPS, Doctrine↔Postgres OK).
- **Décisions de mise en œuvre** : (1) dépôt Symfony dans un dossier **frère** et non un sous-dossier ; (2) runtime **FrankenPHP** (mono-conteneur, worker natif 7.4) plutôt que PHP-FPM/nginx ; (3) image **épinglée PHP 8.4** (template amont en 8.5) pour tenir la cible du standard.
- **En attente (chef de projet)** : dépôt du **fichier standard propre** (LINDBERGH v3.1-R — la copie en session avait des accents corrompus) et **fiche projet R1** + `CLAUDE.md` racine du nouveau dépôt, avant de cadrer les lots suivants.

## 2026-07-23 — Trajectoire de reprise (phase R3)

- **Décision : Chemin B — reconstruction en Symfony 7.4** (validée par le chef de projet).
- Le site existant (Next.js / React / TypeScript) est **GELÉ** : il devient la **source/spécification** et la **baseline visuelle** de la reconstruction. Aucune évolution fonctionnelle dessus (seuls d'éventuels correctifs de sécurité critiques, à noter dans `docs/modifs-pendant-migration.md`).
- La reconstruction se fera dans un **nouveau dépôt Symfony 7.4** conforme au standard (CLAUDE.md), par lots (phase R4-B).
- Conséquences assumées : abandon de l'hébergement Vercel et de Supabase au profit d'un runtime PHP 8.4 (PHP-FPM/nginx ou FrankenPHP) + PostgreSQL + Docker (section 2.8 / 23.3 du standard).

## 2026-07-23 — Git & sauvegarde (phase R0)

- **Décision : git activé.** Dépôt initialisé, commit de référence + tag `avant-mise-en-conformite`.
- Reste à faire (action chef de projet) : dépôt distant privé (compte entreprise) + copie hors machine.

## 2026-07-23 — Blocage outillage (à lever avant R4-B)

- PHP 8.4, Composer, Symfony CLI et Docker sont **absents** de la machine. La reconstruction Symfony ne peut pas démarrer tant qu'ils ne sont pas installés. R1 (fiche projet) et R2 (audit) peuvent avancer sans eux.
