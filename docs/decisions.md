# Journal des décisions techniques — RAF360

> Les arbitrages structurants sont consignés ici (§2.7). Une décision non écrite se
> redécouvre et se rediscute à chaque fois.

## 2026-07-30 — Comptes inactifs : anonymisation plutôt que suppression

**Contexte.** Le RGPD impose une durée de conservation limitée. La recommandation CNIL
usuelle pour un compte client est de 3 ans sans activité.

**Options.** (A) supprimer le compte et les données de l'entreprise ; (B) anonymiser le
compte et conserver les données de l'entreprise.

**Décision : B.** L'anonymisation rend la personne non identifiable — la finalité RGPD est
atteinte — sans casser les références de la base. Surtout, les données de gestion
appartiennent à l'ENTREPRISE, pas à la personne : une inactivité de 36 mois ne prouve pas
la fin du contrat. Effacer automatiquement une comptabilité complète sur ce seul critère
serait destructeur et irréversible.

**Conséquence.** La suppression des données d'entreprise reste déclenchée par deux
événements explicites : la demande de la personne (droit à l'effacement, si elle est le
dernier compte) ou la fin du contrat (action de l'éditeur). À inscrire dans les CGV.

## 2026-07-30 — Périmètre « entreprise » défini une seule fois

`EffacementDonneesEntreprise` est le seul endroit qui énumère les entités rattachées à une
entreprise. La suppression de compte RGPD et le peuplement de démonstration l'utilisent
tous les deux. Motif : une nouvelle entité oubliée dans une des deux listes créerait une
fuite de données silencieuse à la suppression.

## 2026-07-30 — Journaux : empreinte au lieu de l'e-mail

Les journaux de réception de leads contenaient l'e-mail en clair (§2.6 / §17.1 violés).
Remplacé par une empreinte SHA-256 tronquée à 10 caractères : le support peut relier une
demande à un envoi, sans qu'aucune donnée personnelle ne soit écrite dans les journaux.

## 2026-07-30 — Sessions en fichiers : limite assumée à une seule instance

Les sessions utilisent le stockage fichier par défaut de PHP. C'est incompatible avec
plusieurs instances derrière un répartiteur de charge (§22), où un utilisateur perdrait sa
session au changement d'instance.

**Décision.** Acceptable tant que le service tourne sur **une seule instance**. Le passage
à plusieurs instances exige au préalable un stockage de session partagé (Redis ou base).
Point à rouvrir au moment du choix d'hébergeur — voir `exploitation.md`.
