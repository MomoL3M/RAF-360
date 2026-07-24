"use client";

import { useRouter } from "next/navigation";
import { C } from "@/lib/tokens";
import { frDate, isOverdue } from "@/lib/format";
import { CASH, ECHEANCES, ACTIONS, VEILLE, statutColor } from "@/data/demo";
import { SectionTitle, Donut, Spark, Badge } from "@/components/ui";
import { Sparkle } from "@/components/Icon";
import AiInsight from "@/components/AiInsight";

export default function Dashboard() {
  const router = useRouter();
  return (
    <>
      <SectionTitle
        title="Tableau de bord"
        sub="Vue d'ensemble — le copilote surveille, anticipe et propose. Données de démonstration."
      />

      <AiInsight
        sev={C.red}
        kind="Alerte prioritaire"
        title="Risque de trésorerie détecté sur mars"
        body={
          <>
            En croisant votre échéancier fournisseurs et le <b>1er acompte d&apos;IS (≈ 4 250 €, estimatif)</b> du 15
            mars, le solde projeté tombe à <b>8 400 € en semaine 6</b>. Un encaissement client ACME de 12 000 € est
            attendu — mais après la date critique.
          </>
        }
        scenarios={[
          {
            h: "Décaler l'acompte via échéancier",
            pro: ["Préserve la trésorerie de mars", "Démarche en ligne, rapide"],
            con: ["Majoration possible si retard non encadré", "Ne règle pas le fond"],
          },
          {
            h: "Affacturage facture ACME",
            pro: ["Cash disponible avant l'échéance", "Sécurise le mois"],
            con: ["Coût de financement (~1,5 %)", "Contrat à mettre en place"],
          },
        ]}
        reco="Relancer ACME dès maintenant (échéance au 20 fév.) ; si non réglé au 5 mars, activer l'affacturage plutôt que décaler l'IS."
        cta={[
          { t: "Ouvrir la trésorerie", primary: true, onClick: () => router.push("/app/treasury") },
          { t: "Voir l'échéance IS", onClick: () => router.push("/app/calendar") },
        ]}
        src="Croisement : relevés bancaires · échéancier · calendrier fiscal · confiance 88%"
      />

      <div className="grid-auto mt16">
        <div className="card clickable kpi-card kpi-navy" onClick={() => router.push("/app/treasury")}>
          <div style={{ fontSize: 13, color: C.slate, fontWeight: 600 }}>Trésorerie disponible</div>
          <div style={{ fontSize: 30, fontWeight: 800, color: C.navy, margin: "4px 0" }}>131 200 €</div>
          <Spark pts={CASH.sol} w={220} h={54} />
          <div style={{ fontSize: 12, color: C.red, marginTop: 4, fontWeight: 600 }}>
            ▼ Prévision : 76 k€ à fin février
          </div>
        </div>

        <div className="card clickable kpi-card kpi-gold" onClick={() => router.push("/app/calendar")}>
          <div style={{ fontSize: 13, color: C.slate, fontWeight: 600, marginBottom: 10 }}>Prochaines échéances</div>
          {ECHEANCES.slice(0, 4).map((e, i, arr) => (
            <div
              key={i}
              style={{
                display: "flex",
                justifyContent: "space-between",
                alignItems: "center",
                padding: "7px 0",
                borderBottom: i < arr.length - 1 ? `1px solid ${C.line}` : "none",
              }}
            >
              <span style={{ fontSize: 13, color: C.ink }}>
                {e.t.length > 22 ? e.t.slice(0, 22) + "…" : e.t}
                {e.montant && (
                  <span style={{ color: e.mt === "réel" ? C.slate : C.gold, fontWeight: 600 }}> · {e.montant}</span>
                )}
              </span>
              <span style={{ fontSize: 12, color: isOverdue(e.iso) ? C.red : statutColor[e.statut], fontWeight: 600 }}>
                {frDate(e.iso)}
              </span>
            </div>
          ))}
        </div>

        <div className="card clickable kpi-card kpi-blue" onClick={() => router.push("/app/factures")}>
          <div style={{ fontSize: 13, color: C.slate, fontWeight: 600, marginBottom: 8 }}>Préparation e-facture</div>
          <div style={{ display: "flex", alignItems: "center", gap: 14 }}>
            <Donut pct={65} />
            <div style={{ fontSize: 12, color: C.slate }}>
              Prêt pour la <b style={{ color: C.ink }}>réception obligatoire du 1er sept. 2026</b>
            </div>
          </div>
        </div>

        <div className="card clickable kpi-card kpi-red" onClick={() => router.push("/app/actions")}>
          <div style={{ fontSize: 13, color: C.slate, fontWeight: 600, marginBottom: 10 }}>Actions prioritaires</div>
          {ACTIONS.slice(0, 3).map((a, i) => (
            <div key={i} style={{ display: "flex", gap: 8, alignItems: "flex-start", padding: "6px 0" }}>
              <span className="dot" style={{ background: statutColor[a.statut], marginTop: 5, flexShrink: 0 }} />
              <span style={{ fontSize: 13, color: C.ink }}>{a.t}</span>
            </div>
          ))}
        </div>
      </div>

      <div style={{ marginTop: 24, marginBottom: 12, display: "flex", alignItems: "center", gap: 8 }}>
        <Sparkle color={C.navy} />
        <h3 className="serif" style={{ fontSize: 18, color: C.navy }}>
          Veille intelligente
        </h3>
        <span style={{ fontSize: 12, color: C.slate }}>— surveillance continue de ce qui vous concerne</span>
      </div>
      <div className="grid-auto">
        {VEILLE.map((v, i) => (
          <div className="ai" style={{ ["--sev" as string]: v.sev } as React.CSSProperties} key={i}>
            <div className="ai-tag" style={{ ["--sev" as string]: v.sev } as React.CSSProperties}>
              <Sparkle color={v.sev} /> Veille {v.t}
            </div>
            <div className="ai-body" style={{ marginTop: 7 }}>
              {v.body}
            </div>
            <div style={{ marginTop: 10 }}>
              <Badge color={v.sev}>{v.tag}</Badge>
            </div>
          </div>
        ))}
      </div>

      <div className="hero-band" style={{ marginTop: 24 }}>
        <div>
          <div className="serif" style={{ fontSize: 18, fontWeight: 700 }}>
            Votre entreprise est-elle prête pour la prochaine échéance ?
          </div>
          <div style={{ fontSize: 14, color: C.ice, marginTop: 6 }}>
            Le copilote analyse vos documents, vos risques et vos scénarios possibles.
          </div>
        </div>
        <button className="btn btn-accent" onClick={() => router.push("/app/actions")}>
          Lancer le diagnostic
        </button>
      </div>
    </>
  );
}
