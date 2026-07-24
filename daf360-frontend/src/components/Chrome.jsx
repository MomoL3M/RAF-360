import { C } from "../lib/tokens.js";
import Logo from "./Logo.jsx";
import { NavIcon } from "./Icon.jsx";
import { NAV } from "../data/demo.js";

export function Sidebar({ page, onNav }) {
  return (
    <aside>
      <div style={{ padding: "0 24px 24px" }}><Logo size={36} /></div>
      <nav>
        {NAV.map((n) => (
          <button key={n.id} className={`nav-item ${page === n.id ? "active" : ""}`} onClick={() => onNav(n.id)}>
            <NavIcon id={n.id} />{n.label}
          </button>
        ))}
      </nav>
      <div style={{ margin: 24, padding: 14, background: C.bg, borderRadius: 10, fontSize: 12, color: C.slate }}>
        <b style={{ color: C.navy }}>Maquette de démonstration</b><br />Données fictives · aucune connexion réelle
      </div>
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
