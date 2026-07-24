# État des lieux — RAF 360 (phase R0)

> Document produit dans le cadre du protocole de reprise (CLAUDE.md, section 0-R).
> Objectif : décrire l'existant RÉEL avant toute décision de trajectoire (R3).
> Date : 2026-07-23.

## Stack réelle

- **Framework** : Next.js 16.2.11 (App Router, Turbopack).
- **UI** : React 19.2.4, TypeScript 5.
- **Styles** : Tailwind CSS v4 (présent) + une grande feuille de styles maison à base de variables CSS (`src/app/globals.css`) qui porte la charte et les composants.
- **Polices** : `next/font/google` — Fraunces (titres) + Inter (corps).
- **Gestionnaire de paquets** : npm (`package-lock.json`).
- **Serveur de dev** : `next dev -p 3002`.

> ⚠️ Cette stack est **différente de la cible du standard** (Symfony 7.4 / PHP 8.4 / Twig / AssetMapper / Doctrine / PostgreSQL). Voir la décision de trajectoire (R3).

## Structure

```
src/
  app/
    layout.tsx                     # racine (polices, métadonnées)
    globals.css                    # charte + composants (app & marketing)
    (marketing)/                   # site public (header + footer)
      layout.tsx, page.tsx         # accueil
      produit, solutions, tarifs, a-propos, contact, blog, blog/[slug]
    app/                           # application de démonstration (ex-maquette)
      layout.tsx (shell), dashboard, actions, calendar, treasury,
      documents, factures, dataroom, assistant, onboarding
  components/                      # Logo, Icon, ui, AiInsight, Chrome, marketing/*
  data/                            # demo.ts, blog.ts (TOUTES données fictives)
  lib/                             # tokens.ts, format.ts
```

## Pages / routes publiques

- Marketing : `/`, `/produit`, `/solutions`, `/tarifs`, `/a-propos`, `/blog`, `/blog/[slug]` (4 articles).
- `/contact` → redirection 307 vers `/app/onboarding`.
- Application (démo) : `/app/dashboard`, `/app/actions`, `/app/calendar`, `/app/treasury`, `/app/documents`, `/app/factures`, `/app/dataroom`, `/app/assistant`, `/app/onboarding`.

## Base de données

- **Aucune BDD connectée.** Toutes les données sont fictives et codées en dur (`src/data/demo.ts`, `src/data/blog.ts`).
- Supabase est prévu (consigne initiale) mais **non intégré**.

## Authentification

- **Aucune.** L'« onboarding » et la « connexion » sont des maquettes sans back-end.

## Déploiement / hébergement

- Prévu : **Vercel** (consigne initiale). **Non déployé** à ce jour.
- Environnement actuel : développement local uniquement (port 3002).

## Domaine / trafic / campagnes

- **Aucun domaine public, aucun trafic, aucune campagne.** → La bascule serait libre (aucun enjeu de continuité SEO/publicitaire — cf. ADAPTATIONS du standard).

## Git

- **Absent** (choix explicite « pas de git » exprimé en cours de projet). ⚠️ En conflit avec la phase R0 du standard, qui rend git + sauvegarde hors machine OBLIGATOIRES. À arbitrer.

## Baseline visuelle

- Un **export statique figé** du site existe déjà : `C:\Users\Mohamed\Documents\DAF 360\RAF360-BACKUP\site-2026-07-22` (lançable via `LANCER-LE-SITE.bat`). Il sert de première référence visuelle. Captures d'écran plein-écran aux 4 largeurs (375/768/1440/2560) : **à produire** si une reconstruction (chemin B) est décidée.

## Points de vigilance déjà identifiés (pré-audit R2)

- **Contenus invérifiables / fictifs (règle 2.10 — potentiellement BLOQUANT en ligne)** :
  - Témoignages nominatifs sur l'accueil (« Camille Roussel », « Thomas Nguyen », « Sarah Benkacem ») — **inventés** (contenu de démonstration). À supprimer ou remplacer par de vrais avis (`TODO-PM`).
  - Chiffres / statistiques (« 100 % », taux, etc.) présentés comme des faits — à valider ou marquer estimatifs.
  - Tarifs (49 € / 129 € / sur mesure) — indicatifs, à confirmer.
  - Coordonnées (`contact@raf360.fr`, éditeur) — à vérifier.
- **Pages légales absentes** (mentions légales, confidentialité, CGV/CGU) — obligatoires avant toute mise en ligne.
- **Aucune mesure d'audience / RGPD** (bandeau cookies, plan de mesure) — non applicable tant que hors ligne, obligatoire avant mise en ligne.

## Prochaine étape

Décision de trajectoire (phase R3) à acter par le chef de projet — voir la question soumise.
Tant qu'elle n'est pas actée, aucune reconstruction ni changement destructif n'est engagé.
