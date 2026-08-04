# Infrastructure et accès — RAF360

> Inventaire exigé par la phase R6 et §23.3. Objectif : que personne ne dépende d'une
> mémoire individuelle pour retrouver un domaine, un accès ou une échéance.
>
> **Règle absolue** : ce fichier ne contient **aucune valeur de secret** — seulement le
> **nom** des variables et l'endroit où le secret est rangé (§2.1, §14.5).
>
> Dernière revue : **2026-07-30**. À relire à chaque changement d'hébergeur, de domaine
> ou de prestataire.

---

## 1. Code source

| Élément | Valeur |
|---|---|
| Dépôt | `https://github.com/MomoL3M/RAF-360` — **privé** |
| Branche de production | `main` |
| Baseline archivée | branche `baseline-nextjs-gelee` + tag `avant-mise-en-conformite` (ancien site Next.js, gelé, **jamais supprimé**) |
| Propriétaire du compte | `MomoL3M` (Mohamed Diagne) |
| Autres accès | `TODO-PM` — aucun collaborateur ajouté à ce jour |
| Protection de branche | ⛔ **non activée** — voir la marche à suivre ci-dessous (§2.4) |
| Intégration continue | GitHub Actions, `.github/workflows/ci.yaml` |

**Copie locale de travail** : `C:\Users\Mohamed\Documents\DAF 360\raf360-symfony`
(la baseline gelée est dans le dossier frère `RAF 360 WEBSITE`).

### Protéger `main` — à faire par le chef de projet

Aujourd'hui, la règle « jamais de push direct sur `main` » (§2.4) tient par discipline
seule : rien n'empêche techniquement de contourner la CI. Sur GitHub, dans
*Settings → Branches → Add branch ruleset* (ou *Add rule*) pour `main` :

1. **Require a pull request before merging** — interdit le push direct.
2. **Require status checks to pass** → sélectionner le contrôle **`Qualité & tests`**.
   C'est ce point qui fait de la CI une vraie barrière : sans lui, elle informe sans bloquer.
3. **Require branches to be up to date before merging** — évite qu'une fusion casse `main`
   avec du code jamais testé ensemble.
4. Laisser **Allow force pushes** et **Allow deletions** désactivés.

⚠️ Ne pas cocher « Require approvals » tant que le dépôt n'a qu'un seul contributeur :
la règle rendrait toute fusion impossible.

---

## 2. Environnements

| Environnement | État | Détail |
|---|---|---|
| Développement | ✅ opérationnel | Docker Compose : FrankenPHP (`1-php8.4`) + PostgreSQL 16 + Mailpit. `docker compose up -d --wait` puis `https://localhost` (certificat auto-signé) |
| Préproduction | ⛔ **inexistante** | Bloque la recette performance, accessibilité et visuelle (voir `recette-r5.md`). Doit être **interdite à l'indexation** (§9.1) et **sans données personnelles réelles** (§17.1) |
| Production | ⛔ **inexistante** | Dépend du choix d'hébergeur (`TODO-PM`) |

**L'ancien site n'a jamais été déployé** : aucune exposition publique, donc **aucun service
à éteindre** et aucune redirection 301 à préserver à la bascule.

---

## 3. Domaine, DNS, certificats — `TODO-PM`

| Élément | À renseigner |
|---|---|
| Domaine | `raf360.fr` est **présumé** dans la configuration (`DEFAULT_URI`, `robots.txt`, `sitemap.xml`, adresses e-mail) mais **sa réservation n'est pas confirmée** |
| Registrar | `TODO-PM` — nom, compte, date d'expiration, renouvellement automatique |
| Zone DNS | `TODO-PM` — hébergeur de la zone, accès |
| Enregistrements e-mail | `TODO-PM` — **SPF, DKIM, DMARC** obligatoires (§13.3), sinon les confirmations partent en spam |
| Certificat TLS | `TODO-PM` — Let's Encrypt ou équivalent, **renouvellement automatique** + alerte avant expiration (§23.3) |
| Domaine canonique | Décider **avec ou sans `www`** une fois pour toutes, l'autre redirige en 301 (§9.1) |

⚠️ Tant que le domaine n'est pas confirmé, toutes les URL absolues du site (sitemap,
canonical, `og:url`, liens des e-mails) reposent sur une hypothèse.

---

## 4. Hébergement et base de données — `TODO-PM`

| Élément | Exigence du standard | État |
|---|---|---|
| Runtime | PHP 8.4 (PHP-FPM + nginx, ou FrankenPHP) — **pas un hébergement statique** (§2.8, §23.3) | à choisir |
| Base | PostgreSQL 16, accès au **moindre privilège** (l'application n'est pas superutilisateur) | à choisir |
| Localisation | **Union européenne** (§17.1) — seule mention validée par la fiche projet | à confirmer |
| Sauvegardes | Quotidiennes, chiffrées, **copiées hors site**, restauration testée (§22) | scripts prêts (`bin/sauvegarde-base.sh`), planification à créer |
| Tâches planifiées | `bin/sauvegarde-base.sh` et `app:purger-donnees`, **supervisées** | à créer |
| Surveillance uptime | Sonde externe sur `/health`, 24 h/24 (§21) | à créer |
| CDN / WAF | Selon criticité (§22) | non tranché |

---

## 5. Variables d'environnement (noms uniquement)

Valeurs par défaut non sensibles dans `.env` (versionné). **Valeurs réelles** : `.env.local`
(jamais commité, cf. `.gitignore`) en local, **coffre de l'hébergeur** en production.

| Variable | Rôle | État |
|---|---|---|
| `APP_ENV`, `APP_SECRET` | Environnement et clé applicative | `APP_SECRET` **à générer en production** (vide dans `.env`) |
| `DATABASE_URL` | Connexion PostgreSQL | placeholder `!ChangeMe!` en dev |
| `MAILER_DSN` | Service d'e-mail transactionnel | `null://null` par défaut ; Mailpit en dev |
| `MAILER_FROM_ADDRESS`, `MAILER_FROM_NAME`, `LEAD_NOTIFICATION_EMAIL` | Expéditeur et destinataire des leads | adresses **présumées** `@raf360.fr` → `TODO-PM` |
| `SENTRY_DSN` | Suivi d'erreurs (§21) | vide = désactivé → `TODO-PM` (projet UE) |
| `ANALYTICS_ENDPOINT` | Mesure d'audience (§20) | vide = **aucune mesure, aucune requête** → `TODO-PM` |
| `SAUVEGARDE_PASSPHRASE` | Chiffrement des sauvegardes | **à créer au coffre**, et à conserver **ailleurs que sur le serveur** — perdue, toutes les sauvegardes sont inutilisables |
| `MESSENGER_TRANSPORT_DSN` | File de tâches asynchrones | `doctrine://default` |

---

## 6. Comptes applicatifs

| Type | Création | Règle |
|---|---|---|
| Administrateur | `php bin/console app:create-admin <email>` | **Nominatif** (jamais partagé), **MFA TOTP obligatoire** — l'accès est refusé tant qu'elle n'est pas activée (§16.1) |
| Dirigeant / client | Inscription publique `/inscription` | Mot de passe Argon2id, ≥ 12 caractères |
| Compte de démonstration | `app:demo-user`, `app:demo-data` | **Développement uniquement** — ne doit pas exister en production |

---

## 7. Prestataires (sous-traitants au sens RGPD)

Chaque prestataire retenu doit figurer dans la politique de confidentialité **et** faire
l'objet d'un contrat de sous-traitance (DPA) — cf. `docs/traitements-donnees.md` §3.

| Prestataire | Rôle | État |
|---|---|---|
| Hébergeur | Application + base | `TODO-PM` |
| E-mail transactionnel | Confirmations, notifications | `TODO-PM` |
| Suivi d'erreurs | Diagnostic technique (PII désactivée) | `TODO-PM` |
| Mesure d'audience | Statistiques (sans cookie, UE recommandé) | `TODO-PM` |
| GitHub | Hébergement du code | ✅ en place (compte personnel — envisager une organisation d'entreprise) |

---

## 8. Échéances à surveiller

| Échéance | Fréquence | Responsable |
|---|---|---|
| Renouvellement du domaine | annuel | `TODO-PM` |
| Renouvellement du certificat TLS | automatique, avec alerte | `TODO-PM` |
| Test de restauration d'une sauvegarde | **trimestriel** (`docs/exploitation.md` §3) | `TODO-PM` |
| Revue des mises à jour Dependabot | hebdomadaire | `TODO-PM` |
| Montée de version majeure Symfony / PHP | annuelle, **planifiée** (§23.2) | `TODO-PM` |
| Vérification de citation par les moteurs IA (§9.4) | trimestrielle | `TODO-PM` |
