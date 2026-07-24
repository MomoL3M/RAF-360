import type { Metadata } from "next";
import Link from "next/link";
import Reveal from "@/components/marketing/Reveal";
import { POSTS } from "@/data/blog";
import { IconArrowRight } from "@/components/marketing/icons";

export const metadata: Metadata = {
  title: "Blog",
  description:
    "Analyses, guides et repères pour piloter vos finances : trésorerie, fiscalité, conformité, comptabilité — par l'équipe RAF 360.",
};

export default function BlogPage() {
  const [featured, ...rest] = POSTS;

  return (
    <>
      <section className="page-hero">
        <div className="container">
          <Reveal as="div" className="eyebrow" style={{ marginBottom: 18 }}>
            Le blog
          </Reveal>
          <Reveal as="h1" delay={60} className="display" style={{ maxWidth: 820, marginBottom: 22 }}>
            Repères pour piloter sereinement.
          </Reveal>
          <Reveal as="p" delay={120} className="lead" style={{ maxWidth: 600 }}>
            Trésorerie, fiscalité, conformité, comptabilité : nos analyses claires pour transformer la contrainte en
            avantage.
          </Reveal>
        </div>
      </section>

      {/* Featured */}
      <section className="section" style={{ paddingTop: 12 }}>
        <div className="container">
          <Reveal>
            <Link
              href={`/blog/${featured.slug}`}
              className="surface"
              style={{
                display: "grid",
                gridTemplateColumns: "1.1fr 0.9fr",
                gap: 0,
                padding: 0,
                overflow: "hidden",
                textDecoration: "none",
              }}
            >
              <div style={{ padding: "42px 44px", display: "flex", flexDirection: "column", justifyContent: "center" }}>
                <span className="pill" style={{ alignSelf: "flex-start", marginBottom: 18 }}>
                  <span className="pill-dot" /> À la une · {featured.category}
                </span>
                <h2 className="h2" style={{ marginBottom: 16 }}>
                  {featured.title}
                </h2>
                <p className="lead" style={{ marginBottom: 20, fontSize: "1.08rem" }}>
                  {featured.excerpt}
                </p>
                <span className="link-arrow">
                  Lire l&apos;article <IconArrowRight width={17} height={17} />
                </span>
                <div style={{ marginTop: 18, fontSize: 13, color: "var(--muted)" }}>
                  {featured.dateLabel} · {featured.readTime} de lecture
                </div>
              </div>
              <div
                aria-hidden
                style={{
                  background:
                    "radial-gradient(600px 300px at 70% 20%, rgba(143,187,238,0.5), transparent 60%), linear-gradient(160deg, var(--navy-800), var(--navy-950))",
                  minHeight: 320,
                  display: "flex",
                  alignItems: "center",
                  justifyContent: "center",
                }}
              >
                <span
                  className="mkt-serif"
                  style={{ color: "rgba(255,255,255,0.14)", fontSize: 120, fontWeight: 700, lineHeight: 1 }}
                >
                  360°
                </span>
              </div>
            </Link>
          </Reveal>
        </div>
      </section>

      {/* Grid */}
      <section className="section" style={{ paddingTop: 0 }}>
        <div className="container">
          <div className="feature-grid">
            {rest.map((p, i) => (
              <Reveal key={p.slug} delay={(i % 3) * 90}>
                <Link href={`/blog/${p.slug}`} className="feature-card" style={{ display: "block", textDecoration: "none" }}>
                  <div
                    aria-hidden
                    style={{
                      height: 140,
                      borderRadius: 14,
                      marginBottom: 20,
                      background:
                        "radial-gradient(300px 160px at 75% 15%, rgba(143,187,238,0.45), transparent 60%), linear-gradient(160deg, var(--navy-700), var(--blue-500))",
                      display: "flex",
                      alignItems: "flex-end",
                      padding: 14,
                    }}
                  >
                    <span className="pill on-dark">{p.category}</span>
                  </div>
                  <h3>{p.title}</h3>
                  <p style={{ marginBottom: 14 }}>{p.excerpt}</p>
                  <div style={{ fontSize: 13, color: "var(--muted)" }}>
                    {p.dateLabel} · {p.readTime}
                  </div>
                </Link>
              </Reveal>
            ))}
          </div>
        </div>
      </section>

      <section className="section band-dark on-dark" style={{ textAlign: "center", paddingTop: 0 }}>
        <div className="container container-narrow">
          <Reveal as="h2" className="h2" style={{ marginBottom: 20 }}>
            Envie de passer de la théorie à la pratique ?
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
