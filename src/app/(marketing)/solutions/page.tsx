import type { Metadata } from "next";
import Link from "next/link";
import Reveal from "@/components/marketing/Reveal";
import { IconArrowRight, IconCheck, IconBolt, IconScale, IconUsers } from "@/components/marketing/icons";

export const metadata: Metadata = {
  title: "Solutions",
  description:
    "RAF 360 s'adapte à votre réalité : TPE, PME et cabinets d'expertise comptable. Un pilotage financier taillé pour chaque profil.",
};

const SOLUTIONS = [
  {
    id: "tpe",
    icon: <IconBolt width={22} height={22} />,
    audience: "TPE & indépendants",
    title: "Y voir clair, sans être expert-comptable.",
    body: "Vous portez tout : la facturation, la TVA, la paie, la trésorerie. RAF 360 range, contrôle et vous alerte, pour que vous restiez concentré sur votre métier.",
    points: [
      "Zéro échéance oubliée, tout est daté et rappelé",
      "Trésorerie lisible en un coup d'œil",
      "Documents classés automatiquement",
      "Un expert à portée de clic quand il le faut",
    ],
  },
  {
    id: "pme",
    icon: <IconScale width={22} height={22} />,
    audience: "PME en croissance",
    title: "Le pilotage d'un DAF, à l'échelle de votre PME.",
    body: "Anticipez le BFR, comparez des scénarios de financement, sécurisez vos contrats et vos obligations sociales. Le copilote joue le rôle d'un directeur financier qui ne dort jamais.",
    points: [
      "Trésorerie prévisionnelle & scénarios chiffrés",
      "Suivi multi-comptes et multi-échéances",
      "Data room pour vos conseils (avocat, EC, CAC)",
      "Préparation à la facturation électronique",
    ],
  },
  {
    id: "experts",
    icon: <IconUsers width={22} height={22} />,
    audience: "Experts-comptables",
    title: "Des dossiers clients propres, en continu.",
    body: "Recevez des pièces contrôlées et pré-classées, réduisez les allers-retours et concentrez votre valeur là où elle compte. RAF 360 prépare, vous validez.",
    points: [
      "Multi-entités et espaces collaboratifs",
      "Pièces scorées et pré-imputées",
      "Marque blanche possible",
      "Escalade et validation humaine intégrées",
    ],
  },
];

export default function SolutionsPage() {
  return (
    <>
      <section className="page-hero">
        <div className="container">
          <Reveal as="div" className="eyebrow" style={{ marginBottom: 18 }}>
            Solutions
          </Reveal>
          <Reveal as="h1" delay={60} className="display" style={{ maxWidth: 880, marginBottom: 22 }}>
            Un pilotage taillé pour votre réalité.
          </Reveal>
          <Reveal as="p" delay={120} className="lead" style={{ maxWidth: 620 }}>
            Que vous soyez seul aux commandes ou à la tête d&apos;un cabinet, RAF 360 s&apos;adapte à votre niveau
            d&apos;exigence et à votre organisation.
          </Reveal>
        </div>
      </section>

      {SOLUTIONS.map((s, i) => {
        const reversed = i % 2 === 1;
        return (
          <section key={s.id} id={s.id} className={`section ${reversed ? "band-paper3" : ""}`}>
            <div className="container">
              <div
                className="showcase-grid"
                style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 56, alignItems: "center" }}
              >
                <Reveal anim={reversed ? "right" : "left"} style={{ order: reversed ? 2 : 1 }}>
                  <span className="pill" style={{ marginBottom: 18 }}>
                    {s.icon} {s.audience}
                  </span>
                  <h2 className="h2" style={{ marginBottom: 18 }}>
                    {s.title}
                  </h2>
                  <p className="lead" style={{ marginBottom: 24 }}>
                    {s.body}
                  </p>
                  <Link href="/app/onboarding" className="m-btn m-btn-primary m-btn-sm">
                    Demander une démo <IconArrowRight width={16} height={16} />
                  </Link>
                </Reveal>
                <Reveal anim={reversed ? "left" : "right"} delay={100} style={{ order: reversed ? 1 : 2 }}>
                  <div className="surface">
                    {s.points.map((p) => (
                      <div key={p} className="check-li">
                        <span className="ic">
                          <IconCheck width={15} height={15} />
                        </span>
                        <span style={{ color: "var(--ink-soft)", fontSize: 15.5, lineHeight: 1.5 }}>{p}</span>
                      </div>
                    ))}
                  </div>
                </Reveal>
              </div>
            </div>
          </section>
        );
      })}

      <section className="section band-dark on-dark" style={{ textAlign: "center" }}>
        <div className="container container-narrow">
          <Reveal as="h2" className="h2" style={{ marginBottom: 20 }}>
            Trouvons ensemble la formule adaptée.
          </Reveal>
          <Reveal as="p" delay={80} className="lead" style={{ marginBottom: 30, marginInline: "auto", maxWidth: 560 }}>
            En 20 minutes, nous cartographions vos obligations et vous montrons ce que RAF 360 change au quotidien.
          </Reveal>
          <Reveal delay={140}>
            <Link href="/app/onboarding" className="m-btn m-btn-gold">
              Parler à un conseiller <IconArrowRight width={18} height={18} />
            </Link>
          </Reveal>
        </div>
      </section>
    </>
  );
}
