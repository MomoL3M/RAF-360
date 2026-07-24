import type { Metadata } from "next";
import Link from "next/link";
import { notFound } from "next/navigation";
import Reveal from "@/components/marketing/Reveal";
import { POSTS, getPost } from "@/data/blog";
import { IconArrowRight } from "@/components/marketing/icons";

export function generateStaticParams() {
  return POSTS.map((p) => ({ slug: p.slug }));
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>;
}): Promise<Metadata> {
  const { slug } = await params;
  const post = getPost(slug);
  if (!post) return { title: "Article introuvable" };
  return { title: post.title, description: post.excerpt };
}

export default async function BlogPostPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const post = getPost(slug);
  if (!post) notFound();

  const related = POSTS.filter((p) => p.slug !== post.slug).slice(0, 3);

  return (
    <>
      <article>
        <section className="page-hero">
          <div className="container container-narrow">
            <Reveal>
              <Link href="/blog" className="link-arrow" style={{ marginBottom: 22, fontSize: 14 }}>
                <span style={{ transform: "rotate(180deg)", display: "inline-flex" }}>
                  <IconArrowRight width={16} height={16} />
                </span>
                Tous les articles
              </Link>
            </Reveal>
            <Reveal delay={40}>
              <span className="pill" style={{ marginBottom: 20, marginTop: 14 }}>
                <span className="pill-dot" /> {post.category}
              </span>
            </Reveal>
            <Reveal as="h1" delay={80} className="display" style={{ fontSize: "clamp(2.1rem,4.4vw,3.4rem)", marginBottom: 20 }}>
              {post.title}
            </Reveal>
            <Reveal as="p" delay={120} className="lead" style={{ marginBottom: 22 }}>
              {post.excerpt}
            </Reveal>
            <Reveal delay={160} style={{ display: "flex", alignItems: "center", gap: 12, color: "var(--muted)", fontSize: 14 }}>
              <span
                style={{
                  width: 38,
                  height: 38,
                  borderRadius: "50%",
                  background: "linear-gradient(160deg,#14306b,#2c6fb0)",
                  color: "#fff",
                  display: "inline-flex",
                  alignItems: "center",
                  justifyContent: "center",
                  fontWeight: 700,
                  fontSize: 13,
                }}
              >
                R
              </span>
              <span>
                {post.author} · {post.dateLabel} · {post.readTime} de lecture
              </span>
            </Reveal>
          </div>
        </section>

        <section className="section" style={{ paddingTop: 8 }}>
          <div className="container container-narrow">
            {post.body.map((para, i) => (
              <Reveal as="p" key={i} delay={i * 40} style={{ fontSize: "1.14rem", lineHeight: 1.75, color: "var(--ink-soft)", marginBottom: 22 }}>
                {para}
              </Reveal>
            ))}

            <Reveal
              className="surface"
              style={{
                marginTop: 36,
                background: "var(--paper-2)",
                display: "flex",
                justifyContent: "space-between",
                alignItems: "center",
                flexWrap: "wrap",
                gap: 16,
              }}
            >
              <div>
                <div style={{ fontFamily: "var(--font-serif-stack)", fontSize: "1.3rem", color: "var(--navy-900)", marginBottom: 4 }}>
                  Passez à la pratique avec RAF 360
                </div>
                <p style={{ color: "var(--muted)", fontSize: 14.5 }}>Voyez le copilote travailler sur votre dossier.</p>
              </div>
              <Link href="/app/onboarding" className="m-btn m-btn-gold">
                Demander une démo <IconArrowRight width={18} height={18} />
              </Link>
            </Reveal>
          </div>
        </section>
      </article>

      <section className="section band-soft">
        <div className="container">
          <div style={{ marginBottom: 36 }}>
            <span className="eyebrow" style={{ marginBottom: 14 }}>
              À lire aussi
            </span>
            <h2 className="h2">Poursuivez votre lecture.</h2>
          </div>
          <div className="feature-grid">
            {related.map((p, i) => (
              <Reveal key={p.slug} delay={i * 90}>
                <Link href={`/blog/${p.slug}`} className="feature-card" style={{ display: "block", textDecoration: "none" }}>
                  <span className="pill" style={{ marginBottom: 14 }}>
                    {p.category}
                  </span>
                  <h3>{p.title}</h3>
                  <p style={{ marginBottom: 14 }}>{p.excerpt}</p>
                  <span className="link-arrow" style={{ fontSize: 14 }}>
                    Lire <IconArrowRight width={16} height={16} />
                  </span>
                </Link>
              </Reveal>
            ))}
          </div>
        </div>
      </section>
    </>
  );
}
