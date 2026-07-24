"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { C } from "@/lib/tokens";
import Logo from "./Logo";
import { NavIcon } from "./Icon";
import { NAV } from "@/data/demo";

export function Sidebar() {
  const pathname = usePathname();
  return (
    <aside>
      <div style={{ padding: "0 24px 24px" }}>
        <Logo size={36} />
      </div>
      <nav>
        {NAV.map((n) => {
          const active = pathname === n.href;
          return (
            <Link key={n.id} href={n.href} className={`nav-item ${active ? "active" : ""}`}>
              <NavIcon id={n.id} />
              {n.label}
            </Link>
          );
        })}
      </nav>
      <div style={{ margin: 24, padding: 14, background: C.bg, borderRadius: 10, fontSize: 12, color: C.slate }}>
        <b style={{ color: C.navy }}>Maquette de démonstration</b>
        <br />
        Données fictives · aucune connexion réelle
      </div>
      <Link
        href="/"
        style={{
          display: "flex",
          alignItems: "center",
          gap: 8,
          margin: "0 24px",
          padding: "10px 12px",
          borderRadius: 10,
          fontSize: 13,
          fontWeight: 600,
          color: C.navy,
          textDecoration: "none",
          border: `1px solid ${C.line}`,
        }}
      >
        ← Retour au site
      </Link>
    </aside>
  );
}

export function Topbar() {
  return (
    <header className="top">
      <div style={{ display: "flex", alignItems: "center", gap: 10 }}>
        <span style={{ fontSize: 14, color: C.slate }}>Entité :</span>
        <span style={{ fontSize: 14, fontWeight: 600, color: C.navy }}>ARCAN Démo SAS ▾</span>
      </div>
      <div style={{ display: "flex", alignItems: "center", gap: 16 }}>
        <span style={{ fontSize: 18, cursor: "pointer" }}>🔔</span>
        <div className="avatar">JD</div>
      </div>
    </header>
  );
}
