<!--
  Gabarit de fusion — standard LINDBERGH FORMATION (§2.4, §24, §25).
  La CI vérifie la mécanique (style, analyse statique, tests, fumée, audits).
  Ce qui suit vérifie ce que la CI NE PEUT PAS voir : l'honnêteté du contenu,
  l'accessibilité réelle, la fidélité visuelle et les conséquences RGPD.
  Rayez ce qui ne s'applique pas — mais ne cochez rien que vous n'avez pas vérifié.
-->

## Ce que fait cette fusion

<!-- Une phrase, côté utilisateur : ce qui change pour lui. -->

## Portes automatiques

- [ ] CI verte (CS-Fixer, PHPStan, `lint:twig`, migrations + `schema:validate`, PHPUnit, test de fumée, `composer audit`, `importmap:audit`)

## Contenu et honnêteté (§2.10)

- [ ] Aucun témoignage, chiffre, avis, partenaire ou certification **inventé**
- [ ] Aucun `TODO-PM` ni texte de remplissage laissé dans une page publique
- [ ] Rien qui laisse croire à un agrément inexistant (jamais « plateforme agréée / PDP » ; réseau de professionnels **au conditionnel**)
- [ ] Prix, dates et conditions exacts, et transparents **avant** tout engagement

## Sécurité et données (§2.1, §16, §17)

- [ ] Aucun secret ajouté au dépôt ; toute nouvelle variable est documentée dans `docs/infrastructure.md` (nom seulement)
- [ ] Toute entrée externe validée côté serveur (DTO + contraintes)
- [ ] Route sensible : rôle **et** appartenance des données vérifiés côté serveur
- [ ] Aucune donnée personnelle dans les journaux ni dans la mesure d'audience
- [ ] Nouvelle donnée personnelle collectée → ligne ajoutée à `docs/traitements-donnees.md` (finalité, base légale, durée) **et** durée réellement appliquée

## Structure (§14.1, §2.2, §2.3)

- [ ] Contrôleurs fins ; logique métier en service ; **DQL/QueryBuilder uniquement** dans `src/Repository/`
- [ ] `declare(strict_types=1)` ; aucun `mixed` échappatoire ; aucune sortie de debug
- [ ] Plafonds de taille respectés, ou dérogation déclarée dans ADAPTATIONS

## Interface (§4.4, §7, §10)

- [ ] États **chargement / erreur / vide / succès** traités pour toute vue de données
- [ ] Parcours complet au clavier, focus visible, libellés de champs associés
- [ ] Testé à 375 / 768 / 1440 px : aucun défilement horizontal, rien de coupé
- [ ] Contraste AA vérifié pour toute nouvelle combinaison de couleurs

## Fidélité visuelle (iso-graphisme, registre 0-R-V)

- [ ] Aucun changement de graphisme ou de comportement **non demandé**
- [ ] Si le standard imposait un changement visible → inscrit dans `docs/conflits-graphisme.md`, **jamais corrigé en silence**

## Documentation

- [ ] `docs/journal-lots.md` complété si le lot est structurant
- [ ] ADAPTATIONS mis à jour : ligne **supprimée** si l'écart est résorbé, ajoutée si une dérogation est assumée

---

**Avant une mise en production**, la checklist complète du §25 s'applique en plus de
celle-ci, et les points non vérifiables en local (Lighthouse, lecteur d'écran, parité
visuelle) doivent être passés sur préproduction.
