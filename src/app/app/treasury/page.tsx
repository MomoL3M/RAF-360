"use client";

import { useState } from "react";
import { C } from "@/lib/tokens";
import { CASH, CASH_ALERTS } from "@/data/demo";
import { SectionTitle, Badge } from "@/components/ui";
import AiInsight from "@/components/AiInsight";

type Tip = { i: number; x: number; y: number };

export default function Treasury() {
  const [zoom, setZoom] = useState(0); // 0 = 12 mois, +1/+2 = resserrer
  const [sel, setSel] = useState<number | null>(null);
  const [tip, setTip] = useState<Tip | null>(null);

  const start = zoom > 0 ? Math.min(zoom * 3, 8) : 0;
  const idx: number[] = [];
  for (let i = start; i < 12; i++) idx.push(i);

  const enc = CASH.ent[CASH.realIdx],
    dec = CASH.sor[CASH.realIdx];

  return (
    <>
      <SectionTitle
        title="Trésorerie"
        sub="Position consolidée, réalisée et prévisionnelle — simulation à valider, non comptable"
      />

      <div className="grid-3" style={{ marginBottom: 16 }}>
        {(
          [
            ["Solde de trésorerie disponible", "131 200 €", C.navy, "3 comptes bancaires"],
            ["Encaissements du mois", enc.toLocaleString("fr-FR") + " 000 €", C.green, "Novembre 2025"],
            ["Décaissements du mois", dec.toLocaleString("fr-FR") + " 000 €", C.red, "Novembre 2025"],
          ] as [string, string, string, string][]
        ).map((k, i) => (
          <div className={`card kpi-card ${["kpi-navy", "kpi-green", "kpi-red"][i]}`} key={i}>
            <div style={{ fontSize: 13, color: C.slate }}>{k[0]}</div>
            <div style={{ fontSize: 26, fontWeight: 800, color: k[2], margin: "4px 0" }}>{k[1]}</div>
            <div style={{ fontSize: 12, color: C.slate }}>{k[3]}</div>
          </div>
        ))}
      </div>

      <AiInsight
        sev={C.red}
        kind="Anticipation"
        title="Le point bas arrive en janvier–février"
        body={
          <>
            La courbe prévisionnelle décroche de <b>131 k€ (nov.) à 76 k€ (fév.)</b>. Deux façons de traiter ce creux —
            le copilote compare :
          </>
        }
        scenarios={[
          {
            h: "Ligne de trésorerie court terme",
            pro: ["Immédiat, souple", "Coût maîtrisé si remboursée vite"],
            con: ["Intérêts sur tirage", "Négociation banque à prévoir"],
          },
          {
            h: "Renégocier les délais fournisseurs",
            pro: ["Aucun coût financier", "Effet durable sur le BFR"],
            con: ["Dépend de l'accord fournisseur", "Effet plus lent"],
          },
        ]}
        reco="Combiner : négocier +15 j sur le principal fournisseur (déc.) et garder la ligne CT en filet de sécurité."
        src="Prévision 13 semaines · échéancier fournisseurs · confiance 85%"
      />

      <div className="card mt16">
        <div
          style={{
            display: "flex",
            justifyContent: "space-between",
            alignItems: "center",
            flexWrap: "wrap",
            gap: 10,
            marginBottom: 8,
          }}
        >
          <div style={{ display: "flex", gap: 10, alignItems: "center", flexWrap: "wrap" }}>
            <span className="chip">📅 Mars 2025 – Févr. 2026</span>
            <span className="chip">🏦 Tous les comptes</span>
            <Legend color={C.green} label="Fonctionnelle (réalisé)" />
            <Legend color={C.gold} label="Prévisionnelle" />
          </div>
          <div style={{ display: "flex", gap: 6 }}>
            <button className="btn btn-ghost btn-xs" onClick={() => setZoom((z) => Math.max(0, z - 1))} title="Dézoomer">
              ➖
            </button>
            <button className="btn btn-ghost btn-xs" onClick={() => setZoom((z) => Math.min(2, z + 1))} title="Zoomer">
              ➕
            </button>
          </div>
        </div>

        <Chart idx={idx} sel={sel} onPick={(i) => setSel((s) => (s === i ? null : i))} onTip={setTip} />

        <div style={{ fontSize: 12, color: C.slate, marginTop: 6 }}>
          Survolez un point pour le détail, cliquez pour générer le tableau du mois. Zoom ➕ / ➖ pour resserrer la
          période.
        </div>

        <Detail sel={sel} />
      </div>

      <div className="card mt16">
        <div style={{ fontSize: 13, fontWeight: 700, color: C.slate, marginBottom: 12 }}>Alertes d&apos;encaissement</div>
        {CASH_ALERTS.map((a, i) => (
          <div
            key={i}
            style={{
              display: "flex",
              justifyContent: "space-between",
              alignItems: "center",
              padding: "11px 12px",
              borderRadius: 8,
              marginBottom: 8,
              background: a.st === "en retard" ? C.redl : C.ice + "44",
            }}
          >
            <div>
              <div style={{ fontSize: 14, fontWeight: 600, color: C.ink }}>
                {a.c} — <span style={{ color: a.st === "en retard" ? C.red : C.navy }}>{a.m}</span>
              </div>
              <div style={{ fontSize: 12, color: C.slate }}>
                {a.mode} · échéance {a.d}
              </div>
            </div>
            <Badge color={a.st === "en retard" ? C.red : C.navy}>
              {a.st === "en retard" ? "En retard" : "Encaissement attendu"}
            </Badge>
          </div>
        ))}
      </div>

      {tip !== null && (
        <div className="cash-tip" style={{ left: tip.x + 14, top: tip.y - 10 }}>
          <div style={{ fontWeight: 700, marginBottom: 4 }}>
            {CASH.lab[tip.i]} 20{CASH.yr[tip.i]} — {tip.i <= CASH.realIdx ? "réalisé" : "prévisionnel"}
          </div>
          Solde : <b>{CASH.sol[tip.i]} k€</b>
          <br />
          Encaissements : {CASH.ent[tip.i]} k€
          <br />
          Décaissements : {CASH.sor[tip.i]} k€
        </div>
      )}
    </>
  );
}

function Legend({ color, label }: { color: string; label: string }) {
  return (
    <span style={{ display: "inline-flex", alignItems: "center", gap: 6, fontSize: 12, color: C.slate }}>
      <span style={{ width: 12, height: 3, background: color, display: "inline-block", borderRadius: 2 }} />
      {label}
    </span>
  );
}

function Chart({
  idx,
  sel,
  onPick,
  onTip,
}: {
  idx: number[];
  sel: number | null;
  onPick: (i: number) => void;
  onTip: (t: Tip | null) => void;
}) {
  const W = 640,
    H = 280,
    padL = 48,
    padB = 34,
    padT = 14,
    padR = 14;
  const plotW = W - padL - padR,
    plotH = H - padT - padB;
  const maxV = Math.max(...CASH.ent, ...CASH.sor, ...CASH.sol) * 1.12;
  const yOf = (v: number) => padT + plotH - (v / maxV) * plotH;
  const n = idx.length,
    band = plotW / n;
  const cx = (k: number) => padL + band * (k + 0.5);

  const ticks = 5;
  const gy = [];
  for (let t = 0; t <= ticks; t++) {
    const v = (maxV * t) / ticks,
      y = yOf(v);
    gy.push(
      <g key={t}>
        <line x1={padL} y1={y} x2={W - padR} y2={y} stroke={C.line} strokeWidth="1" />
        <text x={padL - 8} y={y + 4} textAnchor="end" fontSize="10" fill={C.slate}>
          {Math.round(v)}k€
        </text>
      </g>
    );
  }

  const realPts: string[] = [],
    fcPts: string[] = [];
  idx.forEach((gi, k) => {
    const s = `${cx(k).toFixed(1)},${yOf(CASH.sol[gi]).toFixed(1)}`;
    (gi <= CASH.realIdx ? realPts : fcPts).push(s);
  });
  const lastRealK = idx.filter((gi) => gi <= CASH.realIdx).length - 1;

  return (
    <svg viewBox={`0 0 ${W} ${H}`} width="100%" style={{ display: "block" }}>
      {gy}
      {idx.map((gi, k) => {
        const bw = band * 0.22,
          c = cx(k);
        const real = gi <= CASH.realIdx;
        return (
          <g key={"b" + k}>
            <rect
              x={c - bw - 2}
              y={yOf(CASH.ent[gi])}
              width={bw}
              height={(CASH.ent[gi] / maxV) * plotH}
              rx="2"
              fill={C.blue}
              opacity={real ? 0.55 : 0.28}
            />
            <rect
              x={c + 2}
              y={yOf(CASH.sor[gi])}
              width={bw}
              height={(CASH.sor[gi] / maxV) * plotH}
              rx="2"
              fill={C.red}
              opacity={real ? 0.5 : 0.26}
            />
          </g>
        );
      })}
      {realPts.length > 1 && (
        <polyline points={realPts.join(" ")} fill="none" stroke={C.green} strokeWidth="2.8" strokeLinecap="round" strokeLinejoin="round" />
      )}
      {lastRealK >= 0 && lastRealK < n - 1 && (
        <line
          x1={cx(lastRealK)}
          y1={yOf(CASH.sol[idx[lastRealK]])}
          x2={cx(lastRealK + 1)}
          y2={yOf(CASH.sol[idx[lastRealK + 1]])}
          stroke={C.gold}
          strokeWidth="2.5"
          strokeDasharray="5 4"
        />
      )}
      {fcPts.length > 1 && (
        <polyline
          points={fcPts.join(" ")}
          fill="none"
          stroke={C.gold}
          strokeWidth="2.8"
          strokeDasharray="5 4"
          strokeLinecap="round"
          strokeLinejoin="round"
        />
      )}
      {idx.map((gi, k) => {
        const c = cx(k),
          y = yOf(CASH.sol[gi]);
        const real = gi <= CASH.realIdx,
          isSel = sel === gi;
        return (
          <g key={"p" + k}>
            <circle
              cx={c}
              cy={y}
              r={isSel ? 6 : 4.5}
              fill={real ? C.green : C.gold}
              stroke="#fff"
              strokeWidth="2"
              style={{ cursor: "pointer" }}
              onMouseEnter={(e) => onTip({ i: gi, x: e.clientX, y: e.clientY })}
              onMouseMove={(e) => onTip({ i: gi, x: e.clientX, y: e.clientY })}
              onMouseLeave={() => onTip(null)}
              onClick={() => onPick(gi)}
            />
            <text x={c} y={H - padB + 16} textAnchor="middle" fontSize="10" fill={C.slate}>
              {CASH.lab[gi]}
            </text>
            <text x={c} y={H - padB + 27} textAnchor="middle" fontSize="9" fill={C.slate}>
              &apos;{CASH.yr[gi]}
            </text>
          </g>
        );
      })}
      <line x1={padL} y1={padT} x2={padL} y2={padT + plotH} stroke={C.slate} strokeWidth="1" />
      <line x1={padL} y1={padT + plotH} x2={W - padR} y2={padT + plotH} stroke={C.slate} strokeWidth="1" />
    </svg>
  );
}

function Detail({ sel }: { sel: number | null }) {
  if (sel === null) {
    return (
      <div
        style={{
          padding: "14px 16px",
          color: C.slate,
          fontSize: 13,
          background: C.bg,
          borderRadius: 10,
          marginTop: 14,
        }}
      >
        👆 Cliquez sur un point de la courbe pour afficher le détail du mois.
      </div>
    );
  }
  const i = sel,
    real = i <= CASH.realIdx;
  const debut = i === 0 ? 40 : CASH.sol[i - 1];
  const catE: [string, number][] = [
    ["Ventes / prestations", Math.round(CASH.ent[i] * 0.78)],
    ["Subventions & aides", Math.round(CASH.ent[i] * 0.09)],
    ["Autres encaissements", Math.round(CASH.ent[i] * 0.13)],
  ];
  const catS: [string, number][] = [
    ["Salaires & charges", Math.round(CASH.sor[i] * 0.46)],
    ["Fournisseurs", Math.round(CASH.sor[i] * 0.34)],
    ["Fiscalité & prêts", Math.round(CASH.sor[i] * 0.2)],
  ];
  const Row = ({ l, v, c }: { l: string; v: number; c?: string }) => (
    <div
      style={{
        display: "flex",
        justifyContent: "space-between",
        padding: "7px 0",
        fontSize: 13,
        borderBottom: `1px solid ${C.line}`,
      }}
    >
      <span style={{ color: C.slate }}>{l}</span>
      <span style={{ fontWeight: 600, color: c || C.ink }}>{v} k€</span>
    </div>
  );
  return (
    <div className="card" style={{ marginTop: 14 }}>
      <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 10 }}>
        <div style={{ fontSize: 15, fontWeight: 700, color: C.navy }}>
          {CASH.lab[i]} 20{CASH.yr[i]} — détail {real ? "(réalisé)" : "(prévisionnel)"}
        </div>
        <Badge color={real ? C.green : C.gold}>{real ? "Trésorerie fonctionnelle" : "Trésorerie prévisionnelle"}</Badge>
      </div>
      <div className="grid-half">
        <div>
          <div style={{ fontSize: 12, fontWeight: 700, color: C.blue, textTransform: "uppercase", marginBottom: 4 }}>
            Encaissements — {CASH.ent[i]} k€
          </div>
          {catE.map((x, k) => (
            <Row key={k} l={x[0]} v={x[1]} c={C.blue} />
          ))}
        </div>
        <div>
          <div style={{ fontSize: 12, fontWeight: 700, color: C.red, textTransform: "uppercase", marginBottom: 4 }}>
            Décaissements — {CASH.sor[i]} k€
          </div>
          {catS.map((x, k) => (
            <Row key={k} l={x[0]} v={x[1]} c={C.red} />
          ))}
        </div>
      </div>
      <div
        style={{
          display: "flex",
          justifyContent: "space-between",
          marginTop: 12,
          paddingTop: 12,
          borderTop: `2px solid ${C.line}`,
          fontSize: 14,
        }}
      >
        <span style={{ color: C.slate }}>Solde début {debut} k€ → solde fin</span>
        <span style={{ fontWeight: 800, color: CASH.sol[i] < 40 ? C.red : C.navy }}>{CASH.sol[i]} k€</span>
      </div>
      {!real && (
        <div style={{ marginTop: 12 }}>
          <AiInsight
            sev={CASH.sol[i] < 90 ? C.red : C.gold}
            kind="Anticipation"
            title={CASH.sol[i] < 90 ? "Mois sous tension" : "Mois à surveiller"}
            body={
              <>
                Sur ce mois prévisionnel, les décaissements dépassent les encaissements de{" "}
                <b>{CASH.sor[i] - CASH.ent[i]} k€</b>. Le copilote recommande d&apos;anticiper un levier de financement
                ou d&apos;étaler un décaissement.
              </>
            }
          />
        </div>
      )}
    </div>
  );
}
