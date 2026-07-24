# Journal des décisions — RAF 360

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
