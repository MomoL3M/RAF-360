import type { Metadata } from "next";
import Link from "next/link";
import Reveal from "@/components/marketing/Reveal";
import AppPreview from "@/components/marketing/AppPreview";
import {
  IconArrowRight,
  IconSparkle,
  IconChart,
  IconCalendar,
  IconVault,
  IconDoc,
  IconUsers,
  IconCheck,
} from "@/components/marketing/icons";

export const metadata: Metadata = {
  title: "Produit",
  description:
    "Le copilote RAF 360 sur chaque écran : trésorerie prévisionnelle, échéances sourcées, coffre-fort documentaire, e-facture et réseau de professionnels habilités.",
};

const MODULES = [
  {
    id: "copilote",
    icon: <IconSparkle width={24} height={24} />,
    kicker: "Copilote IA",
    title: "Un copilote qui anticipe, sur chaque écran.",
    body: "Il surveille en continu votre dossier, croise vos données et vous alerte avant que le problème n'arrive. Chaque analyse est sourcée, chiffrée et accompagnée d'un score de confiance.",
    points: [
      "Alertes prioritaires datées avec montant estimatif",
      "Scénarios comparés (avantages / inconvénients) + recommandation",
      "Veille juridique, fiscale, sectorielle et comptable en continu",
    ],
  },
  {
    id: "tresorerie",
    icon: <IconChart width={24} height={24} />,
    kicker: "Trésorerie",
    title: "Voyez le point bas des semaines à l'avance.",
    body: "Position consolidée multi-comptes, réalisée et prévisionnelle. Survolez la courbe, ouvrez le détail d'un mois, zoomez sur une période — et déclenchez le bon levier à temps.",
    points: [
      "Courbe réalisée (fonctionnelle) + prévisionnelle",
      "Détail encaissements / décaissements par mois",
      "Alertes d'encaissement client avec mode et date",
    ],
  },
  {
    id: "echeances",
    icon: <IconCalendar width={24} height={24} />,
    kicker: "Échéances",
    title: "Plus jamais une obligation oubliée.",
    body: "TVA, IS, DSN, bail, approbation des comptes… Chaque échéance est datée, sourcée, priorisée et préparée. Les retards sont mis en évidence, les acomptes estimés.",
    points: [
      "Calendrier trié du plus proche au plus lointain",
      "Montants estimatifs clairement signalés",
      "Brouillons préparés par le copilote",
    ],
  },
  {
    id: "coffre",
    icon: <IconVault width={24} height={24} />,
    kicker: "Coffre-fort documentaire",
    title: "Vos pièces, classées et contrôlées.",
    body: "Déposez par email, mobile ou scanner. L'OCR lit, classe par arborescence (corporate, business, RH) et attribue un score de confiance — les pièces douteuses sont signalées.",
    points: [
      "Classement automatique par domaine",
      "Score de confiance par document",
      "Détection des pièces manquantes",
    ],
  },
  {
    id: "factures",
    icon: <IconDoc width={24} height={24} />,
    kicker: "Factures & e-facture",
    title: "Prêt pour la réforme, sans stress.",
    body: "Capture, pré-imputation, détection de doublons et préparation à la facturation électronique via une plateforme partenaire agréée (PDP). Anticipez les relances clients.",
    points: [
      "Réception obligatoire au 1er sept. 2026",
      "Émission TPE/PME au 1er sept. 2027",
      "Anticipation des relances & du BFR",
    ],
  },
  {
    id: "dataroom",
    icon: <IconUsers width={24} height={24} />,
    kicker: "Data room & experts",
    title: "Le bon professionnel, au bon moment.",
    body: "Quand un sujet est réglementé, le copilote oriente vers l'expert habilité et prépare la sélection de documents. Partage limité dans le temps, révocable et journalisé.",
    points: [
      "Réseau d'experts par domaine",
      "Prise de rendez-vous intégrée",
      "Aucune commission sur les honoraires",
    ],
  },
];

export default function ProduitPage() {
  return (
    <>
      <section className="page-hero">
        <div className="container">
          <Reveal as="div" className="eyebrow" style={{ marginBottom: 18 }}>
            Le produit
          </Reveal>
          <Reveal as="h1" delay={60} className="display" style={{ maxWidth: 880, marginBottom: 22 }}>
            Un système d&apos;exploitation pour le dirigeant.
          </Reveal>
          <Reveal as="p" delay={120} className="lead" style={{ maxWidth: 620, marginBottom: 32 }}>
            Six modules, un même fil conducteur : le copilote. Il collecte, contrôle, éclaire vos décisions et vous met
            en relation avec le bon expert — sans jamais s&apos;y substituer.
          </Reveal>
          <Reveal delay={180} style={{ display: "flex", gap: 14, flexWrap: "wrap" }}>
            <Link href="/app/onboarding" className="m-btn m-btn-gold">
              Demander une démo <IconArrowRight width={18} height={18} />
            </Link>
            <Link href="/app/dashboard" className="m-btn m-btn-ghost">
              Explorer la démo interactive
            </Link>
          </Reveal>
        </div>
      </section>

      {MODULES.map((m, i) => {
        const reversed = i % 2 === 1;
        const dark = i === 2;
        return (
          <section
            key={m.id}
            id={m.id}
            className={`section ${dark ? "band-dark on-dark" : i % 2 === 1 ? "band-paper3" : ""}`}
          >
            <div className="container">
              <div
                className="showcase-grid"
                style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 56, alignItems: "center" }}
              >
                <Reveal anim={reversed ? "right" : "left"} style={{ order: reversed ? 2 : 1 }}>
                  <span className={`eyebrow ${dark ? "on-dark" : ""}`} style={{ marginBottom: 16 }}>
                    {m.kicker}
                  </span>
                  <h2 className="h2" style={{ marginBottom: 18 }}>
                    {m.title}
                  </h2>
                  <p className="lead" style={{ marginBottom: 24 }}>
                    {m.body}
                  </p>
                  <ul style={{ listStyle: "none" }}>
                    {m.points.map((p) => (
                      <li key={p} className="check-li">
                        <span
                          className="ic"
                          style={dark ? { background: "rgba(237,163,35,0.16)", color: "var(--gold-400)" } : undefined}
                        >
                          <IconCheck width={15} height={15} />
                        </span>
                        <span
                          style={{
                            color: dark ? "rgba(219,232,251,0.9)" : "var(--ink-soft)",
                            fontSize: 15.5,
                            lineHeight: 1.5,
                          }}
                        >
                          {p}
                        </span>
                      </li>
                    ))}
                  </ul>
                </Reveal>
                <Reveal
                  anim={reversed ? "left" : "right"}
                  delay={100}
                  style={{ order: reversed ? 1 : 2, display: "flex", justifyContent: "center" }}
                >
                  {m.id === "copilote" || m.id === "tresorerie" ? (
                    <AppPreview />
                  ) : (
                    <div
                      className="feature-ic"
                      style={{
                        width: 200,
                        height: 200,
                        borderRadius: 40,
                        fontSize: 0,
                      }}
                    >
                      <span style={{ transform: "scale(3.4)" }}>{m.icon}</span>
                    </div>
                  )}
                </Reveal>
              </div>
            </div>
          </section>
        );
      })}

      <section className="section band-dark on-dark" style={{ textAlign: "center" }}>
        <div className="container container-narrow">
          <Reveal as="h2" className="h2" style={{ marginBottom: 20 }}>
            Voyez RAF 360 travailler pour vous.
          </Reveal>
          <Reveal delay={100} style={{ display: "flex", gap: 14, justifyContent: "center", flexWrap: "wrap" }}>
            <Link href="/app/onboarding" className="m-btn m-btn-gold">
              Demander une démo <IconArrowRight width={18} height={18} />
            </Link>
            <Link href="/tarifs" className="m-btn m-btn-ghost on-dark">
              Voir les tarifs
            </Link>
          </Reveal>
        </div>
      </section>
    </>
  );
}
