import type { Metadata } from "next";
import Link from "next/link";
import Reveal from "@/components/marketing/Reveal";
import { LogoMark } from "@/components/Logo";
import { IconArrowRight, IconShield, IconScale, IconEye, IconGlobe } from "@/components/marketing/icons";

export const metadata: Metadata = {
  title: "À propos",
  description:
    "RAF 360, édité par Lindbergh Formation / Groupe ARCAN, met la puissance d'un DAF au service des TPE et PME françaises, dans le strict respect du cadre réglementé.",
};

const VALUES = [
  {
    icon: <IconShield width={22} height={22} />,
    t: "Rigueur réglementaire",
    d: "Séparation stricte entre l'outil et les actes réglementés. Nous préparons ; les professionnels habilités décident et engagent.",
  },
  {
    icon: <IconEye width={22} height={22} />,
    t: "Transparence radicale",
    d: "Chaque analyse est sourcée et assortie d'un score de confiance. Rien d'inventé : ce qui n'est pas vérifié est signalé comme estimatif.",
  },
  {
    icon: <IconScale width={22} height={22} />,
    t: "Indépendance",
    d: "Aucune commission sur les honoraires des experts. Notre seul intérêt est votre pilotage, pas la vente de prestations tierces.",
  },
  {
    icon: <IconGlobe width={22} height={22} />,
    t: "Ancrage français",
    d: "Sources officielles françaises uniquement, données hébergées en France, conçu pour la réalité des TPE et PME du pays.",
  },
];

export default function AProposPage() {
  return (
    <>
      <section className="page-hero">
        <div className="container">
          <Reveal as="div" className="eyebrow" style={{ marginBottom: 18 }}>
            À propos
          </Reveal>
          <Reveal as="h1" delay={60} className="display" style={{ maxWidth: 900, marginBottom: 22 }}>
            Mettre la puissance d&apos;un DAF entre les mains de chaque dirigeant.
          </Reveal>
          <Reveal as="p" delay={120} className="lead" style={{ maxWidth: 640 }}>
            Les grandes entreprises ont un directeur administratif et financier. Les TPE et PME, elles, portent tout
            seules. RAF 360 comble cet écart — avec la rigueur du métier et le respect absolu du cadre réglementé.
          </Reveal>
        </div>
      </section>

      {/* Mission */}
      <section className="section">
        <div className="container">
          <div
            className="showcase-grid"
            style={{ display: "grid", gridTemplateColumns: "1.1fr 0.9fr", gap: 56, alignItems: "center" }}
          >
            <Reveal anim="left">
              <span className="eyebrow" style={{ marginBottom: 16 }}>
                Notre mission
              </span>
              <h2 className="h2" style={{ marginBottom: 18 }}>
                Rendre le pilotage financier accessible, fiable et serein.
              </h2>
              <p className="lead" style={{ marginBottom: 18 }}>
                RAF 360 est édité par <strong style={{ color: "var(--navy-900)" }}>Lindbergh Formation / Groupe
                ARCAN</strong>. Nous réunissons expertise financière, ingénierie logicielle et exigence de conformité
                pour bâtir un copilote au service du dirigeant.
              </p>
              <p className="lead">
                Notre conviction : la technologie doit préparer et éclairer, jamais remplacer le jugement d&apos;un
                professionnel habilité. C&apos;est ce principe qui structure chaque décision de conception.
              </p>
            </Reveal>
            <Reveal anim="right" delay={100} style={{ display: "flex", justifyContent: "center" }}>
              <div
                style={{
                  position: "relative",
                  width: 300,
                  height: 300,
                  display: "flex",
                  alignItems: "center",
                  justifyContent: "center",
                }}
              >
                <div
                  aria-hidden
                  style={{
                    position: "absolute",
                    inset: 0,
                    borderRadius: "50%",
                    background: "radial-gradient(circle at 50% 40%, rgba(143,187,238,0.25), transparent 70%)",
                  }}
                />
                <div className="floaty">
                  <LogoMark size={220} />
                </div>
              </div>
            </Reveal>
          </div>
        </div>
      </section>

      {/* Values */}
      <section className="section band-paper3">
        <div className="container">
          <div style={{ textAlign: "center", maxWidth: 640, margin: "0 auto 52px" }}>
            <Reveal as="div" className="eyebrow" style={{ marginBottom: 16, justifyContent: "center" }}>
              Nos valeurs
            </Reveal>
            <Reveal as="h2" delay={60} className="h2">
              Ce qui nous engage.
            </Reveal>
          </div>
          <div className="feature-grid" style={{ gridTemplateColumns: "repeat(2,1fr)" }}>
            {VALUES.map((v, i) => (
              <Reveal key={v.t} delay={(i % 2) * 90}>
                <div className="feature-card" style={{ display: "flex", gap: 20 }}>
                  <div className="feature-ic" style={{ marginBottom: 0, flexShrink: 0 }}>
                    {v.icon}
                  </div>
                  <div>
                    <h3>{v.t}</h3>
                    <p>{v.d}</p>
                  </div>
                </div>
              </Reveal>
            ))}
          </div>
        </div>
      </section>

      {/* Stats band */}
      <section className="section band-dark on-dark">
        <div className="container">
          <div className="stats-grid" style={{ display: "grid", gridTemplateColumns: "repeat(3,1fr)", gap: 28 }}>
            {[
              ["5", "domaines couverts : financier, comptable, fiscal, social, juridique"],
              ["100 %", "sources officielles françaises"],
              ["0", "commission sur les honoraires des experts"],
            ].map((s, i) => (
              <Reveal key={i} delay={i * 90} style={{ textAlign: "center" }}>
                <div className="stat-num">{s[0]}</div>
                <div className="stat-label">{s[1]}</div>
              </Reveal>
            ))}
          </div>
        </div>
      </section>

      <section className="section band-dark on-dark" style={{ textAlign: "center", paddingTop: 0 }}>
        <div className="container container-narrow">
          <Reveal as="h2" className="h2" style={{ marginBottom: 20 }}>
            Envie d&apos;en savoir plus ?
          </Reveal>
          <Reveal delay={100} style={{ display: "flex", gap: 14, justifyContent: "center", flexWrap: "wrap" }}>
            <Link href="/app/onboarding" className="m-btn m-btn-gold">
              Nous contacter <IconArrowRight width={18} height={18} />
            </Link>
            <Link href="/produit" className="m-btn m-btn-ghost on-dark">
              Découvrir le produit
            </Link>
          </Reveal>
        </div>
      </section>
    </>
  );
}
