import Link from "next/link";
import Logo from "@/components/Logo";

const COLS = [
  {
    title: "Produit",
    links: [
      { href: "/produit", label: "Vue d'ensemble" },
      { href: "/produit#copilote", label: "Copilote IA" },
      { href: "/produit#tresorerie", label: "Trésorerie" },
      { href: "/app/dashboard", label: "Voir la démo" },
    ],
  },
  {
    title: "Solutions",
    links: [
      { href: "/solutions#tpe", label: "TPE" },
      { href: "/solutions#pme", label: "PME" },
      { href: "/solutions#experts", label: "Experts-comptables" },
      { href: "/tarifs", label: "Tarifs" },
    ],
  },
  {
    title: "Entreprise",
    links: [
      { href: "/a-propos", label: "À propos" },
      { href: "/blog", label: "Blog" },
      { href: "/app/onboarding", label: "Contact" },
      { href: "/app/onboarding", label: "Demander une démo" },
    ],
  },
];

export default function Footer() {
  return (
    <footer className="mkt-footer">
      <div className="container">
        <div className="footer-grid">
          <div>
            <div className="on-dark" style={{ marginBottom: 18 }}>
              <Logo size={34} dark />
            </div>
            <p style={{ maxWidth: 320, fontSize: 14.5, lineHeight: 1.65 }}>
              Le copilote financier, comptable, fiscal, social et juridique des TPE et PME françaises. Nous éclairons vos
              décisions et orientons chaque sujet réglementé vers le bon professionnel habilité.
            </p>
          </div>
          {COLS.map((c) => (
            <div key={c.title}>
              <h4>{c.title}</h4>
              {c.links.map((l, i) => (
                <Link key={i} href={l.href}>
                  {l.label}
                </Link>
              ))}
            </div>
          ))}
        </div>
        <div className="footer-bottom">
          <span>© {new Date().getFullYear()} RAF 360 — Édité par Lindbergh Formation / Groupe ARCAN. France.</span>
          <span style={{ display: "flex", gap: 20 }}>
            <Link href="/app/onboarding" style={{ padding: 0 }}>
              Mentions légales
            </Link>
            <Link href="/app/onboarding" style={{ padding: 0 }}>
              Confidentialité
            </Link>
            <Link href="/app/onboarding" style={{ padding: 0 }}>
              RGPD
            </Link>
          </span>
        </div>
      </div>
    </footer>
  );
}
