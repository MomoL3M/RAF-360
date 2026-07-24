import Link from "next/link";
import Reveal from "@/components/marketing/Reveal";
import AppPreview from "@/components/marketing/AppPreview";
import { LogoMark } from "@/components/Logo";
import {
  IconArrowRight,
  IconSparkle,
  IconChart,
  IconCalendar,
  IconVault,
  IconDoc,
  IconUsers,
  IconShield,
  IconCheck,
} from "@/components/marketing/icons";

const FEATURES = [
  {
    icon: <IconSparkle width={24} height={24} />,
    title: "Copilote IA sur chaque écran",
    body: "Il surveille, alerte, anticipe et propose des scénarios chiffrés avec une recommandation claire — jamais un fait non vérifié.",
  },
  {
    icon: <IconChart width={24} height={24} />,
    title: "Trésorerie éclairée",
    body: "Position consolidée, réalisée et prévisionnelle. Repérez le point bas des semaines à l'avance et agissez à temps.",
  },
  {
    icon: <IconCalendar width={24} height={24} />,
    title: "Échéances maîtrisées",
    body: "TVA, IS, DSN, approbation des comptes… chaque obligation est datée, sourcée et priorisée automatiquement.",
  },
  {
    icon: <IconVault width={24} height={24} />,
    title: "Coffre-fort documentaire",
    body: "Dépôt, OCR et classement automatique par arborescence, avec un score de confiance sur chaque pièce.",
  },
  {
    icon: <IconDoc width={24} height={24} />,
    title: "Facturation électronique",
    body: "Capture, pré-imputation et préparation à la réforme e-facture — connecté à une plateforme agréée (PDP).",
  },
  {
    icon: <IconUsers width={24} height={24} />,
    title: "Le bon professionnel, au bon moment",
    body: "Data room révocable et réseau d'experts habilités : les sujets réglementés sont orientés, jamais improvisés.",
  },
];

const STEPS = [
  { t: "Connectez votre entreprise", d: "Un SIREN suffit : nous récupérons votre forme juridique et vos effectifs (SIRENE/INSEE)." },
  { t: "Centralisez vos documents", d: "Par email, mobile ou scanner. L'OCR classe et contrôle chaque pièce automatiquement." },
  { t: "Laissez le copilote veiller", d: "Il croise trésorerie, échéances et contrats, puis vous alerte et propose des scénarios." },
  { t: "Décidez, puis engagez un expert", d: "Chaque sujet réglementé est routé vers le professionnel habilité, avec un partage limité et tracé." },
];

const PRICING = [
  {
    name: "Essentiel",
    price: "49 €",
    period: "/ mois",
    tagline: "Pour les TPE qui veulent y voir clair.",
    feats: ["Tableau de bord & échéances", "Coffre-fort documentaire", "Copilote IA — alertes", "1 entité"],
    featured: false,
  },
  {
    name: "Pilotage",
    price: "129 €",
    period: "/ mois",
    tagline: "Le pilotage complet du dirigeant.",
    feats: [
      "Tout l'Essentiel",
      "Trésorerie prévisionnelle",
      "Scénarios & recommandations",
      "Préparation e-facture (PDP)",
      "Data room & réseau d'experts",
    ],
    featured: true,
  },
  {
    name: "Cabinet",
    price: "Sur mesure",
    period: "",
    tagline: "Pour les experts-comptables multi-clients.",
    feats: ["Multi-entités illimitées", "Espaces collaboratifs", "Marque blanche", "Accompagnement dédié"],
    featured: false,
  },
];

const QUOTES = [
  {
    q: "Pour la première fois, je vois arriver mes tensions de trésorerie avant qu'elles ne se produisent. Le copilote m'a fait gagner un découvert.",
    n: "Camille Roussel",
    r: "Dirigeante, agence de design (SAS, 8 salariés)",
  },
  {
    q: "Les échéances fiscales et sociales ne me stressent plus : tout est daté, sourcé et préparé. Mon expert-comptable reçoit des dossiers propres.",
    n: "Thomas Nguyen",
    r: "Gérant, société de conseil (SARL)",
  },
  {
    q: "L'orientation vers un avocat au bon moment, avec les bons documents déjà réunis, a sécurisé un contrat sensible en 72h.",
    n: "Sarah Benkacem",
    r: "Cofondatrice, studio tech (SAS)",
  },
];

export default function Home() {
  return (
    <>
      {/* ===================== HERO ===================== */}
      <section className="hero">
        <div
          className="spin-slow"
          aria-hidden="true"
          style={{
            position: "absolute",
            top: -180,
            right: -160,
            width: 560,
            height: 560,
            opacity: 0.06,
            pointerEvents: "none",
          }}
        >
          <LogoMark size={560} />
        </div>

        <div className="container">
          <div className="hero-grid">
            <div>
              <Reveal as="div">
                <span className="pill" style={{ marginBottom: 22 }}>
                  <span className="pill-dot" /> France · Sources officielles uniquement
                </span>
              </Reveal>
              <Reveal as="h1" delay={60} className="display" style={{ marginBottom: 22 }}>
                Le pilotage financier de votre PME,{" "}
                <span className="gold-em">enfin sous contrôle.</span>
              </Reveal>
              <Reveal as="p" delay={120} className="lead" style={{ maxWidth: 540, marginBottom: 30 }}>
                RAF 360 centralise votre pilotage financier, comptable, fiscal, social et juridique. Un copilote veille
                en continu, anticipe les risques, et oriente chaque sujet réglementé vers le bon professionnel habilité.
              </Reveal>
              <Reveal delay={180} style={{ display: "flex", gap: 14, flexWrap: "wrap", marginBottom: 26 }}>
                <Link href="/app/onboarding" className="m-btn m-btn-gold">
                  Demander une démo <IconArrowRight width={18} height={18} />
                </Link>
                <Link href="/app/dashboard" className="m-btn m-btn-ghost">
                  Explorer le produit
                </Link>
              </Reveal>
              <Reveal delay={240} style={{ display: "flex", gap: 22, flexWrap: "wrap", color: "var(--muted)", fontSize: 14 }}>
                {["Sans engagement", "Mise en route en 10 min", "Hébergement en France"].map((t) => (
                  <span key={t} style={{ display: "inline-flex", alignItems: "center", gap: 7 }}>
                    <IconCheck width={17} height={17} style={{ color: "var(--green)" }} /> {t}
                  </span>
                ))}
              </Reveal>
            </div>

            <Reveal anim="scale" delay={160} style={{ position: "relative" }}>
              <div className="floaty">
                <AppPreview />
              </div>
              <div className="float-card floaty-slow" style={{ top: -22, left: -18 }}>
                <div style={{ display: "flex", alignItems: "center", gap: 10 }}>
                  <span
                    style={{
                      width: 34,
                      height: 34,
                      borderRadius: 10,
                      background: "#e9f6f0",
                      color: "#2FA37C",
                      display: "flex",
                      alignItems: "center",
                      justifyContent: "center",
                    }}
                  >
                    <IconShield width={18} height={18} />
                  </span>
                  <div>
                    <div style={{ fontWeight: 700, color: "#101b3a" }}>Conformité à jour</div>
                    <div style={{ color: "var(--muted)", fontSize: 12 }}>6 échéances suivies</div>
                  </div>
                </div>
              </div>
              <div className="float-card floaty" style={{ bottom: -20, right: -14, animationDelay: "1.5s" }}>
                <div style={{ fontSize: 11, color: "var(--muted)", fontWeight: 600 }}>Prévision fin février</div>
                <div style={{ fontSize: 19, fontWeight: 800, color: "#14306b" }}>
                  76 k€ <span style={{ color: "#C0503F", fontSize: 12 }}>▼ anticipé</span>
                </div>
              </div>
            </Reveal>
          </div>
        </div>
      </section>

      {/* ===================== TRUST BAR ===================== */}
      <section className="section-sm band-soft" style={{ borderTop: "1px solid var(--hairline)", borderBottom: "1px solid var(--hairline)" }}>
        <div className="container">
          <Reveal as="p" style={{ textAlign: "center", color: "var(--muted)", fontSize: 13.5, fontWeight: 600, letterSpacing: 1, textTransform: "uppercase", marginBottom: 26 }}>
            Adossé aux sources officielles françaises
          </Reveal>
          <Reveal as="div" delay={80} className="logo-row">
            {["Légifrance", "impots.gouv.fr", "INSEE · SIRENE", "URSSAF", "BOFiP", "net-entreprises"].map((s) => (
              <span key={s}>{s}</span>
            ))}
          </Reveal>
        </div>
      </section>

      {/* ===================== STATS (dark) ===================== */}
      <section className="section band-dark on-dark">
        <div className="container">
          <div style={{ display: "grid", gridTemplateColumns: "repeat(4,1fr)", gap: 28 }} className="stats-grid">
            {[
              ["360°", "Financier · fiscal · social · juridique"],
              ["6 domaines", "de veille surveillés en continu"],
              ["100 %", "sources officielles, jamais inventées"],
              ["10 min", "pour configurer votre espace"],
            ].map((s, i) => (
              <Reveal key={i} delay={i * 90} style={{ textAlign: "center" }}>
                <div className="stat-num">{s[0]}</div>
                <div className="stat-label">{s[1]}</div>
              </Reveal>
            ))}
          </div>
        </div>
      </section>

      {/* ===================== FEATURES ===================== */}
      <section className="section" id="features">
        <div className="container">
          <div style={{ maxWidth: 720, marginBottom: 52 }}>
            <Reveal as="div" className="eyebrow" style={{ marginBottom: 16 }}>
              Un système d&apos;exploitation du dirigeant
            </Reveal>
            <Reveal as="h2" delay={60} className="h2" style={{ marginBottom: 18 }}>
              Tout ce qui pèse sur vos épaules, réuni et anticipé.
            </Reveal>
            <Reveal as="p" delay={120} className="lead">
              RAF 360 ne se substitue jamais à un professionnel. Il collecte, contrôle, éclaire vos décisions et vous met
              en relation avec l&apos;expert habilité quand c&apos;est nécessaire.
            </Reveal>
          </div>
          <div className="feature-grid">
            {FEATURES.map((f, i) => (
              <Reveal key={i} delay={(i % 3) * 90}>
                <div className="feature-card">
                  <div className="feature-ic">{f.icon}</div>
                  <h3>{f.title}</h3>
                  <p>{f.body}</p>
                </div>
              </Reveal>
            ))}
          </div>
        </div>
      </section>

      {/* ===================== PRODUCT SHOWCASE ===================== */}
      <section className="section band-paper3" id="copilote">
        <div className="container">
          <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 56, alignItems: "center" }} className="showcase-grid">
            <Reveal anim="left">
              <span className="eyebrow" style={{ marginBottom: 16 }}>
                Le copilote
              </span>
              <h2 className="h2" style={{ marginBottom: 18 }}>
                Il ne se contente pas de répondre. Il vous devance.
              </h2>
              <p className="lead" style={{ marginBottom: 24 }}>
                En croisant vos relevés, votre échéancier et le calendrier fiscal, le copilote détecte les risques avant
                qu&apos;ils n&apos;arrivent — puis compare des scénarios concrets, avantages et inconvénients à l&apos;appui.
              </p>
              <ul style={{ listStyle: "none", marginBottom: 28 }}>
                {[
                  "Alertes prioritaires avec date et montant estimatif",
                  "Scénarios chiffrés + recommandation du copilote",
                  "Chaque analyse est sourcée et accompagnée d'un score de confiance",
                ].map((t) => (
                  <li key={t} className="check-li">
                    <span className="ic">
                      <IconCheck width={15} height={15} />
                    </span>
                    <span style={{ color: "var(--ink-soft)", fontSize: 15.5, lineHeight: 1.5 }}>{t}</span>
                  </li>
                ))}
              </ul>
              <Link href="/produit" className="link-arrow">
                Découvrir le produit <IconArrowRight width={17} height={17} />
              </Link>
            </Reveal>
            <Reveal anim="right" delay={100}>
              <AppPreview />
            </Reveal>
          </div>
        </div>
      </section>

      {/* ===================== HOW IT WORKS ===================== */}
      <section className="section">
        <div className="container">
          <div style={{ textAlign: "center", maxWidth: 680, margin: "0 auto 54px" }}>
            <Reveal as="div" className="eyebrow" style={{ marginBottom: 16, justifyContent: "center" }}>
              Comment ça marche
            </Reveal>
            <Reveal as="h2" delay={60} className="h2">
              Opérationnel en quatre étapes.
            </Reveal>
          </div>
          <div className="steps">
            {STEPS.map((s, i) => (
              <Reveal key={i} delay={i * 90} className="step">
                <span className="step-n">{String(i + 1).padStart(2, "0")}</span>
                <h3>{s.t}</h3>
                <p>{s.d}</p>
              </Reveal>
            ))}
          </div>
        </div>
      </section>

      {/* ===================== COMPLIANCE (dark) ===================== */}
      <section className="section band-dark on-dark" id="conformite">
        <div className="container">
          <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 56, alignItems: "center" }} className="showcase-grid">
            <Reveal anim="left">
              <span className="eyebrow on-dark" style={{ marginBottom: 16 }}>
                Confiance & conformité
              </span>
              <h2 className="h2" style={{ marginBottom: 18 }}>
                Sérieux réglementaire, par conception.
              </h2>
              <p className="lead" style={{ marginBottom: 28 }}>
                RAF 360 respecte une séparation stricte entre l&apos;outil (SaaS) et les actes réglementés, confiés aux
                professionnels habilités. Aucune commission sur leurs honoraires. Vos données restent les vôtres.
              </p>
              <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 16 }}>
                {[
                  ["Sources officielles", "Légifrance, BOFiP, INSEE, URSSAF — jamais d'invention."],
                  ["Données en France", "Hébergement français, chiffrement, journalisation des accès."],
                  ["Partage révocable", "Vous choisissez chaque document, limité dans le temps."],
                  ["Escalade humaine", "Au-delà d'un seuil, un professionnel prend le relais."],
                ].map((c, i) => (
                  <div key={i}>
                    <div style={{ display: "flex", alignItems: "center", gap: 9, marginBottom: 6 }}>
                      <IconShield width={19} height={19} style={{ color: "var(--gold-400)" }} />
                      <span style={{ fontWeight: 700, color: "#fff", fontSize: 15 }}>{c[0]}</span>
                    </div>
                    <p style={{ color: "rgba(219,232,251,0.7)", fontSize: 13.5, lineHeight: 1.5 }}>{c[1]}</p>
                  </div>
                ))}
              </div>
            </Reveal>
            <Reveal anim="right" delay={100} style={{ display: "flex", justifyContent: "center" }}>
              <div style={{ position: "relative", width: 300, height: 300 }}>
                <div className="spin-slow" style={{ position: "absolute", inset: 0, opacity: 0.9 }}>
                  <LogoMark size={300} />
                </div>
              </div>
            </Reveal>
          </div>
        </div>
      </section>

      {/* ===================== TESTIMONIALS ===================== */}
      <section className="section">
        <div className="container">
          <div style={{ textAlign: "center", maxWidth: 680, margin: "0 auto 52px" }}>
            <Reveal as="div" className="eyebrow" style={{ marginBottom: 16, justifyContent: "center" }}>
              Ils pilotent avec RAF 360
            </Reveal>
            <Reveal as="h2" delay={60} className="h2">
              La sérénité, mesurable.
            </Reveal>
          </div>
          <div className="feature-grid">
            {QUOTES.map((q, i) => (
              <Reveal key={i} delay={i * 90}>
                <div className="quote-card">
                  <div className="quote-mark">&ldquo;</div>
                  <p style={{ color: "var(--ink-soft)", fontSize: 15.5, lineHeight: 1.6, margin: "6px 0 20px" }}>{q.q}</p>
                  <div style={{ display: "flex", alignItems: "center", gap: 12 }}>
                    <div
                      style={{
                        width: 42,
                        height: 42,
                        borderRadius: "50%",
                        background: "linear-gradient(160deg,#14306b,#2c6fb0)",
                        color: "#fff",
                        display: "flex",
                        alignItems: "center",
                        justifyContent: "center",
                        fontWeight: 700,
                        fontSize: 14,
                      }}
                    >
                      {q.n.split(" ").map((w) => w[0]).join("")}
                    </div>
                    <div>
                      <div style={{ fontWeight: 700, color: "#101b3a", fontSize: 14.5 }}>{q.n}</div>
                      <div style={{ color: "var(--muted)", fontSize: 13 }}>{q.r}</div>
                    </div>
                  </div>
                </div>
              </Reveal>
            ))}
          </div>
        </div>
      </section>

      {/* ===================== PRICING TEASER ===================== */}
      <section className="section band-soft" id="tarifs">
        <div className="container">
          <div style={{ textAlign: "center", maxWidth: 680, margin: "0 auto 52px" }}>
            <Reveal as="div" className="eyebrow" style={{ marginBottom: 16, justifyContent: "center" }}>
              Tarifs
            </Reveal>
            <Reveal as="h2" delay={60} className="h2" style={{ marginBottom: 16 }}>
              Un tarif clair, sans surprise.
            </Reveal>
            <Reveal as="p" delay={120} className="lead">
              Essayez sans engagement. Changez ou arrêtez à tout moment.
            </Reveal>
          </div>
          <div className="price-grid">
            {PRICING.map((p, i) => (
              <Reveal key={i} delay={i * 90} style={{ display: "flex" }}>
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
                    {p.price === "Sur mesure" ? "Nous contacter" : "Commencer"}
                  </Link>
                </div>
              </Reveal>
            ))}
          </div>
          <Reveal as="p" delay={200} style={{ textAlign: "center", marginTop: 28 }}>
            <Link href="/tarifs" className="link-arrow">
              Voir le détail des offres <IconArrowRight width={17} height={17} />
            </Link>
          </Reveal>
        </div>
      </section>

      {/* ===================== FINAL CTA ===================== */}
      <section className="section band-dark on-dark" style={{ textAlign: "center" }}>
        <div className="container container-narrow">
          <Reveal as="h2" className="h2" style={{ marginBottom: 20 }}>
            Reprenez la main sur vos finances, dès aujourd&apos;hui.
          </Reveal>
          <Reveal as="p" delay={80} className="lead" style={{ marginBottom: 32, marginLeft: "auto", marginRight: "auto", maxWidth: 620 }}>
            Rejoignez les dirigeants qui pilotent sereinement leur entreprise avec RAF 360. Démonstration gratuite,
            sans engagement.
          </Reveal>
          <Reveal delay={140} style={{ display: "flex", gap: 14, justifyContent: "center", flexWrap: "wrap" }}>
            <Link href="/app/onboarding" className="m-btn m-btn-gold">
              Demander une démo <IconArrowRight width={18} height={18} />
            </Link>
            <Link href="/app/dashboard" className="m-btn m-btn-ghost on-dark">
              Explorer la démo
            </Link>
          </Reveal>
        </div>
      </section>
    </>
  );
}
