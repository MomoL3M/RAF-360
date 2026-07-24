import { useState } from "react";
import { C } from "../lib/tokens.js";
import { DOC_TREE } from "../data/demo.js";
import { SectionTitle, Badge } from "../components/ui.jsx";
import AiInsight from "../components/AiInsight.jsx";

export default function Documents() {
  const [open, setOpen] = useState({ corp: true, biz: false, rh: false });
  const toggle = (k) => setOpen((o) => ({ ...o, [k]: !o[k] }));

  return (
    <>
      <SectionTitle title="Coffre-fort documentaire" sub="Dépôt, OCR et classement automatique par arborescence — score de confiance par pièce" />
      <AiInsight
        sev={C.gold}
        kind="Veille documentaire"
        title="3 pièces manquantes pour une analyse fiable"
        body="Le contrat client BÉTA a une confiance de 72 % (clause de responsabilité illisible). Il manque le relevé bancaire de décembre et la liasse fiscale N-1. Le copilote a déjà généré les demandes."
        cta={[{ t: "Réclamer les pièces", primary: true }, { t: "Simulateur de paie" }]}
      />

      <div style={{ border: `2px dashed ${C.line}`, borderRadius: 12, padding: 24, textAlign: "center", margin: "16px 0", background: C.card }}>
        <div style={{ fontSize: 15, color: C.navy, fontWeight: 600 }}>Glissez vos documents ici</div>
        <div style={{ fontSize: 13, color: C.slate, marginTop: 4 }}>ou par email, mobile, scanner · PDF, images, XML acceptés — classement automatique</div>
        <div style={{ marginTop: 12 }}><button className="btn btn-primary btn-sm">Parcourir</button></div>
      </div>

      <div className="card" style={{ padding: 0 }}>
        {Object.keys(DOC_TREE).map((key) => {
          const f = DOC_TREE[key], isOpen = open[key];
          return (
            <div key={key}>
              <button className="tree-row" onClick={() => toggle(key)}>
                <span className={`tree-caret ${isOpen ? "open" : ""}`}>▶</span>
                <span style={{ fontSize: 16 }}>{f.icon}</span>
                <span style={{ fontSize: 14, fontWeight: 700, color: C.navy, flex: 1 }}>{f.label}</span>
                <Badge color={C.navy2}>{f.files.length} pièces</Badge>
              </button>
              {isOpen && (
                <>
                  <div className="tree-head"><span>Document</span><span>Type</span><span>Confiance</span><span>Date</span></div>
                  {f.files.map((d, i) => (
                    <div className="tree-child" key={i}>
                      <span style={{ color: C.ink, fontWeight: 500 }}>{d.n}</span>
                      <span><Badge color={C.navy2}>{d.type}</Badge></span>
                      <span style={{ color: d.conf >= 85 ? C.green : C.gold, fontWeight: 700 }}>{d.conf}%</span>
                      <span style={{ color: C.slate }}>{d.date}</span>
                    </div>
                  ))}
                </>
              )}
            </div>
          );
        })}
      </div>

      <div className="card mt16" style={{ display: "flex", justifyContent: "space-between", alignItems: "center", flexWrap: "wrap", gap: 12 }}>
        <div>
          <div style={{ fontSize: 14, fontWeight: 700, color: C.navy }}>🧮 Simulateur de paie</div>
          <div style={{ fontSize: 13, color: C.slate, marginTop: 3 }}>Estimez un coût employeur, un net à payer ou une prime — pré-contrôle avant transmission au gestionnaire.</div>
        </div>
        <button className="btn btn-ghost btn-sm">Ouvrir le simulateur</button>
      </div>
    </>
  );
}
