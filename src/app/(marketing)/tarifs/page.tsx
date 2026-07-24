import type { Metadata } from "next";
import Link from "next/link";
import Reveal from "@/components/marketing/Reveal";
import { IconArrowRight, IconCheck } from "@/components/marketing/icons";

export const metadata: Metadata = {
  title: "Tarifs",
  description:
    "Des offres claires et sans engagement pour piloter vos finances : Essentiel, Pilotage et Cabinet. Essayez gratuitement.",
};

const PLANS = [
  {
    name: "Essentiel",
    price: "49 €",
    period: "/ mois HT",
    tagline: "Pour les TPE qui veulent y voir clair.",
    feats: ["Tableau de bord & centre d'actions", "Calendrier des échéances", "Coffre-fort documentaire (OCR)", "Copilote IA — alertes", "1 entité · 1 utilisateur"],
    cta: "Commencer",
    featured: false,
  },
  {
    name: "Pilotage",
    price: "129 €",
    period: "/ mois HT",
    tagline: "Le pilotage complet du dirigeant.",
    feats: [
      "Tout l'Essentiel, plus :",
      "Trésorerie prévisionnelle & scénarios",
      "Recommandations du copilote",
      "Préparation e-facture (PDP)",
      "Data room & réseau d'experts",
      "3 utilisateurs",
    ],
    cta: "Commencer",
    featured: true,
  },
  {
    name: "Cabinet",
    price: "Sur mesure",
    period: "",
    tagline: "Pour les experts-comptables multi-clients.",
    feats: ["Multi-entités illimitées", "Espaces collaboratifs", "Marque blanche", "API & intégrations", "Accompagnement dédié"],
    cta: "Nous contacter",
    featured: false,
  },
];

const COMPARE = [
  ["Tableau de bord & actions", true, true, true],
  ["Calendrier des échéances", true, true, true],
  ["Coffre-fort documentaire", true, true, true],
  ["Copilote — alertes", true, true, true],
  ["Trésorerie prévisionnelle", false, true, true],
  ["Scénarios & recommandations", false, true, true],
  ["Préparation e-facture (PDP)", false, true, true],
  ["Data room & réseau d'experts", false, true, true],
  ["Multi-entités", false, false, true],
  ["Marque blanche", false, false, true],
  ["Accompagnement dédié", false, false, true],
] as const;

const FAQ = [
  ["Y a-t-il un engagement ?", "Non. Tous les abonnements sont sans engagement, résiliables à tout moment. Vous pouvez aussi changer d'offre quand vous le souhaitez."],
  ["RAF 360 remplace-t-il mon expert-comptable ?", "Non, et c'est un principe fondateur. RAF 360 prépare, contrôle et éclaire, mais les actes réglementés restent confiés aux professionnels habilités. Aucune commission n'est prélevée sur leurs honoraires."],
  ["Mes données sont-elles en sécurité ?", "Vos données sont hébergées en France, chiffrées, et chaque accès est journalisé. Vous gardez la maîtrise de vos partages, limités dans le temps et révocables."],
  ["Puis-je essayer avant de payer ?", "Oui. Demandez une démo : nous configurons un espace de démonstration avec vos premières obligations en quelques minutes."],
];

export default function TarifsPage() {
  return (
    <>
      <section className="page-hero">
        <div className="container" style={{ textAlign: "center" }}>
          <Reveal as="div" className="eyebrow" style={{ marginBottom: 18, justifyContent: "center" }}>
            Tarifs
          </Reveal>
          <Reveal as="h1" delay={60} className="display" style={{ maxWidth: 820, margin: "0 auto 22px" }}>
            Un tarif clair, sans surprise.
          </Reveal>
          <Reveal as="p" delay={120} className="lead" style={{ maxWidth: 560, margin: "0 auto" }}>
            Choisissez la formule qui correspond à votre entreprise. Sans engagement, évolutif à tout moment.
          </Reveal>
        </div>
      </section>

      <section className="section" style={{ paddingTop: 24 }}>
        <div className="container">
          <div className="price-grid">
            {PLANS.map((p, i) => (
              <Reveal key={p.name} delay={i * 90} style={{ display: "flex" }}>
                <div className={`price-card ${p.featured ? "featured" : ""}`} style={{ flex: 1 }}>
                  {p.featured && (
                    <span
                      className="pill"
                      style={{ alignSelf: "flex-start", marginBottom: 14, background: "rgba(255,255,255,0.1)", borderColor: "rgba(255,255,255,0.2)", color: "#fff" }}
                    >
                      <span className="pill-dot" /> Le plus choisi
                    </span>
                  )}
                  <div style={{ fontSize: 14, fontWeight: 700, letterSpacing: 0.5, textTransform: "uppercase", opacity: 0.85 }}>
                    {p.name}
                  </div>
                  <div style={{ margin: "10px 0 4px", display: "flex", alignItems: "baseline", gap: 4 }}>
                    <span className="price-amount">{p.price}</span>
                    <span style={{ fontSize: 15, opacity: 0.65 }}>{p.period}</span>
                  </div>
                  <p style={{ fontSize: 14, opacity: 0.8, marginBottom: 18 }}>{p.tagline}</p>
                  <div style={{ flex: 1 }}>
                    {p.feats.map((f) => (
                      <div key={f} className="price-feat">
                        <IconCheck width={18} height={18} />
                        <span>{f}</span>
                      </div>
                    ))}
                  </div>
                  <Link
                    href="/app/onboarding"
                    className={`m-btn ${p.featured ? "m-btn-gold" : "m-btn-ghost"}`}
                    style={{ marginTop: 22, width: "100%" }}
                  >
                    {p.cta}
                  </Link>
                </div>
              </Reveal>
            ))}
          </div>
          <Reveal as="p" delay={220} style={{ textAlign: "center", marginTop: 22, color: "var(--muted)", fontSize: 14 }}>
            Tarifs indicatifs hors taxes. Offre de lancement — les prix définitifs seront confirmés à la souscription.
          </Reveal>
        </div>
      </section>

      {/* comparison table */}
      <section className="section band-soft">
        <div className="container">
          <div style={{ textAlign: "center", marginBottom: 40 }}>
            <Reveal as="h2" className="h2">
              Comparez les offres.
            </Reveal>
          </div>
          <Reveal as="div" className="surface" style={{ padding: 0, overflow: "hidden" }}>
            <div style={{ overflowX: "auto" }}>
              <table style={{ width: "100%", borderCollapse: "collapse", minWidth: 620 }}>
                <thead>
                  <tr style={{ background: "var(--paper-2)" }}>
                    <th style={{ textAlign: "left", padding: "18px 22px", fontSize: 13, color: "var(--muted)", fontWeight: 700, textTransform: "uppercase", letterSpacing: 0.6 }}>
                      Fonctionnalité
                    </th>
                    {["Essentiel", "Pilotage", "Cabinet"].map((n) => (
                      <th key={n} style={{ padding: "18px 16px", fontSize: 14, color: "var(--navy-900)", fontWeight: 700, fontFamily: "var(--font-serif-stack)" }}>
                        {n}
                      </th>
                    ))}
                  </tr>
                </thead>
                <tbody>
                  {COMPARE.map((row, i) => (
                    <tr key={i} style={{ borderTop: "1px solid var(--hairline)" }}>
                      <td style={{ padding: "14px 22px", fontSize: 14.5, color: "var(--ink-soft)" }}>{row[0]}</td>
                      {[row[1], row[2], row[3]].map((v, k) => (
                        <td key={k} style={{ padding: "14px 16px", textAlign: "center" }}>
                          {v ? (
                            <IconCheck width={19} height={19} style={{ color: "var(--green)" }} />
                          ) : (
                            <span style={{ color: "var(--hairline)", fontSize: 18 }}>—</span>
                          )}
                        </td>
                      ))}
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </Reveal>
        </div>
      </section>

      {/* FAQ */}
      <section className="section">
        <div className="container container-narrow">
          <div style={{ textAlign: "center", marginBottom: 40 }}>
            <Reveal as="div" className="eyebrow" style={{ marginBottom: 16, justifyContent: "center" }}>
              Questions fréquentes
            </Reveal>
            <Reveal as="h2" delay={60} className="h2">
              Tout ce qu&apos;il faut savoir.
            </Reveal>
          </div>
          <div style={{ display: "grid", gap: 14 }}>
            {FAQ.map(([q, a], i) => (
              <Reveal key={i} delay={i * 60} className="surface">
                <h3 className="h3" style={{ marginBottom: 10, fontSize: "1.2rem" }}>
                  {q}
                </h3>
                <p style={{ color: "var(--muted)", fontSize: 15.5, lineHeight: 1.6 }}>{a}</p>
              </Reveal>
            ))}
          </div>
        </div>
      </section>

      <section className="section band-dark on-dark" style={{ textAlign: "center" }}>
        <div className="container container-narrow">
          <Reveal as="h2" className="h2" style={{ marginBottom: 20 }}>
            Prêt à piloter sereinement ?
          </Reveal>
          <Reveal delay={100}>
            <Link href="/app/onboarding" className="m-btn m-btn-gold">
              Demander une démo <IconArrowRight width={18} height={18} />
            </Link>
          </Reveal>
        </div>
      </section>
    </>
  );
}
