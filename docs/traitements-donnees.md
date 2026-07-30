# Traitements de données personnelles — RAF360

> Base du registre des traitements tenu par le responsable de traitement (§17.1).
> Ce tableau est la SOURCE de la politique de confidentialité : toute évolution du code
> qui collecte une nouvelle donnée doit d'abord apparaître ici.

- **Responsable de traitement** : Lindbergh Formation SAS — SIRET 817 946 114 00029
- **Éditeur du service** : RAF360
- **Délégué à la protection des données (DPO)** : `TODO-PM` — désigné ou non (obligatoire seulement dans certains cas)
- **Contact pour l'exercice des droits** : `TODO-PM` — adresse e-mail dédiée à fournir
- **Dernière revue** : 2026-07-30

---

## 1. Tableau des traitements

| # | Donnée | Finalité | Base légale | Durée de conservation | Appliquée par | Destinataires |
|---|---|---|---|---|---|---|
| T1 | E-mail, nom, prénom | Créer et identifier le compte | Exécution du contrat | Durée du compte, puis anonymisation après **36 mois sans connexion** | `PurgeDonnees` (automatique) | Éditeur, hébergeur |
| T2 | Mot de passe (haché Argon2id) | Authentifier l'utilisateur | Exécution du contrat | Idem T1 — neutralisé à l'anonymisation | `PurgeDonnees` | Éditeur, hébergeur |
| T3 | Secret TOTP (2FA) | Sécuriser l'accès | Obligation de sécurité (art. 32) | Idem T1 — effacé à l'anonymisation | `PurgeDonnees` | Éditeur, hébergeur |
| T4 | Dates de création et de dernière connexion | Appliquer les durées de conservation, sécurité | Intérêt légitime | Idem T1 | `PurgeDonnees` | Éditeur, hébergeur |
| T5 | Raison sociale, SIREN, régime TVA, secteur, site web | Paramétrer le service pour l'entreprise | Exécution du contrat | Durée du contrat | Suppression de compte (dernier compte) | Éditeur, hébergeur |
| T6 | Données de gestion : factures, échéances, documents, trésorerie, encaissements | Fournir la fonction de pilotage | Exécution du contrat | Durée du contrat ; suppression à la demande | `SuppressionCompte` | Éditeur, hébergeur |
| T7 | Rendez-vous avec un professionnel | Mise en relation demandée par l'utilisateur | Exécution du contrat | Durée du contrat | `SuppressionCompte` | Éditeur, professionnel concerné |
| T8 | Formulaire de contact : nom, e-mail, société, téléphone, message | Répondre à la demande | Intérêt légitime (relation commerciale) | **Non stocké dans le site** — transmis par e-mail ; durée = messagerie de l'éditeur → `TODO-PM` | Éditeur (hors application) | Éditeur, service d'e-mail |
| T9 | Formulaire de diagnostic : SIREN, e-mail, site web, type d'activité | Préparer le diagnostic demandé | Consentement (demande volontaire) | Idem T8 → `TODO-PM` | Éditeur (hors application) | Éditeur, service d'e-mail |
| T10 | Adresse IP (limitation de débit anti-spam) | Sécurité des formulaires | Intérêt légitime | Fenêtre glissante courte, en cache — non journalisée | Composant RateLimiter | Éditeur |
| T11 | Journaux techniques (empreinte non réversible de l'e-mail, identifiants internes) | Diagnostic d'incident, traçabilité | Intérêt légitime | Selon la rétention du collecteur de journaux → `TODO-PM` (hébergeur) | Plateforme d'hébergement | Éditeur, hébergeur |
| T12 | Choix de consentement aux cookies | Preuve du choix | Obligation légale | 6 mois (cookie `raf360_consent`) | Navigateur | Aucun |

### Ce que le site ne collecte PAS

Aucune donnée bancaire (pas de paiement en ligne à ce stade), aucune donnée sensible au
sens de l'art. 9 (santé, opinions, origine), aucun profilage, aucune décision automatisée.
Aucune donnée personnelle n'est envoyée à un outil de mesure d'audience (cf. `plan-mesure.md`).

---

## 2. Comment les durées sont réellement appliquées

Une durée annoncée mais jamais exécutée n'est pas une conformité. Mécanismes en place :

| Mécanisme | Commande / code | Déclenchement |
|---|---|---|
| Anonymisation des comptes inactifs (36 mois) | `php bin/console app:purger-donnees` → `App\Service\PurgeDonnees` | Tâche planifiée quotidienne — **à créer chez l'hébergeur** (`TODO-PM`) |
| Effacement à la demande de la personne | `App\Service\SuppressionCompte`, page « Mes données » | Immédiat, par l'utilisateur |
| Export des données (accès + portabilité) | `App\Service\ExportDonneesPersonnelles` | Immédiat, par l'utilisateur |

Vérification sans risque avant exécution :

```bash
php bin/console app:purger-donnees --simulation
```

Les comptes portant `ROLE_ADMIN` sont exclus de l'anonymisation automatique : les purger
fermerait l'accès à la plateforme sans que personne ne l'ait demandé.

---

## 3. Sous-traitants

| Sous-traitant | Rôle | Données | Localisation | Contrat (DPA) |
|---|---|---|---|---|
| Hébergeur | Hébergement applicatif et base de données | T1 → T12 | Union européenne (exigence) | `TODO-PM` |
| Service d'e-mail transactionnel | Envoi des confirmations et notifications | T1, T8, T9 | `TODO-PM` (UE exigée) | `TODO-PM` |
| Suivi d'erreurs (Sentry ou équivalent) | Diagnostic technique | T11 (PII désactivée : `send_default_pii: false`) | `TODO-PM` | `TODO-PM` |

Aucun de ces contrats n'est signé à ce jour : ils conditionnent la mise en ligne
(cf. `recette-r5.md`, bloquants côté chef de projet).

---

## 4. Droits des personnes — où ils s'exercent

| Droit | Moyen | État |
|---|---|---|
| Accès et portabilité (art. 15, 20) | Page « Mes données » → export JSON | ✅ implémenté |
| Effacement (art. 17) | Page « Mes données » → suppression du compte (mot de passe + confirmation) | ✅ implémenté |
| Rectification (art. 16) | Par demande à l'éditeur | ⚠️ pas d'écran d'édition du profil à ce jour |
| Opposition et limitation (art. 18, 21) | Par demande à l'éditeur | ⚠️ dépend de l'adresse de contact (`TODO-PM`) |
| Retrait du consentement cookies | Lien permanent en pied de page | ✅ implémenté |
| Réclamation | CNIL | ✅ mentionné dans la politique |

---

## 5. Sécurité et incidents

- Mots de passe hachés en **Argon2id** (libsodium), jamais réversibles.
- Cloisonnement par entreprise vérifié à chaque accès aux données métier (§16.1).
- Double authentification obligatoire pour les comptes d'administration.
- Journaux : **aucune donnée personnelle en clair** — l'e-mail d'un prospect n'apparaît que
  sous forme d'empreinte SHA-256 tronquée, non réversible.
- Procédure de violation de données (notification CNIL sous 72 h) : `TODO-PM` — à écrire
  avec l'éditeur avant la mise en ligne.
