# Registre des conflits graphisme ↔ standard (0-R-V)

> Chaque point où la fidélité au design existant contredit une règle du standard.
> Claude ne tranche pas seul : il propose, applique la « troisième voie » (ajustement
> minimal préservant la fidélité perçue) quand elle existe, et le chef de projet arbitre.

## CG-01 — Ambre vif en TEXTE sur fond clair : contraste sous AA (§10)

- **Date** : 2026-07-29 · **Statut** : résolu par ajustement minimal (à confirmer par le PM)
- **Éléments** :
  - `.gold-em` — mot mis en avant dans le H1 du héros (« enfin sous contrôle »), fond clair du héros.
  - `.step-n` — numéros d'étapes (01–04) de la section « Comment ça marche », fond `band-paper3` (clair).
- **Règle en conflit** : WCAG 2.2 / RGAA niveau AA — contraste texte ≥ 4,5:1 (≥ 3:1 grand texte).
- **Mesures** :
  - `--gold-500` (#eda323) sur blanc ≈ **2,1:1** → échoue même le seuil grand texte (3:1).
  - `--gold-600` (#d98a10) sur `--paper-3` ≈ **2,7:1** → échoue le seuil texte normal (4,5:1).
- **Décision (troisième voie — assombrissement minimal, fidélité perçue conservée)** :
  introduction d'un token **`--gold-ink` (#8a5e00)**, RÉSERVÉ au texte ambre sur fond clair
  (≈ 5,7:1 sur blanc, ≈ 4,9:1 sur paper-3). L'ambre vif `--gold` reste inchangé pour les
  **fonds** de CTA (là où le contraste est déjà bon) : l'identité « accent ambre » est préservée,
  seule la nuance du texte ambre sur clair est assombrie.
- **Arbitrage PM** :
  - *Option A (dérogation iso-graphisme)* : conserver l'ambre vif en texte → **refusée par défaut**
    (l'accessibilité prime sur l'esthétique, priorité §0 ; contenu illisible pour malvoyants/daltoniens).
  - *Option B (conformité, appliquée)* : `--gold-ink` sur ces textes. **Réversible** en repointant sur
    `--gold-500/600` si le PM assume la dérogation.
  - 👉 **Le chef de projet confirme ou infirme l'option B.**

## Points de contraste à revérifier (recette axe/Lighthouse — bloquée en local par le certificat auto-signé)

- `--muted` (#64708f) sur fond teinté `--paper-3` ≈ **4,3:1** : limite pour du texte normal (< 4,5).
  À trancher lors de la recette outillée (agrandir le texte, l'assombrir, ou restreindre l'usage aux
  grands textes / éléments non textuels).
- Balayage complet de toutes les combinaisons texte/fond + test manuel clavier & lecteur d'écran :
  **recette humaine restant à faire** (voir ADAPTATIONS — accessibilité).
