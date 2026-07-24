import { C } from "../lib/tokens.js";

export const NAV = [
  { id: "dash", label: "Tableau de bord" },
  { id: "actions", label: "Centre d'actions" },
  { id: "calendar", label: "Calendrier" },
  { id: "cash", label: "Trésorerie" },
  { id: "docs", label: "Documents" },
  { id: "factures", label: "Factures" },
  { id: "dataroom", label: "Data room" },
  { id: "assistant", label: "Assistant IA" },
];

export const statutColor = {
  "À faire": C.slate, "À valider": C.gold, "En retard": C.red,
  risque: C.red, confirmer: C.gold, escalade: C.navy2, attente: C.slate,
};
export const statutLabel = {
  risque: "Risque élevé", confirmer: "À confirmer", escalade: "Escalade avocat", attente: "En attente",
};

// échéances triées de la plus proche à la plus lointaine
export const ECHEANCES = [
  { t: "TVA (CA3) — Décembre", iso: "2026-01-24", statut: "En retard", prio: "Haute", montant: "3 420 €", mt: "réel" },
  { t: "Déclaration TVA (CA3) — Janvier", iso: "2026-02-12", statut: "À faire", prio: "Haute", montant: "3 980 €", mt: "estimatif" },
  { t: "Renouvellement bail commercial", iso: "2026-02-20", statut: "À valider", prio: "Moyenne" },
  { t: "1er acompte d'IS", iso: "2026-03-15", statut: "À faire", prio: "Moyenne", montant: "4 250 €", mt: "estimatif" },
  { t: "DSN mensuelle — Février", iso: "2026-03-15", statut: "À faire", prio: "Haute" },
  { t: "Approbation des comptes annuels", iso: "2026-06-30", statut: "À faire", prio: "Basse" },
].sort((a, b) => a.iso.localeCompare(b.iso));

export const ACTIONS = [
  { t: "3 pièces manquantes pour la clôture TVA", statut: "risque", who: "Vous" },
  { t: "Facture fournisseur ACME en doublon détecté", statut: "confirmer", who: "Compta" },
  { t: "Contrat client BÉTA : clause de responsabilité à revoir", statut: "escalade", who: "Avocat" },
  { t: "Rapprochement bancaire de janvier à valider", statut: "attente", who: "DAF" },
];

// trésorerie : 12 mois, indices 0..realIdx = réalisé (fonctionnel), le reste = prévisionnel
export const CASH = {
  lab: ["Mar", "Avr", "Mai", "Jun", "Jul", "Aoû", "Sep", "Oct", "Nov", "Déc", "Jan", "Fév"],
  yr: ["25", "25", "25", "25", "25", "25", "25", "25", "25", "25", "26", "26"],
  ent: [48, 52, 60, 55, 58, 62, 70, 66, 72, 68, 74, 80],
  sor: [42, 45, 50, 48, 52, 49, 55, 53, 58, 90, 95, 92],
  sol: [46, 53, 63, 70, 76, 89, 104, 117, 131, 109, 88, 76],
  realIdx: 8,
};

export const CASH_ALERTS = [
  { c: "Client ACME", m: "12 000 €", mode: "Virement", d: "20 févr. 2026", st: "attendu" },
  { c: "Client GAMMA", m: "6 480 €", mode: "Prélèvement SEPA", d: "28 févr. 2026", st: "attendu" },
  { c: "Client BÉTA", m: "9 200 €", mode: "Chèque", d: "05 févr. 2026", st: "en retard" },
];

export const DOC_TREE = {
  corp: {
    label: "Documents corporate", icon: "🏛️", files: [
      { n: "Statuts_société.pdf", type: "Statuts", conf: 99, date: "12 janv." },
      { n: "PV_AG_2024.pdf", type: "PV d'AG", conf: 97, date: "28 juin" },
      { n: "Extrait_Kbis.pdf", type: "Kbis", conf: 99, date: "05 janv." },
      { n: "Pacte_associés.pdf", type: "Pacte", conf: 94, date: "10 janv." },
    ],
  },
  biz: {
    label: "Documents business", icon: "🤝", files: [
      { n: "Contrat_BETA_prestation.pdf", type: "Contrat client", conf: 72, date: "28 janv." },
      { n: "Contrat_fournisseur_ACME.pdf", type: "Contrat fourn.", conf: 91, date: "03 févr." },
      { n: "CGV_2026.pdf", type: "CGV", conf: 96, date: "02 janv." },
      { n: "Bail_commercial.pdf", type: "Bail", conf: 88, date: "15 janv." },
    ],
  },
  rh: {
    label: "Documents RH", icon: "👥", files: [
      { n: "Bulletin_paie_Martin.pdf", type: "Bulletin", conf: 90, date: "31 janv." },
      { n: "Dossier_salarié_Durand.pdf", type: "Dossier RH", conf: 93, date: "20 janv." },
      { n: "Contrat_travail_Petit.pdf", type: "Contrat", conf: 95, date: "08 janv." },
    ],
  },
};

export const PRO_TREE = [
  { dom: "Expertise comptable", pros: [
    { n: "Cabinet Durand & Associés", r: "Expert-comptable · CAC", dispo: "Sous 48h", init: "DA" },
    { n: "Cabinet Novéo Audit", r: "Expert-comptable", dispo: "Sous 4j", init: "NA" },
  ] },
  { dom: "Droit des affaires", pros: [
    { n: "Me Sophie Lambert", r: "Avocate — droit des sociétés", dispo: "Sous 72h", init: "SL" },
    { n: "Me Karim Benali", r: "Avocat — contrats & contentieux", dispo: "Sous 5j", init: "KB" },
  ] },
  { dom: "Fiscalité", pros: [
    { n: "Cabinet FiscaConseil", r: "Fiscaliste", dispo: "Sous 5j", init: "FC" },
  ] },
  { dom: "Paie & social", pros: [
    { n: "PaieExpert Services", r: "Gestionnaire paie / DSN", dispo: "Sous 48h", init: "PE" },
  ] },
  { dom: "Financement & DAF externe", pros: [
    { n: "Hélène Roux", r: "DAF externalisée à temps partagé", dispo: "Sous 1 sem.", init: "HR" },
  ] },
];

export const APPT_SLOTS = [
  "Mar. 11 · 09:00", "Mar. 11 · 14:30", "Mer. 12 · 10:00",
  "Jeu. 13 · 16:00", "Ven. 14 · 11:30", "Ven. 14 · 15:00",
];

export const VEILLE = [
  { t: "Juridique", sev: C.navy2, body: "Vos statuts prévoient une AG d'approbation avant le 30 juin. Modèle de PV pré-rempli disponible.", tag: "À préparer" },
  { t: "Fiscale", sev: C.gold, body: "Échéance e-facturation : réception obligatoire au 1er sept. 2026. Votre dossier est prêt à 65 %.", tag: "En cours" },
  { t: "Sectorielle", sev: C.blue, body: "Point à surveiller : évolution des délais de paiement clients de votre secteur. Impact BFR à évaluer.", tag: "À surveiller" },
  { t: "Comptable", sev: C.green, body: "Pré-clôture : 3 charges constatées d'avance identifiées, prêtes à être transmises à votre cabinet.", tag: "Opportunité" },
];

export const INITIAL_CHAT = [
  { role: "ia", t: "Bonjour. Je surveille en continu votre dossier : échéances, trésorerie, contrats, paie, et je fais une veille juridique, fiscale, sectorielle et comptable. Je vous alerte et je vous propose des scénarios. Les sujets réglementés sont orientés vers un professionnel." },
  { role: "user", t: "Pourquoi ma trésorerie baisse à partir de décembre ?" },
  { role: "ia", t: "Sur la période prévisionnelle (déc.→févr.), vos décaissements passent de ~53 k€ à ~92 k€/mois tandis que les encaissements progressent moins vite. Résultat : le solde recule de 131 k€ à 76 k€. Deux leviers : étaler un décaissement fournisseur, ou activer une ligne court terme.", src: "Source : relevés bancaires · échéancier fournisseurs · confiance 88% · simulation à valider" },
];
