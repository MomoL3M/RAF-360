# Audit de conformité & suivi de reprise — RAF 360

> Piloté par le standard LINDBERGH FORMATION (CLAUDE.md, protocole 0-R).

## Suivi de phase

| Phase | Objet | État |
|-------|-------|------|
| R0 | Sécuriser l'existant (git, sauvegarde, baseline, état des lieux) | **En cours** — git ✅, tag ✅, [etat-des-lieux.md](etat-des-lieux.md) ✅ ; sauvegarde distante ⬜, captures baseline 4 largeurs ⬜ |
| R1 | Fondations du contrat (fiche projet §1, CLAUDE.md propre à la racine) | ⬜ à faire (avec le chef de projet) |
| R2 | Audit d'écarts + inventaire de transfert (aucune modification) | ⬜ prêt à démarrer (ne nécessite aucun outillage) |
| R3 | Trajectoire | ✅ **Chemin B — reconstruction Symfony 7.4** (validé 2026-07-23) |
| R4-B | Reconstruction Symfony par lots | ⛔ bloqué : outillage absent (voir ci-dessous) |
| R5 | Recette & bascule | — |
| R6 | Fermeture (archivage de l'existant) | — |

## Blocage à lever avant R4-B — outillage

La machine ne dispose que de `git` et `node`. Il manque, pour le Chemin B :

- **PHP 8.4** (CLI + extensions courantes : intl, pdo_pgsql, mbstring, opcache…)
- **Composer** (gestionnaire de dépendances PHP)
- **Symfony CLI** (binaire `symfony`)
- **Docker Desktop** (+ `docker compose`) — pour PostgreSQL et l'exécution conteneurisée exigée par le standard (§2.8)

Tant que ces outils ne sont pas installés, la reconstruction Symfony ne peut pas commencer. R1 et R2 avancent sans eux.

## R2 — Audit d'écarts (à compléter)

_À renseigner lors de la phase R2 : pour chaque section du standard, l'écart entre l'existant et la cible, la gravité (BLOQUANT / MAJEUR / MINEUR) et l'effort. Vérifications prioritaires : secrets dans le code, entrées non validées, données personnelles exposées, pages légales absentes, contenus de réassurance invérifiables._

### Points déjà repérés (pré-audit, cf. etat-des-lieux.md)

- **Contenus fictifs (règle 2.10)** : témoignages nominatifs, statistiques, tarifs, coordonnées → à valider ou retirer (`TODO-PM`). BLOQUANT pour toute mise en ligne.
- **Pages légales absentes** (mentions légales, confidentialité, CGV/CGU).
- **Aucune mesure d'audience / dispositif RGPD** (à intégrer à la reconstruction).

## R2 — Inventaire de transfert (à compléter)

_À renseigner : URLs (table de correspondance + 301 si domaine public — ici aucun), règles métier à re-exprimer, tokens de design extraits à l'exact du CSS existant, table de correspondance des éléments dynamiques → implémentation cible (Stimulus/Turbo), contenus VALIDÉS uniquement._
