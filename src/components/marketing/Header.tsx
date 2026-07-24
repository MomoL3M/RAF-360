"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import Logo from "@/components/Logo";
import { IconArrowRight } from "./icons";

const LINKS = [
  { href: "/produit", label: "Produit" },
  { href: "/solutions", label: "Solutions" },
  { href: "/tarifs", label: "Tarifs" },
  { href: "/a-propos", label: "À propos" },
  { href: "/blog", label: "Blog" },
];

export default function Header() {
  const pathname = usePathname();
  const [scrolled, setScrolled] = useState(false);
  const [open, setOpen] = useState(false);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 12);
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  useEffect(() => {
    setOpen(false);
  }, [pathname]);

  return (
    <header className={`mkt-header ${scrolled ? "scrolled" : ""}`}>
      <div className="container">
        <Link href="/" className="logo-lockup" aria-label="RAF 360 — accueil">
          <Logo size={34} />
        </Link>

        <nav className="mkt-nav" aria-label="Navigation principale">
          {LINKS.map((l) => (
            <Link key={l.href} href={l.href} className={pathname === l.href ? "active" : ""}>
              {l.label}
            </Link>
          ))}
        </nav>

        <div className="header-cta">
          <Link href="/app/dashboard" className="m-btn m-btn-ghost m-btn-sm">
            Se connecter
          </Link>
          <Link href="/app/onboarding" className="m-btn m-btn-gold m-btn-sm">
            Demander une démo <IconArrowRight width={16} height={16} />
          </Link>
          <button
            className="burger"
            aria-label={open ? "Fermer le menu" : "Ouvrir le menu"}
            aria-expanded={open}
            onClick={() => setOpen((o) => !o)}
          >
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round">
              {open ? <path d="M6 6l12 12M18 6L6 18" /> : <path d="M4 7h16M4 12h16M4 17h16" />}
            </svg>
          </button>
        </div>
      </div>

      <div className={`mobile-menu ${open ? "open" : ""}`}>
        {LINKS.map((l) => (
          <Link key={l.href} href={l.href}>
            {l.label}
          </Link>
        ))}
        <Link href="/app/onboarding">Contact</Link>
        <div style={{ display: "flex", gap: 12, marginTop: 18 }}>
          <Link href="/app/dashboard" className="m-btn m-btn-ghost" style={{ flex: 1 }}>
            Se connecter
          </Link>
          <Link href="/app/onboarding" className="m-btn m-btn-gold" style={{ flex: 1 }}>
            Démo
          </Link>
        </div>
      </div>
    </header>
  );
}
