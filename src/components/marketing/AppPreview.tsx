import { Sparkle } from "@/components/Icon";

/** Aperçu stylisé du produit (mini tableau de bord) affiché dans un cadre vitré. */
export default function AppPreview() {
  const sol = [46, 53, 63, 70, 76, 89, 104, 117, 131, 109, 88, 76];
  const W = 520,
    H = 150,
    pad = 8;
  const max = Math.max(...sol),
    min = Math.min(...sol),
    rng = max - min || 1;
  const step = (W - pad * 2) / (sol.length - 1);
  const realN = 9;
  const pt = (v: number, i: number) => `${(pad + i * step).toFixed(1)},${(H - pad - ((v - min) / rng) * (H - pad * 2)).toFixed(1)}`;
  const realPts = sol.slice(0, realN).map((v, i) => pt(v, i)).join(" ");
  const fcPts = sol.slice(realN - 1).map((v, i) => pt(v, i + realN - 1)).join(" ");

  return (
    <div className="product-frame">
      <div className="inner">
        <div className="win-dots">
          <i />
          <i />
          <i />
          <span style={{ marginLeft: 10, fontSize: 12, color: "#8b97b5", fontWeight: 600 }}>
            RAF 360 — Tableau de bord
          </span>
        </div>
        <div style={{ padding: 18, background: "#f7f9fd" }}>
          {/* Copilote alert */}
          <div
            style={{
              position: "relative",
              background: "#fff",
              border: "1px solid #e4eaf5",
              borderRadius: 12,
              padding: "13px 15px 13px 17px",
              overflow: "hidden",
            }}
          >
            <span style={{ position: "absolute", left: 0, top: 0, bottom: 0, width: 4, background: "#C0503F" }} />
            <div
              style={{
                display: "inline-flex",
                alignItems: "center",
                gap: 6,
                fontSize: 10.5,
                fontWeight: 700,
                letterSpacing: 0.4,
                textTransform: "uppercase",
                color: "#C0503F",
              }}
            >
              <Sparkle color="#C0503F" size={12} /> Copilote RAF 360 · Alerte prioritaire
            </div>
            <div style={{ fontSize: 13.5, fontWeight: 700, color: "#101b3a", margin: "6px 0 3px" }}>
              Risque de trésorerie détecté sur mars
            </div>
            <div style={{ fontSize: 12, color: "#64708f", lineHeight: 1.5 }}>
              Le 1er acompte d&apos;IS (≈ 4 250 €) tombe avant l&apos;encaissement ACME. Deux scénarios sont prêts.
            </div>
          </div>

          {/* KPI row */}
          <div style={{ display: "grid", gridTemplateColumns: "repeat(3,1fr)", gap: 10, marginTop: 12 }}>
            {[
              ["Trésorerie", "131 200 €", "#14306b"],
              ["Encaissé (mois)", "72 000 €", "#2FA37C"],
              ["Décaissé (mois)", "58 000 €", "#C0503F"],
            ].map((k, i) => (
              <div key={i} style={{ background: "#fff", border: "1px solid #e4eaf5", borderRadius: 10, padding: "11px 12px" }}>
                <div style={{ fontSize: 10.5, color: "#64708f" }}>{k[0]}</div>
                <div style={{ fontSize: 17, fontWeight: 800, color: k[2], marginTop: 3 }}>{k[1]}</div>
              </div>
            ))}
          </div>

          {/* chart */}
          <div style={{ background: "#fff", border: "1px solid #e4eaf5", borderRadius: 10, padding: 12, marginTop: 12 }}>
            <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 6 }}>
              <span style={{ fontSize: 12, fontWeight: 700, color: "#14306b" }}>Trésorerie — 12 mois</span>
              <span style={{ display: "inline-flex", gap: 12, fontSize: 10.5, color: "#64708f" }}>
                <span style={{ display: "inline-flex", alignItems: "center", gap: 5 }}>
                  <span style={{ width: 12, height: 3, borderRadius: 2, background: "#2FA37C", display: "inline-block" }} />
                  Réalisé
                </span>
                <span style={{ display: "inline-flex", alignItems: "center", gap: 5 }}>
                  <span style={{ width: 12, height: 3, borderRadius: 2, background: "#EDA323", display: "inline-block" }} />
                  Prévisionnel
                </span>
              </span>
            </div>
            <svg viewBox={`0 0 ${W} ${H}`} width="100%" style={{ display: "block" }}>
              <defs>
                <linearGradient id="prevFill" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0" stopColor="#2FA37C" stopOpacity="0.16" />
                  <stop offset="1" stopColor="#2FA37C" stopOpacity="0" />
                </linearGradient>
              </defs>
              <polygon points={`${pad},${H - pad} ${realPts} ${(pad + (realN - 1) * step).toFixed(1)},${H - pad}`} fill="url(#prevFill)" />
              <polyline points={realPts} fill="none" stroke="#2FA37C" strokeWidth="2.6" strokeLinecap="round" strokeLinejoin="round" />
              <polyline points={fcPts} fill="none" stroke="#EDA323" strokeWidth="2.6" strokeDasharray="5 4" strokeLinecap="round" strokeLinejoin="round" />
              {sol.map((v, i) => (
                <circle key={i} cx={pad + i * step} cy={H - pad - ((v - min) / rng) * (H - pad * 2)} r="3" fill={i < realN ? "#2FA37C" : "#EDA323"} stroke="#fff" strokeWidth="1.5" />
              ))}
            </svg>
          </div>
        </div>
      </div>
    </div>
  );
}
