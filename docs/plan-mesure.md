# Plan de mesure — RAF360

> Obligatoire avant la mise en ligne (§20). Il traduit les objectifs de la fiche projet en
> événements mesurables. Sans lui, on pilote à l'aveugle : on saurait combien de visiteurs
> arrivent, jamais où on les perd.

**Principe non négociable** : aucune donnée personnelle ne part dans la mesure. Ni e-mail,
ni nom, ni SIREN, ni identifiant de compte, et le chemin de page est transmis **sans query
string** — un paramètre d'URL est l'endroit où une adresse e-mail fuite le plus souvent.

---

## 1. Ce que l'on cherche à savoir

| Question de décision | Ce qu'on regarde |
|---|---|
| Le site convertit-il ? | `diagnostic_demande` / visiteurs uniques |
| Où perd-on les visiteurs ? | Entonnoir §3, étape par étape |
| Quelles pages amènent au diagnostic ? | Page de dernière vue avant `diagnostic_demande` |
| D'où viennent les visiteurs qui convertissent ? | Propriété `canal` de `page_vue` |
| Les moteurs de réponse IA nous envoient-ils du monde ? | `canal = moteur_ia` (§9.4) |
| Quel contenu ne sert à rien ? | Pages sans suite : vues sans clic vers une étape suivante |

---

## 2. Conventions de nommage

Un seul format, en français, sans majuscule ni accent : `objet_action`.
Exemples : `diagnostic_demande`, `tarifs_cta_diagnostic`. Un événement ne change **jamais**
de nom une fois en production — un renommage casse toutes les séries historiques.

Propriétés autorisées, et elles seules :

| Propriété | Valeurs | Pourquoi elle est sans risque |
|---|---|---|
| `page` | chemin sans query string | Pas de paramètre, donc pas de donnée personnelle |
| `canal` | `direct`, `interne`, `moteur_ia`, `moteur_recherche`, `referral`, `inconnu` | Classement de la provenance, l'URL de référence complète n'est pas conservée |

---

## 3. Entonnoir de la conversion n°1

Conversion n°1 de la fiche projet : **« Démarrer le diagnostic gratuit »**.

| Étape | Événement | État |
|---|---|---|
| 1. Arrivée sur une page publique | `page_vue` | ✅ en place |
| 2. Clic sur le CTA principal (accueil, haut) | `accueil_cta_diagnostic_haut` | ✅ en place |
| 3. Clic sur le CTA principal (accueil, bas de page) | `accueil_cta_diagnostic_bas` | ✅ en place |
| 4. Clic sur le CTA depuis les tarifs | `tarifs_cta_diagnostic` | ✅ en place |
| 5. Affichage du formulaire de diagnostic | `page_vue` sur `/diagnostic` | ✅ en place |
| 6. **Demande de diagnostic acceptée** | `diagnostic_demande` | ✅ en place |
| 7. Création de compte | `compte_cree` | ✅ en place |
| 8. Onboarding terminé (SIREN → TVA → activité) | `onboarding_termine` | ✅ en place |

### Micro-conversions

| Micro-conversion | Événement | État |
|---|---|---|
| Contact / demande de rappel | `contact_message_envoye` | ✅ en place |
| Clic « Parler à un conseiller » (tarifs) | `tarifs_cta_conseiller` | ✅ en place |
| Aperçu SIREN sans compte | `apercu_siren_affiche` | ⛔ fonction non développée |
| Téléchargement de checklist | `checklist_telechargee` | ⛔ fonction non développée |
| Inscription à la veille e-facturation | `veille_inscription` | ⛔ fonction non développée |

Les lignes ⛔ ne sont pas des oublis : les fonctions correspondantes n'existent pas encore.
Elles figurent ici pour que leur mesure soit posée **en même temps** qu'elles, et pas après.

---

## 4. Comment c'est implémenté

Un seul point d'entrée : `assets/controllers/tracking_controller.js` (§20 — jamais d'appels
de mesure dispersés dans les gabarits).

| Mécanisme | Détail |
|---|---|
| Vue de page | Émise à la connexion du contrôleur, avec le `canal` |
| Clic | Attribut déclaratif : `data-action="click->tracking#evenement"` + `data-tracking-nom-param="…"` |
| Conversion | Annoncée par le **serveur** via un flash `conversion` (voir `LeadController`) |

Pourquoi la conversion vient du serveur : un clic sur « Envoyer » n'est pas une conversion.
Seule une soumission **acceptée** (validée, non spam, non rejetée) en est une. Mesurer le
clic gonflerait artificiellement les chiffres.

### Garde-fous

- **Consentement** : sans cookie `raf360_consent=accepted`, aucun événement ne part.
- **Sans prestataire configuré** (`ANALYTICS_ENDPOINT` vide) : aucune requête réseau n'est
  émise. C'est l'état actuel — le site ne mesure donc rien à ce jour, et ne trace personne.
- **Jamais bloquant** : un échec d'envoi est abandonné silencieusement ; la mesure ne doit
  jamais dégrader la navigation.

---

## 5. Ce qui reste à décider (`TODO-PM`)

1. **Choisir l'outil de mesure.** Recommandation : un outil **sans cookie, hébergé dans
   l'UE** (Plausible ou Matomo auto-hébergé). Avantage direct : une mesure d'audience
   exemptée de consentement se déclenche pour **tous** les visiteurs, alors qu'aujourd'hui
   la mesure attend un « Accepter » — donc mesure une minorité. Moins de friction, moins de
   risque juridique (§17.1).
   → Une fois l'outil choisi, renseigner `ANALYTICS_ENDPOINT` et rouvrir la règle de
   consentement ci-dessus si l'outil est réellement exempté.
   → **Piège à ne pas oublier** : la politique de sécurité du contenu est aujourd'hui
   `default-src 'self'` (`config/packages/nelmio_security.yaml`). Un point de collecte
   sur un autre domaine sera donc **bloqué par le navigateur** tant que son origine n'est
   pas ajoutée en `connect-src`. Un outil auto-hébergé sur le même domaine évite le sujet.
2. **Cibles chiffrées.** Chaque indicateur de la fiche projet doit avoir une cible
   (taux de conversion visé, volume mensuel). Sans cible, aucune alerte n'a de seuil.
3. **Alertes de chute** (§20) : une baisse anormale de `diagnostic_demande` ou du trafic
   doit alerter quelqu'un de nommé. À configurer dans l'outil retenu.
4. **Vérification GEO manuelle** (§9.4) : poser trimestriellement aux assistants IA les
   questions clés de la cible et noter si RAF360 est cité, avec quelles informations.

---

## 6. Recette de la mesure (avant mise en ligne)

À dérouler quand un prestataire sera branché — aucun de ces points ne peut être validé
aujourd'hui, faute d'outil configuré :

- [ ] Refuser les cookies → **aucune** requête vers le point de collecte (à vérifier dans
      l'onglet réseau du navigateur).
- [ ] Accepter → `page_vue` arrive avec le bon `canal`.
- [ ] Envoyer le formulaire de diagnostic → `diagnostic_demande` arrive **une seule fois**.
- [ ] Renvoyer le même formulaire en erreur (champ invalide) → **aucun** événement de
      conversion.
- [ ] Inspecter les charges envoyées : aucune adresse e-mail, aucun SIREN, aucun nom.
- [ ] Vérifier que les URL transmises ne contiennent pas de query string.
